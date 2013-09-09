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

package util;

import java.util.ArrayList;
import java.util.Iterator;
import java.util.List;

import net.sf.ehcache.Cache;
import net.sf.ehcache.CacheManager;
import net.sf.ehcache.Element;
import obj.CustomAd;
import obj.Extra;
import obj.Ration;

import org.apache.log4j.Logger;
import org.json.JSONException;
import org.json.JSONStringer;
import org.json.JSONWriter;

import com.amazonaws.services.simpledb.AmazonSimpleDB;
import com.amazonaws.services.simpledb.model.Attribute;
import com.amazonaws.services.simpledb.model.Item;
import com.amazonaws.services.simpledb.model.SelectRequest;
import com.amazonaws.services.simpledb.model.SelectResult;
import com.amazonaws.services.simpledb.util.SimpleDBUtils;

import thread.CacheAppCustomsLoaderThread;
import thread.CacheConfigLoaderThread;
import thread.CacheCustomsLoaderThread;
import thread.InvalidateThread;

public class CacheUtil {
	private static AmazonSimpleDB sdb;
    static Logger log = Logger.getLogger("CacheUtil");

	public static void initalize() {
		sdb = AdWhirlUtil.getSDB();	
	}
	
	public static void preload() {
		// This order is important - preloadConfigs() must come after preloadAppCustoms() has completed
		preloadAppCustoms();
		preloadCustoms();
		preloadConfigs();
		
		Thread invalidater = new Thread(new InvalidateThread());
	    invalidater.start();
	}

	private static Cache getCache(String cacheName) {
		Cache cache = CacheManager.getInstance().getCache(cacheName);
		if(cache == null) {
			log.fatal("Unable to initialize cache \"" + cacheName + "\"");
			System.exit(0);
		}
		
		return cache;
	}

	public static Cache getCacheAppCustoms() {
		return getCache("appCustoms");
	}
	
	public static Cache getCacheConfigs() {
		return getCache("json_configs");
	}
	
	public static Cache getCacheCustoms() {
		return getCache("json_customs");
	}
	
	public static Cache getCacheHits() {
		return getCache("hitsCache");
	}

	public static Cache getCacheHitsLegacy() {
		return getCache("legacyHitsCache");
	}
	
	private static void preloadConfigs() {
		log.warn("Preloading configs...");
		
		List<Thread> threads = new ArrayList<Thread>();

		int threadId = 1;
		String appsNextToken = null;
	    do {
			SelectRequest appsRequest = new SelectRequest("select `itemName()` from `" + AdWhirlUtil.DOMAIN_APPS + "` where deleted is null");
			appsRequest.setNextToken(appsNextToken);
			try {
			    SelectResult appsResult = sdb.select(appsRequest);
			    appsNextToken = appsResult.getNextToken();
			    List<Item> appsList = appsResult.getItems();
				
			    Thread thread = new Thread(new CacheConfigLoaderThread(appsList, threadId++));
			    threads.add(thread);
			    thread.start();
			} 
			catch (Exception e) {
				AdWhirlUtil.logException(e, log);
	        } 
	    }
	    while(appsNextToken != null);
	    
	    for(Thread thread : threads) {
	    	try {
				thread.join();
			} catch (InterruptedException e) {
				log.error("Caught exception while joining preload threads", e);
			}
	    }
	}

	private static void preloadCustoms() {
		log.warn("Preloading customs...");
		
		List<Thread> threads = new ArrayList<Thread>();
		
		int threadId = 1;
		String customsNextToken = null;
	    do {
			SelectRequest customsRequest = new SelectRequest("select `itemName()` from `" + AdWhirlUtil.DOMAIN_CUSTOMS + "` where deleted is null");
			customsRequest.setNextToken(customsNextToken);
			try {
			    SelectResult customsResult = sdb.select(customsRequest);
			    customsNextToken = customsResult.getNextToken();
			    List<Item> customsList = customsResult.getItems();
				 
			    Thread thread = new Thread(new CacheCustomsLoaderThread(customsList, threadId++));
			    threads.add(thread);
			    thread.start();
			}
			catch(Exception e) {
				AdWhirlUtil.logException(e, log);
		    }
	    }
	    while(customsNextToken != null);
	    
	    for(Thread thread : threads) {
	    	try {
				thread.join();
			} catch (InterruptedException e) {
				log.error("Caught exception while joining preload threads", e);
			}
	    }
	}
	
