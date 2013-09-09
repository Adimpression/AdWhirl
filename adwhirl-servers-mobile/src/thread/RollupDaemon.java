/*
  Copyright 2009-2010 AdMob, Inc.

  Licensed under the Apache License, Version 2.0 (the "License");
  you may not use this file except in compliance with the License.
  You may obtain a copy of the License at

  http://www.apache.org/licenses/LICENSE-2.0

  Unless required by applicable law or agreed to in writing, software
  distributed under the License is distributed on an "AS IS" BASIS,
  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
  See the License for the specific language governing permissions and
  limitations under the License.
 */

package thread;


import java.text.SimpleDateFormat;

import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.GregorianCalendar;
import java.util.List;
import java.util.TimeZone;
import java.util.UUID;
import java.util.concurrent.atomic.AtomicInteger;

import org.apache.log4j.Logger;

import com.amazonaws.services.simpledb.AmazonSimpleDB;
import com.amazonaws.services.simpledb.model.Attribute;
import com.amazonaws.services.simpledb.model.DeleteAttributesRequest;
import com.amazonaws.services.simpledb.model.Item;
import com.amazonaws.services.simpledb.model.PutAttributesRequest;
import com.amazonaws.services.simpledb.model.ReplaceableAttribute;
import com.amazonaws.services.simpledb.model.SelectRequest;
import com.amazonaws.services.simpledb.model.SelectResult;
import com.amazonaws.services.simpledb.util.SimpleDBUtils;

import util.AdWhirlUtil;

public class RollupDaemon implements Runnable {
	static Logger log = Logger.getLogger("RollupDaemon");

	private static AmazonSimpleDB sdb;

	AtomicInteger impressionsFound;
	
	public void run() {
		log.info("RollupDaemon started");

		sdb = AdWhirlUtil.getSDB();

		int threadId = 1;

		//We're a makeshift daemon, let's loop forever
		while(true) {		
			impressionsFound = new AtomicInteger(0);
			
			List<Thread> threads = new ArrayList<Thread>();

			String invalidsNextToken = null;

			do {
				SelectRequest invalidsRequest = new SelectRequest("select * from `" + AdWhirlUtil.DOMAIN_STATS_INVALID + "`");
				invalidsRequest.setNextToken(invalidsNextToken);
				try {
					SelectResult invalidsResult = sdb.select(invalidsRequest);
					invalidsNextToken = invalidsResult.getNextToken();
					List<Item> invalidsList = invalidsResult.getItems();

					Thread helper = new Thread(new RollupHelper(invalidsList, threadId++, impressionsFound));
					threads.add(helper);
					helper.start();
				}
				catch(Exception e) {
					AdWhirlUtil.logException(e, log);

					// Eventually we'll get a 'stale request' error and need to start over.
					invalidsNextToken = null;
				}
			}
			while(invalidsNextToken != null);

			for(Thread thread : threads) {
				try {
					thread.join();
				} 
				catch(InterruptedException e) {
					e.printStackTrace();
				}
			}
			
			log.error("Impressions found: " + impressionsFound);
			
			try {
				Thread.sleep(2 * 60000);
			} catch (InterruptedException e) {
				log.error("Unable to sleep... continuing");
			}
		}
	}

	private class RollupHelper implements Runnable {
		List<Item> invalidsList;
		int threadId;
		AtomicInteger impressionsFound;

		public RollupHelper(List<Item> invalidsList, int threadId, AtomicInteger impressionsFound) {
			this.invalidsList = invalidsList;
			this.threadId = threadId;
			this.impressionsFound = impressionsFound;
		}

		public void run() {
			String nid = null;
			String aid = null;

			for(Item invalidsItem : invalidsList) {								
				nid = invalidsItem.getName();

				List<Attribute> attributeList = invalidsItem.getAttributes();
				for(Attribute attribute : attributeList) {
					String attributeName = attribute.getName();
					if(attributeName.equals("aid")) {
						aid = attribute.getValue();

						// Must be inside for nids with multiple aids (i.e. customs)
						rollupNetworkStats(nid, aid);
					}
				}

				deleteTempStatsInvalid(nid);
			}

			log.info("[Thread " + this.threadId + "] Exiting.");
		}

		private void rollupNetworkStats(String nid, String aid) {
			if(nid == null || aid == null) {
				log.warn("Null parameter passed, nid=\"" + nid + "\" and aid=\"" + aid + "\"");
				return;
			}

			String statsNextToken = null;

			List<String> sids = new ArrayList<String>();
			int impressions = 0;
			int clicks = 0;
			int type = 0;

			do {
				SelectRequest statsRequest = new SelectRequest("select * from `" + AdWhirlUtil.DOMAIN_STATS_TEMP + "` where `nid` = '" + nid + "' and `aid` = '" + aid + "'");
				statsRequest.setConsistentRead(true);
				statsRequest.setNextToken(statsNextToken);
				try {
					SelectResult statsResult = sdb.select(statsRequest);
					statsNextToken = statsResult.getNextToken();
					List<Item> statsList = statsResult.getItems();

					String sid = null;

					for(Item statsItem : statsList) {	
						sid = statsItem.getName();
						sids.add(sid);

						List<Attribute> attributeList = statsItem.getAttributes();
						for(Attribute attribute : attributeList) {
							String attributeName = attribute.getName();
							if(attributeName.equals("impressions")) {
								impressions += SimpleDBUtils.decodeZeroPaddingInt(attribute.getValue());
								impressionsFound.addAndGet(impressions);
							}
							else if(attributeName.equals("clicks")) {
								clicks += SimpleDBUtils.decodeZeroPaddingInt(attribute.getValue());
							}
							else if(attributeName.equals("type")) {
								type = SimpleDBUtils.decodeZeroPaddingInt(attribute.getValue());
							}
						}
					}
				}
				catch(Exception e) {
					AdWhirlUtil.logException(e, log);
					return;
				}
			}
			while(statsNextToken != null);

			if(updateStats(nid, aid, impressions, clicks, type)) {
				deleteTempStats(sids);
			}
		}

