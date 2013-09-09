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
import java.util.Date;
import java.util.Iterator;
import java.util.List;
import java.util.UUID;

import org.apache.log4j.Logger;

import com.amazonaws.services.simpledb.AmazonSimpleDB;
import com.amazonaws.services.simpledb.model.Item;
import com.amazonaws.services.simpledb.model.PutAttributesRequest;
import com.amazonaws.services.simpledb.model.ReplaceableAttribute;
import com.amazonaws.services.simpledb.model.SelectRequest;
import com.amazonaws.services.simpledb.model.SelectResult;

import net.sf.ehcache.Element;

import obj.HitObject;
import obj.HitObjectKey;

import servlet.MetricsServlet;

import util.AdWhirlUtil;

public class RollupThread implements Runnable {
	static Logger log = Logger.getLogger("RollupThread");
	
	private static AmazonSimpleDB sdb;
	
	public void run() {
		log.info("RollupThread started");
		
		sdb = AdWhirlUtil.getSDB();
		
		while(true) {
			processHitsCache();
			processLegacyHitsCache();
			
			//TODO: change this
			try {
				Thread.sleep(1 * 60000);
			} catch (InterruptedException e) {
				log.error("Unable to sleep... continuing");
			}
		}
	}

	@SuppressWarnings("unchecked")
	private void processHitsCache() {
		log.debug("Processing hitsCache");
		
		int impressions = 0;
		
		Iterator<HitObjectKey> it = (Iterator<HitObjectKey>) MetricsServlet.hitsCache.getKeys().iterator();
		while(it.hasNext()) {
			HitObjectKey key = it.next();
			
			Element element = MetricsServlet.hitsCache.get(key);
			if(element != null) {
				String nid = key.nid;
				String aid = key.aid;
				
				HitObject ho = (HitObject)element.getObjectValue();
				impressions += ho.impressions.get();
				updateSimpleDB(nid, aid, ho);
			}
		}
		
		log.debug("XXXXX Pushed impressions: " + impressions);
	}
	
	@SuppressWarnings("unchecked")
	private void processLegacyHitsCache() {
		log.debug("Processing legacyHitsCache");
		
		int impressions = 0;
		
		Iterator<String> it = MetricsServlet.legacyHitsCache.getKeys().iterator();
		while(it.hasNext()) {
			String key = it.next();
			
			int index = key.indexOf("_");
			if(index == -1) {
				log.error("Invalid key: " + key);
				continue;
			}
		
			String type = key.substring(index+1);
			String aid = key.substring(0, index);
			
			if(type == null || aid == null) {
				log.error("Invalid key: " + key);
				continue;
			}
			
			String nid = null;
			SelectRequest request = new SelectRequest("select itemName() from `" + AdWhirlUtil.DOMAIN_NETWORKS + "` where `aid` = '" + aid + "' and `type` = '" + type + "' limit 1");
			try {
				SelectResult result = sdb.select(request);
				List<Item> list = result.getItems();

				for(Item item : list) {		
					nid = item.getName();
				}
			}
			catch(Exception e) {
				log.error("Unable to process legacy hit, aid=\"" + aid + "\" and type=\"" + type + "\", message: " + e.getMessage());
				AdWhirlUtil.logException(e, log);
				continue;
			}
			
			Element element = MetricsServlet.legacyHitsCache.get(key);
			if(element != null) {
				HitObject ho = (HitObject)element.getObjectValue();
				impressions += ho.impressions.get();
				updateSimpleDB(nid, aid, ho);
			}
			else {
				continue;
			}
		}
		
		log.debug("XXXXX Pushed legacy impressions: " + impressions);
	}
	
	private void updateSimpleDB(String nid, String aid, HitObject ho) {
		log.debug("Updating nid=\"" + nid + "\", aid=\"" + aid + "\"");
		
		if(nid == null || aid == null || ho == null) {
			return;
		}
		
		Date date = new Date();
		
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd");
		String dateTime = sdf.format(date);
		SimpleDateFormat sdfDetail = new SimpleDateFormat("yyyy-MM-dd, HH:mm:ss");
		String dateTimeDetail = sdfDetail.format(date);
		
		String impressions = String.valueOf(ho.impressions);
		String clicks = String.valueOf(ho.clicks);
		String type = String.valueOf(ho.type);

		int i_impressions = Integer.parseInt(impressions);
		int i_clicks = Integer.parseInt(clicks);
		
		if(i_impressions == 0 && i_clicks == 0) {
			return;
		}
		
		ho.impressions.addAndGet(-1 * i_impressions);
		ho.clicks.addAndGet(-1 * i_clicks);
		
		List<ReplaceableAttribute> list = new ArrayList<ReplaceableAttribute>();

		list.add(new ReplaceableAttribute().withName("nid").withValue(nid).withReplace(true));
		list.add(new ReplaceableAttribute().withName("aid").withValue(aid).withReplace(true));
		list.add(new ReplaceableAttribute().withName("type").withValue(type).withReplace(true));
		list.add(new ReplaceableAttribute().withName("impressions").withValue(impressions).withReplace(true));
		list.add(new ReplaceableAttribute().withName("clicks").withValue(clicks).withReplace(true));
		list.add(new ReplaceableAttribute().withName("dateTime").withValue(dateTime).withReplace(true));		
		putItem(AdWhirlUtil.DOMAIN_STATS_TEMP, UUID.randomUUID().toString().replace("-", ""), list);
		
		List<ReplaceableAttribute> list2 = new ArrayList<ReplaceableAttribute>();
		list2.add(new ReplaceableAttribute().withName("aid").withValue(aid).withReplace(false));
		list2.add(new ReplaceableAttribute().withName("dateTime").withValue(dateTimeDetail).withReplace(true));
		putItem(AdWhirlUtil.DOMAIN_STATS_INVALID, nid, list2);
	}

	private void putItem(String domain, String item, List<ReplaceableAttribute> list) {
		log.debug("Putting Amazon SimpleDB item: " + item);
		PutAttributesRequest request = new PutAttributesRequest().withDomainName(domain).withItemName(item);
		request.setAttributes(list);
		try {
			sdb.putAttributes(request);
		} catch (Exception e) {
			log.error("Unable to create item \"" + item + "\": " + e.getMessage());
			AdWhirlUtil.logException(e, log);
		}
	}
}