	private static void preloadAppCustoms() {
		log.warn("Preloading app customs...");
		
		List<Thread> threads = new ArrayList<Thread>();
		
		int threadId = 1;
		String appsNextToken = null;
	    do {
			SelectRequest appsRequest = new SelectRequest("select `itemName()` from `" + AdWhirlUtil.DOMAIN_APPS + "` where deleted is null");
			appsRequest.setNextToken(appsNextToken);
			try {
			    SelectResult appsResult = sdb.select(appsRequest);
			    appsNextToken = appsResult.getNextToken();
			    List<Item> appsList = appsResult.getItems();
				    
			    Thread thread = new Thread(new CacheAppCustomsLoaderThread(appsList, threadId++));
			    threads.add(thread);
			    thread.start();
			}
			catch(Exception e) {
			    AdWhirlUtil.logException(e, log);
		    }
	    }
	    while(appsNextToken != null);
	    
	    for(Thread thread : threads) {
	    	try {
				thread.join();
			} catch (InterruptedException e) {
				log.error("Caught exception while joining preload threads", e);
			}
	    }
	}

	public static void loadApp(String aid) {
		log.debug("Loading app <" + aid + "> into the cache");
		
		Cache appCustomCache = getCacheAppCustoms();

		Extra extra = null;
		List<Ration> rations = null;

		boolean loaded = false;
		while(!loaded) {
			extra = new Extra();

			//First we pull the general configuration information
			SelectRequest request = new SelectRequest("select `adsOn`, `locationOn`, `fgColor`, `bgColor`, `cycleTime`, `transition` from `" + AdWhirlUtil.DOMAIN_APPS + "` where itemName() = '" + aid + "' limit 1");
			try {
				SelectResult result = sdb.select(request);;
				List<Item> itemList = result.getItems();

				for(Item item : itemList) {
					int locationOn = 0;
					String bgColor = null;
					String fgColor = null;
					int cycleTime = 30000;
					int transition = 0;

					List<Attribute> attributeList = item.getAttributes();
					for(Attribute attribute : attributeList) {
						try {
							String attributeName = attribute.getName();
							if(attributeName.equals("adsOn")) {
								int adsOn = SimpleDBUtils.decodeZeroPaddingInt(attribute.getValue());
								extra.setAdsOn(adsOn);
							}
							else if(attributeName.equals("locationOn")) {
								locationOn = SimpleDBUtils.decodeZeroPaddingInt(attribute.getValue());
								extra.setLocationOn(locationOn);
							}
							else if(attributeName.equals("fgColor")) {
								fgColor = attribute.getValue();
								extra.setFgColor(fgColor);
							}
							else if(attributeName.equals("bgColor")) {
								bgColor = attribute.getValue();
								extra.setBgColor(bgColor);
							}
							else if(attributeName.equals("cycleTime")) {
								cycleTime = SimpleDBUtils.decodeZeroPaddingInt(attribute.getValue());
								extra.setCycleTime(cycleTime);
							}
							else if(attributeName.equals("transition")) {
								transition = SimpleDBUtils.decodeZeroPaddingInt(attribute.getValue());
								extra.setTransition(transition);
							}
							else {
								log.warn("SELECT request pulled an unknown attribute <aid: " + aid + " + >: " + attributeName + "|" + attribute.getValue());
							}
						}
						catch(NumberFormatException e) {
							log.warn("Invalid data for aid <" + aid + ">: " + e.getMessage(), e);
						}
					}

					//Now we pull the information about the app's nids
					SelectRequest networksRequest = new SelectRequest("select * from `" + AdWhirlUtil.DOMAIN_NETWORKS + "` where `aid` = '" + aid + "' and deleted is null");
					SelectResult networksResult = sdb.select(networksRequest);
					List<Item> networksList = networksResult.getItems();

					rations = new ArrayList<Ration>();

					networks_loop:
						for(Item network : networksList) {
							String nid = network.getName();

							Ration ration = new Ration(nid);

							List<Attribute> networkAttributeList = network.getAttributes();
							for(Attribute networkAttribute : networkAttributeList) {
								try {
									String networkAttributeName = networkAttribute.getName();
									if(networkAttributeName.equals("adsOn")) {
										int adsOn = SimpleDBUtils.decodeZeroPaddingInt(networkAttribute.getValue());
										if(adsOn == 0) {
											//We don't care about reporting back a network that isn't active
											continue networks_loop;
										}
									}
									else if(networkAttributeName.equals("type")) {
										ration.setType(SimpleDBUtils.decodeZeroPaddingInt(networkAttribute.getValue()));
									}
									else if(networkAttributeName.equals("weight")) {
										ration.setWeight(SimpleDBUtils.decodeZeroPaddingFloat(networkAttribute.getValue()));
										if(ration.getWeight() < 0) {
										    log.warn("Ration weight should not be less than zero <nid:" + nid + ", weight:" + ration.getWeight() + ">");
										}
									}								
									else if(networkAttributeName.equals("priority")) {
										ration.setPriority(SimpleDBUtils.decodeZeroPaddingInt(networkAttribute.getValue()));
										if(ration.getPriority() <= 0) {
										    log.warn("Ration priority should not be less than one <nid:" + nid + ", priority:" + ration.getPriority() + ">");
										}
									}								
									else if(networkAttributeName.equals("key")) {
										ration.setNetworkKey(networkAttribute.getValue());
									}
									else if(networkAttributeName.equals("aid")) {
										// We already know the aid.
									}
									else if(networkAttributeName.equals("Sdb-item-identifier")) {
										// Just means it's been edited by SDBExplorer, ignore.
									}
									else {
										log.warn("SELECT request pulled an unknown attribute <nid: " + nid + ">:" + networkAttributeName + "|" + networkAttribute.getValue());
									}
								}
								catch(NumberFormatException e) {
									log.warn("Invalid data for <nid: " + nid + ">: ", e);
								}
							}

							if(ration.getType() == AdWhirlUtil.NETWORKS.CUSTOM.ordinal()) {
								String key = aid;
								Element cachedAppCustom = appCustomCache.get(key);

								if(cachedAppCustom != null) {
									double weight = ration.getWeight();

									@SuppressWarnings("unchecked")
									List<Ration> customRations = (List<Ration>)cachedAppCustom.getObjectValue();
									for(Ration customRation : customRations) {
										customRation.setPriority(ration.getPriority());
										
										double customWeight = AdWhirlUtil.round(customRation.getWeight() * weight / 100, 2);
										customRation.setWeight(customWeight);
										
										rations.add(customRation);
									}
								}
							}
							else {
								rations.add(ration);
							}
						}	
				} 

				loaded = true;
			}
			catch (Exception e) {
			    AdWhirlUtil.logException(e, log);
			}       
		}

		try {
			genJsonConfigs(getCacheConfigs(), aid, extra, rations);
		} 
		catch (JSONException e) {
		    log.error("Error creating jsonConfig for aid <"+ aid +">: " + e.getMessage(), e);
		    for(Ration ration : rations) {
			log.warn(ration.toString());
		    }
		}
	}