		private boolean updateStats(String nid, String aid, int impressions, int clicks, int type) {
			Date today = new Date();
			today = startOfDay(today);

			SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd");
			sdf.setTimeZone(TimeZone.getTimeZone("GMT"));
			String dateTime = sdf.format(today);

			String itemName = null;

			SelectRequest statsRequest = new SelectRequest("select * from `" + AdWhirlUtil.DOMAIN_STATS + "` where `nid` = '" + nid + "' and `aid` = '" + aid + "' and `dateTime` = '" + dateTime + "'");
			try {
				SelectResult statsResult = sdb.select(statsRequest);
				List<Item> statsList = statsResult.getItems();

				for(Item statsItem : statsList) {	
					itemName = statsItem.getName();
					List<Attribute> attributeList = statsItem.getAttributes();
					for(Attribute attribute : attributeList) {
						String attributeName = attribute.getName();
						if(attributeName.equals("impressions")) {
							impressions += SimpleDBUtils.decodeZeroPaddingInt(attribute.getValue());
						}
						else if(attributeName.equals("clicks")) {
							clicks += SimpleDBUtils.decodeZeroPaddingInt(attribute.getValue());
						}
					}
				}
				
				log.warn("[Thread " + this.threadId + "] Pushing: date <" + dateTime + ">, nid <" + nid + ">, aid <" + aid + ">, impressions <" + impressions + ">, clicks <" + clicks + ">");
				List<ReplaceableAttribute> list = new ArrayList<ReplaceableAttribute>();
				list.add(new ReplaceableAttribute().withName("impressions").withValue(String.valueOf(impressions)).withReplace(true));
				list.add(new ReplaceableAttribute().withName("clicks").withValue(String.valueOf(clicks)).withReplace(true));
				list.add(new ReplaceableAttribute().withName("dateTime").withValue(dateTime).withReplace(true));
				list.add(new ReplaceableAttribute().withName("nid").withValue(nid).withReplace(true));
				list.add(new ReplaceableAttribute().withName("aid").withValue(aid).withReplace(true));
				list.add(new ReplaceableAttribute().withName("type").withValue(String.valueOf(type)).withReplace(true));

				if(itemName == null) {
					itemName = UUID.randomUUID().toString().replace("-", "");
				}

				putItem(AdWhirlUtil.DOMAIN_STATS, itemName, list);
			}
			catch(Exception e) {
				AdWhirlUtil.logException(e, log);
				return false;
			}

			return true;
		}

		private Date startOfDay(Date date) {
			Calendar calendar = new GregorianCalendar();
			calendar.setTimeZone(TimeZone.getTimeZone("GMT"));
			calendar.setTime(date);
			calendar.set(Calendar.HOUR_OF_DAY, 0);
			calendar.set(Calendar.MINUTE, 0);
			calendar.set(Calendar.SECOND, 0);
			calendar.set(Calendar.MILLISECOND, 0);
			return calendar.getTime();
		}

		private void deleteTempStats(List<String> sids) {
			for(String sid : sids) {
				log.debug("Deleting sid=" + sid);
				DeleteAttributesRequest deleteRequest = new DeleteAttributesRequest(AdWhirlUtil.DOMAIN_STATS_TEMP, sid);
				try {
					sdb.deleteAttributes(deleteRequest);
				} catch (Exception e) {
					AdWhirlUtil.logException(e, log);
					return;
				}
			}
		}

		private void deleteTempStatsInvalid(String nid) {
			log.debug("Deleting nid=" + nid);
			DeleteAttributesRequest deleteRequest = new DeleteAttributesRequest(AdWhirlUtil.DOMAIN_STATS_INVALID, nid);
			try {
				sdb.deleteAttributes(deleteRequest);
			} 
			catch (Exception e) {
				try {
					try {
						Thread.sleep(1000);
					} 
					catch (InterruptedException ie) {
						log.error("Unable to sleep... continuing");
					}
					sdb.deleteAttributes(deleteRequest);
				} 
				catch (Exception e2) {
					try {
						Thread.sleep(5000);
					} 
					catch (InterruptedException ie) {
						log.error("Unable to sleep... continuing");
					}
					
					try {
						sdb.deleteAttributes(deleteRequest);
					}
					catch(Exception e3) {
						AdWhirlUtil.logException(e, log);
					}
				}
				
				return;
			}
		}

		private void putItem(String domain, String item, List<ReplaceableAttribute> list) {
			log.debug("Putting Amazon SimpleDB item: " + item);
			PutAttributesRequest request = new PutAttributesRequest().withDomainName(domain).withItemName(item);
			request.setAttributes(list);
			try {
				sdb.putAttributes(request);
			} catch (Exception e) {
				AdWhirlUtil.logException(e, log);
				return;
			}
		}
	}
}
