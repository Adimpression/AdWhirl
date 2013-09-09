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

import java.util.List;

import com.amazonaws.services.simpledb.model.Item;

import org.apache.log4j.Logger;

import util.CacheUtil;

public class CacheCustomsLoaderThread implements Runnable {
	static Logger log = Logger.getLogger("CacheCustomsLoaderThread");

	private List<Item> customsList;
	private int threadId;
	
	public CacheCustomsLoaderThread(List<Item> customsList, int threadId) {
	    this.customsList = customsList;
	    this.threadId = threadId;
	}
	
	public void run() {
	    log.debug("CacheCustomsLoaderThread<"+ threadId + "> started");
		
		for(Item item : customsList) {
			String nid = item.getName();		
			CacheUtil.loadCustom(nid);
		}
	}
}