	private static void genJsonConfigs(Cache cache, String aid, Extra extra, List<Ration> rations) throws JSONException {
		cache.put(new Element(aid + "_250", genJsonConfigV250(extra, rations)));
		
		Iterator<Ration> i = rations.iterator(); 
		while(i.hasNext()) {
			Ration ration = i.next();

			// Decimal weights only supported in >2.5.0
			ration.setWeight((int)ration.getWeight());
		}

		cache.put(new Element(aid + "_200", genJsonConfigV200(extra, rations)));

		i = rations.iterator();
		while(i.hasNext()) {
			Ration ration = i.next();

			// Types over 16 only supported in >2.0
			if(ration.getType() > 16) {
				i.remove();
			}
		}

		cache.put(new Element(aid + "_127", genJsonConfigV127(extra, rations)));
		cache.put(new Element(aid + "_103", genJsonConfigV103(extra, rations)));
	}

	private static String genJsonConfigV250(Extra extra, List<Ration> rations) throws JSONException {
		JSONWriter jsonWriter = new JSONStringer();

		if(extra.getAdsOn() == 0) {
			return jsonWriter.object()
			.key("rations")
			.array()
			.endArray()
			.endObject()
			.toString();
		}

		jsonWriter = jsonWriter.object()
		.key("extra")
		.object()
		.key("location_on")
		.value(extra.getLocationOn())
		.key("background_color_rgb")
		.object()
		.key("red")
		.value(extra.getBg_red())
		.key("green")
		.value(extra.getBg_green())
		.key("blue")
		.value(extra.getBg_blue())
		.key("alpha")
		.value(extra.getBg_alpha())
		.endObject()
		.key("text_color_rgb")
		.object()
		.key("red")
		.value(extra.getFg_red())
		.key("green")
		.value(extra.getFg_green())
		.key("blue")
		.value(extra.getFg_blue())
		.key("alpha")
		.value(extra.getFg_alpha())
		.endObject()
		.key("cycle_time")
		.value(extra.getCycleTime())
		.key("transition")
		.value(extra.getTransition())
		.endObject();

		jsonWriter = jsonWriter.key("rations")
		.array();

		for(Ration ration : rations) {
			jsonWriter = jsonWriter.object()
			.key("nid")
			.value(ration.getNid())
			.key("type")
			.value(ration.getType())
			.key("nname")
			.value(ration.getNName())
			.key("weight")
			.value(ration.getWeight())
			.key("priority")
			.value(ration.getPriority())
			.key("key");

			if(ration.getType() == AdWhirlUtil.NETWORKS.VIDEOEGG.ordinal()) {
				String[] temp = ration.getNetworkKey().split(AdWhirlUtil.KEY_SPLIT);

				jsonWriter = jsonWriter.object()
				.key("publisher")
				.value(temp[0])
				.key("area")
				.value(temp[1])
				.endObject();
			}
			else if(ration.getType() == AdWhirlUtil.NETWORKS.JUMPTAP.ordinal()) {
				String[] temp = ration.getNetworkKey().split(AdWhirlUtil.KEY_SPLIT);

				if(temp.length == 3) {
					jsonWriter = jsonWriter.object()
					.key("publisherID")
					.value(temp[0])
					.key("siteID")
					.value(temp[1])
					.key("spotID")
					.value(temp[2])
					.endObject();
				}
                                else if(temp.length == 2) {
					jsonWriter = jsonWriter.object()
					.key("publisherID")
					.value(temp[0])
					.key("siteID")
					.value(temp[1])
					.endObject();
                                }
				else {
					jsonWriter = jsonWriter.object()
					.key("publisherID")
					.value(temp[0])
					.endObject();
				}
			}
			else if(ration.getType() == AdWhirlUtil.NETWORKS.QUATTRO.ordinal()) {
				String[] temp = ration.getNetworkKey().split(AdWhirlUtil.KEY_SPLIT);

				jsonWriter = jsonWriter.object()
				.key("siteID")
				.value(temp[0])
				.key("publisherID")
				.value(temp[1])
				.endObject();
			}
			else if(ration.getType() == AdWhirlUtil.NETWORKS.MOBCLIX.ordinal()) {
				String[] temp = ration.getNetworkKey().split(AdWhirlUtil.KEY_SPLIT);

				if(temp.length == 2) {
					jsonWriter = jsonWriter.object()
					.key("appID")
					.value(temp[0])
					.key("adCode")
					.value(temp[1])
					.endObject();
				}
				else {
					jsonWriter = jsonWriter.object()
					.key("appID")
					.value(temp[0])
					.endObject();
				}
			}
			else {
				jsonWriter = jsonWriter.value(ration.getNetworkKey());
			}

			jsonWriter = jsonWriter.endObject();
		}

		jsonWriter = jsonWriter.endArray();

		return jsonWriter.endObject().toString();
	}

	private static String genJsonConfigV200(Extra extra, List<Ration> rations) throws JSONException {
		JSONWriter jsonWriter = new JSONStringer();

		if(extra.getAdsOn() == 0) {
			return jsonWriter.object()
			.key("rations")
			.array()
			.endArray()
			.endObject()
			.toString();
		}

		jsonWriter = jsonWriter.object()
		.key("extra")
		.object()
		.key("location_on")
		.value(extra.getLocationOn())
		.key("background_color_rgb")
		.object()
		.key("red")
		.value(extra.getBg_red())
		.key("green")
		.value(extra.getBg_green())
		.key("blue")
		.value(extra.getBg_blue())
		.key("alpha")
		.value(extra.getBg_alpha())
		.endObject()
		.key("text_color_rgb")
		.object()
		.key("red")
		.value(extra.getFg_red())
		.key("green")
		.value(extra.getFg_green())
		.key("blue")
		.value(extra.getFg_blue())
		.key("alpha")
		.value(extra.getFg_alpha())
		.endObject()
		.key("cycle_time")
		.value(extra.getCycleTime())
		.key("transition")
		.value(extra.getTransition())
		.endObject();

		jsonWriter = jsonWriter.key("rations")
		.array();

		for(Ration ration : rations) {
			jsonWriter = jsonWriter.object()
			.key("nid")
			.value(ration.getNid())
			.key("type")
			.value(ration.getType())
			.key("nname")
			.value(ration.getNName())
			.key("weight")
			.value(ration.getWeight())
			.key("priority")
			.value(ration.getPriority())
			.key("key");

			if(ration.getType() == AdWhirlUtil.NETWORKS.VIDEOEGG.ordinal()) {
				String[] temp = ration.getNetworkKey().split(AdWhirlUtil.KEY_SPLIT);

				jsonWriter = jsonWriter.object()
				.key("publisher")
				.value(temp[0])
				.key("area")
				.value(temp[1])
				.endObject();
			}
			else if(ration.getType() == AdWhirlUtil.NETWORKS.JUMPTAP.ordinal()) {
				String[] temp = ration.getNetworkKey().split(AdWhirlUtil.KEY_SPLIT);

                                jsonWriter = jsonWriter.value(temp[0]);
			}
			else if(ration.getType() == AdWhirlUtil.NETWORKS.QUATTRO.ordinal()) {
				String[] temp = ration.getNetworkKey().split(AdWhirlUtil.KEY_SPLIT);

				jsonWriter = jsonWriter.object()
				.key("siteID")
				.value(temp[0])
				.key("publisherID")
				.value(temp[1])
				.endObject();
			}
			else if(ration.getType() == AdWhirlUtil.NETWORKS.MOBCLIX.ordinal()) {
				String[] temp = ration.getNetworkKey().split(AdWhirlUtil.KEY_SPLIT);

				if(temp.length == 2) {
					jsonWriter = jsonWriter.object()
					.key("appID")
					.value(temp[0])
					.key("adCode")
					.value(temp[1])
					.endObject();
				}
				else {
					jsonWriter = jsonWriter.object()
					.key("appID")
					.value(temp[0])
					.endObject();
				}
			}
			else {
				jsonWriter = jsonWriter.value(ration.getNetworkKey());
			}

			jsonWriter = jsonWriter.endObject();
		}

		jsonWriter = jsonWriter.endArray();

		return jsonWriter.endObject().toString();
	}

	//Legacy support
	private static String genJsonConfigV127(Extra extra, List<Ration> rations) throws JSONException {
		JSONWriter jsonWriter = new JSONStringer();

		jsonWriter = jsonWriter.array();

		if(extra.getAdsOn() == 0) {
			jsonWriter = jsonWriter.object()
			.key("empty_ration")
			.value(100)
			.endObject()
			.object()
			.key("empty_ration")
			.value("empty_ration")
			.endObject()
			.object()
			.key("empty_ration")
			.value(1)
			.endObject();
		}
		else {
			jsonWriter = jsonWriter.object();
			double customWeight = 0;
			for(Ration ration : rations) {
				if(ration.getNName().equals("custom")) {
					customWeight += ration.getWeight();
					continue;
				}

				// Takes care of MdotM legacy support
				String rationName;
				if(ration.getType() == AdWhirlUtil.NETWORKS.MDOTM.ordinal()) {
					rationName = "adrollo";
				}
				else {
					rationName = ration.getNName();
				}

				jsonWriter = jsonWriter.key(rationName + "_ration")
				.value(ration.getWeight());

			}

			if(customWeight != 0) {
				jsonWriter = jsonWriter.key("custom_ration")
				.value(customWeight);
			}

			jsonWriter = jsonWriter.endObject();

			jsonWriter = jsonWriter.object();
			for(Ration ration : rations) {
				if(ration.getNName().equals("custom")) {
					continue;
				}
				else if(ration.getType() == AdWhirlUtil.NETWORKS.VIDEOEGG.ordinal()) {
					String[] temp = ration.getNetworkKey().split(AdWhirlUtil.KEY_SPLIT);

					jsonWriter = jsonWriter.key(ration.getNName() + "_key")
					.object()
					.key("publisher")
					.value(temp[0])
					.key("area")
					.value(temp[1])
					.endObject();
				}
                                else if(ration.getType() == AdWhirlUtil.NETWORKS.JUMPTAP.ordinal()) {
                                  String[] temp = ration.getNetworkKey().split(AdWhirlUtil.KEY_SPLIT);

                                  jsonWriter = jsonWriter.key(ration.getNName() + "_key")
                                      .value(temp[0]);
                                }
				else if(ration.getType() == AdWhirlUtil.NETWORKS.QUATTRO.ordinal()) {
					String[] temp = ration.getNetworkKey().split(AdWhirlUtil.KEY_SPLIT);

					jsonWriter = jsonWriter.key(ration.getNName() + "_key")
					.object()
					.key("siteID")
					.value(temp[0])
					.key("publisherID")
					.value(temp[1])
					.endObject();
				}
				else if(ration.getType() == AdWhirlUtil.NETWORKS.MOBCLIX.ordinal()) {
					String[] temp = ration.getNetworkKey().split(AdWhirlUtil.KEY_SPLIT);

					if(temp.length == 2) {
						jsonWriter = jsonWriter.key(ration.getNName() + "_key")
						.object()
						.key("appID")
						.value(temp[0])
						.key("adCode")
						.value(temp[1])
						.endObject();
					}
					else {
						jsonWriter = jsonWriter.key(ration.getNName() + "_key")
						.object()
						.key("appID")
						.value(temp[0])
						.endObject();
					}
				}
				else {

					// Takes care of MdotM legacy support
					String rationName;
					if(ration.getType() == AdWhirlUtil.NETWORKS.MDOTM.ordinal()) {
						rationName = "adrollo";
					}
					else {
						rationName = ration.getNName();
					}

					jsonWriter = jsonWriter.key(rationName + "_key")
					.value(ration.getNetworkKey());
				}
			}

			if(customWeight != 0) {
				jsonWriter = jsonWriter.key("dontcare_key")
				.value(customWeight);
			}
			jsonWriter = jsonWriter.endObject();

			jsonWriter = jsonWriter.object();
			int customPriority = Integer.MAX_VALUE;
			for(Ration ration : rations) {
				if(ration.getNName().equals("custom")) {
					if(customPriority > ration.getPriority()) {
						customPriority = ration.getPriority();
					}
					continue;
				}

				// Takes care of MdotM legacy support
				String rationName;
				if(ration.getType() == AdWhirlUtil.NETWORKS.MDOTM.ordinal()) {
					rationName = "adwhirl_12";
				}
				else {
					rationName = ration.getNName();
				}

				jsonWriter = jsonWriter.key(rationName + "_priority")
				.value(ration.getPriority());
			}
			if(customWeight != 0) {
				jsonWriter = jsonWriter.key("custom_priority")
				.value(customPriority);
			}
			jsonWriter = jsonWriter.endObject();
		}

		jsonWriter = jsonWriter.object()
		.key("background_color_rgb")
		.object()
		.key("red")
		.value(extra.getBg_red())
		.key("green")
		.value(extra.getBg_green())
		.key("blue")
		.value(extra.getBg_blue())
		.key("alpha")
		.value(extra.getBg_alpha())
		.endObject()
		.key("text_color_rgb")
		.object()
		.key("red")
		.value(extra.getFg_red())
		.key("green")
		.value(extra.getFg_green())
		.key("blue")
		.value(extra.getFg_blue())
		.key("alpha")
		.value(extra.getFg_alpha())
		.endObject()
		.key("refresh_interval")
		.value(extra.getCycleTime())
		.key("location_on")
		.value(extra.getLocationOn())
		.key("banner_animation_type")
		.value(extra.getTransition())
		.key("fullscreen_wait_interval")
		.value(extra.getFullscreen_wait_interval())
		.key("fullscreen_max_ads")
		.value(extra.getFullscreen_max_ads())
		.key("metrics_url")
		.value(extra.getMetrics_url())
		.key("metrics_flag")
		.value(extra.getMetrics_flag())
		.endObject();

		return jsonWriter.endArray().toString();
	}

	//Legacy support
	private static String genJsonConfigV103(Extra extra, List<Ration> rations) throws JSONException {
		JSONWriter jsonWriter = new JSONStringer();

		jsonWriter = jsonWriter.array();

		if(extra.getAdsOn() == 0) {
			jsonWriter = jsonWriter.object()
			.key("empty_ration")
			.value(100)
			.endObject()
			.object()
			.key("empty_ration")
			.value("empty_ration")
			.endObject()
			.object()
			.key("empty_ration")
			.value(1)
			.endObject();
		}
		else {
			jsonWriter = jsonWriter.object();
			double customWeight = 0;
			for(Ration ration : rations) {
				if(ration.getNName().equals("custom")) {
					customWeight += ration.getWeight();
					continue;
				}

				// Takes care of MdotM legacy support
				String rationName;
				if(ration.getType() == AdWhirlUtil.NETWORKS.MDOTM.ordinal()) {
					rationName = "adrollo";
				}
				else {
					rationName = ration.getNName();
				}

				jsonWriter = jsonWriter.key(rationName + "_ration")
				.value(ration.getWeight());

			}
			if(customWeight != 0) {
				jsonWriter = jsonWriter.key("custom_ration")
				.value(customWeight);
			}
			jsonWriter = jsonWriter.endObject();

			jsonWriter = jsonWriter.object();
			for(Ration ration : rations) {			
				if(ration.getNName().equals("custom")) {
					continue;
				}
				else if(ration.getType() == AdWhirlUtil.NETWORKS.VIDEOEGG.ordinal()) {
					String[] temp = ration.getNetworkKey().split(AdWhirlUtil.KEY_SPLIT);

					jsonWriter = jsonWriter.key(ration.getNName() + "_key")
					.object()
					.key("publisher")
					.value(temp[0])
					.key("area")
					.value(temp[1])
					.endObject();
				}
                                else if(ration.getType() == AdWhirlUtil.NETWORKS.JUMPTAP.ordinal()) {
                                  String[] temp = ration.getNetworkKey().split(AdWhirlUtil.KEY_SPLIT);

                                  jsonWriter = jsonWriter.key(ration.getNName() + "_key")
                                      .value(temp[0]);
                                }
				else if(ration.getType() == AdWhirlUtil.NETWORKS.QUATTRO.ordinal()) {
					String[] temp = ration.getNetworkKey().split(AdWhirlUtil.KEY_SPLIT);

					if(temp.length == 2) {
						jsonWriter = jsonWriter.key(ration.getNName() + "_key")
						.object()
						.key("siteID")
						.value(temp[0])
						.key("publisherID")
						.value(temp[1])
						.endObject();
					}
					else {

						jsonWriter = jsonWriter.object()
						.key("appID")
						.value(temp[0])
						.endObject();
					}

				}
				else {
					// Takes care of MdotM legacy support
					String rationName;
					if(ration.getType() == AdWhirlUtil.NETWORKS.MDOTM.ordinal()) {
						rationName = "adrollo";
					}
					else {
						rationName = ration.getNName();
					}

					jsonWriter = jsonWriter.key(rationName + "_key")
					.value(ration.getNetworkKey());
				}
			}

			if(customWeight != 0) {
				jsonWriter = jsonWriter.key("dontcare_key")
				.value(customWeight);
			}
			jsonWriter = jsonWriter.endObject();

			jsonWriter = jsonWriter.object();
			int customPriority = Integer.MAX_VALUE;
			for(Ration ration : rations) {
				if(ration.getNName().equals("custom")) {
					if(customPriority > ration.getPriority()) {
						customPriority = ration.getPriority();
					}
					continue;
				}

				// Takes care of MdotM legacy support
				String rationName;
				if(ration.getType() == AdWhirlUtil.NETWORKS.MDOTM.ordinal()) {
					rationName = "adrollo";
				}
				else {
					rationName = ration.getNName();
				}

				jsonWriter = jsonWriter.key(rationName + "_priority")
				.value(ration.getPriority());
			}
			if(customWeight != 0) {
				jsonWriter = jsonWriter.key("custom_priority")
				.value(customPriority);
			}
			jsonWriter = jsonWriter.endObject();
		}

		jsonWriter = jsonWriter.object()
		.key("background_color_rgb")
		.object()
		.key("red")
		.value(extra.getBg_red())
		.key("green")
		.value(extra.getBg_green())
		.key("blue")
		.value(extra.getBg_blue())
		.key("alpha")
		.value(extra.getBg_alpha())
		.endObject()
		.key("text_color_rgb")
		.object()
		.key("red")
		.value(extra.getFg_red())
		.key("green")
		.value(extra.getFg_green())
		.key("blue")
		.value(extra.getFg_blue())
		.key("alpha")
		.value(extra.getFg_alpha())
		.endObject()
		.key("refresh_interval")
		.value(extra.getCycleTime())
		.key("location_on")
		.value(extra.getLocationOn())
		.key("banner_animation_type")
		.value(extra.getTransition())
		.key("fullscreen_wait_interval")
		.value(extra.getFullscreen_wait_interval())
		.key("fullscreen_max_ads")
		.value(extra.getFullscreen_max_ads())
		.key("metrics_url")
		.value(extra.getMetrics_url())
		.key("metrics_flag")
		.value(extra.getMetrics_flag())
		.endObject();

		return jsonWriter.endArray().toString();
	}

	public static void loadCustom(String nid) {
		log.debug("Loading custom <" + nid + "> into the cache");

		CustomAd customAd = null;

		boolean loaded = false;
		while(!loaded) {
			//Custom (house) ad select query
			SelectRequest customRequest = new SelectRequest("select * from `" + AdWhirlUtil.DOMAIN_CUSTOMS + "` where itemName() = '" + nid + "' limit 1");
			try {
				SelectResult customResult = sdb.select(customRequest);
				List<Item> customList = customResult.getItems();

				for(Item cusItem : customList) {	
					customAd = new CustomAd(cusItem.getName());

					List<Attribute> cusAttributeList = cusItem.getAttributes();
					for(Attribute attribute : cusAttributeList) {
						try {
							String attributeName = attribute.getName();		
							if(attributeName.equals("type")) {
								customAd.setType(SimpleDBUtils.decodeZeroPaddingInt(attribute.getValue()));
							}
							else if(attributeName.equals("aid")) {
							    // Legacy field, shouldn't be used
							}
							else if(attributeName.equals("imageLink")) {
								customAd.setImageLink(attribute.getValue());
							}					
							else if(attributeName.equals("link")) {
								customAd.setLink(attribute.getValue());
							}				
							else if(attributeName.equals("description")) {
								customAd.setDescription(attribute.getValue());
							}					
							else if(attributeName.equals("name")) {
								customAd.setName(attribute.getValue());
							}
							else if(attributeName.equals("linkType")) {
								customAd.setLinkType(attribute.getValue());
							}
							else if(attributeName.equals("launchType")) {
								customAd.setLaunchType(attribute.getValue());
							}
							else if(attributeName.equals("uid")) {
							    // We don't care who this belongs to
							}
							else if(attributeName.equals("deleted")) {
							    // We don't care when it was deleted
							}
							else if(attributeName.equals("Sdb-item-identifier")) {
								// Just means it's been edited by SDBExplorer, ignore.
							}
							else {
								log.warn("SELECT request pulled an unknown attribute <cid: " + nid + ">: " + attributeName + "|" + attribute.getValue());
							}
						}
						catch(NumberFormatException e) {
							log.warn("Invalid data for custom <" + nid + ">: " + e.getMessage(), e);
						}
					}
				}	

				loaded = true;
			}
			catch(Exception e) {
			    AdWhirlUtil.logException(e, log);
			}	
		}

		try {
			genJsonCustoms(getCacheCustoms(), nid, customAd);
		} catch (JSONException e) {
			log.error("Error creating jsonCustom: " + e.getMessage());
			return;
		}
	}

	private static void genJsonCustoms(Cache cache, String nid, CustomAd customAd) throws JSONException {
		if(customAd != null) {
			cache.put(new Element(nid + "_127", genJsonCustomV127(customAd)));	
		}
	}

	private static String genJsonCustomV127(CustomAd customAd) throws JSONException {
		JSONWriter jsonWriter = new JSONStringer();

		int launch_type;

		String s_link_type = customAd.getLinkType();
		int link_type = -1;
                try {
                  link_type = Integer.parseInt(s_link_type);
                }
                catch(NumberFormatException e) {
                  link_type = 1;
                }

		if(customAd.getLaunchType().equals("")) {
			if(link_type == 2) {
				launch_type = 1;
			}
			else {
				launch_type = 2;
			}
		}
		else {
			String s_launch_type = customAd.getLaunchType();
			launch_type = Integer.parseInt(s_launch_type);
		}

		jsonWriter = jsonWriter.object()
		.key("img_url")
		.value(customAd.getImageLink())
		.key("redirect_url")
		.value(customAd.getLink())
		.key("metrics_url")
		.value("http://" + AdWhirlUtil.SERVER + "/exclick.php?nid=" + customAd.getNid() + "&appid=$aid&type=9&appver=200")
		.key("metrics_url2")
		.value("")
		.key("ad_type")
		.value(customAd.getType())
		.key("ad_text")
		.value(customAd.getDescription())
		.key("link_type")
		.value(link_type)
		.key("launch_type")
		.value(launch_type)
		.key("subtext")
		.value("")
		.key("webview_animation_type")
		.value(4)
		.endObject();

		return jsonWriter.toString();
	}


	public static void loadAppCustom(String aid) {
		log.debug("Loading app customs for <aid:" + aid + "> into the cache");

		List<Ration> rations = null;

		boolean loaded = false;
		while(!loaded) {
			rations = new ArrayList<Ration>();

			try {
				String nextToken = null;
				do {
					// TODO - remove "is null" from where
					SelectRequest request = new SelectRequest("select `weight`, `cid` from `" + AdWhirlUtil.DOMAIN_APP_CUSTOMS + "` where `aid` = '" + aid + "' and `deleted` is null");
					request.setNextToken(nextToken);

					SelectResult result = sdb.select(request);
					nextToken = result.getNextToken();
					List<Item> list = result.getItems();

					for(Item item : list) {	
						Ration ration = new Ration();

						String acid = item.getName();

						List<Attribute> attributeList = item.getAttributes();
						for(Attribute attribute : attributeList) {
							String attributeName = null;

							try {
								attributeName = attribute.getName();		
								if(attributeName.equals("weight")) {
									double weight = SimpleDBUtils.decodeZeroPaddingFloat(attribute.getValue());
									ration.setWeight(weight);
								}	
								else if(attributeName.equals("cid")) {
									String cid = attribute.getValue();
									ration.setNid(cid);
								}
							}
							catch(NumberFormatException e) {
								log.error("Invalid data for app custom <acid: " + acid + ">: " + attributeName, e);
							}
						}

						ration.setNetworkKey("__CUSTOM__");
						ration.setType(AdWhirlUtil.NETWORKS.CUSTOM.ordinal());

						rations.add(ration);
					}		
				}
				while(nextToken != null);
				
				loaded = true;
			}
			catch (Exception e) {
				AdWhirlUtil.logException(e, log);
			}	
		}

		Cache cache = getCacheAppCustoms();
		cache.put(new Element(aid, rations));	
	}
}
