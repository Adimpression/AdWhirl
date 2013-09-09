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

package servlet;

import java.io.IOException;

import javax.servlet.ServletConfig;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.apache.log4j.Logger;

import thread.RollupThread;
import util.AdWhirlUtil;
import util.CacheUtil;
import obj.HitObject;
import obj.HitObjectKey;

import net.sf.ehcache.Cache;
import net.sf.ehcache.Element;

public class MetricsServlet extends HttpServlet 
{
    private static final long serialVersionUID = 6570803342253639443L;

    static Logger log = Logger.getLogger("MetricsServlet");
	
    public static Cache hitsCache;
    public static Cache legacyHitsCache;
    public static Thread rollupThread;
	
    public void init(ServletConfig servletConfig) throws ServletException {
	hitsCache = CacheUtil.getCacheHits();
	legacyHitsCache = CacheUtil.getCacheHitsLegacy();

	rollupThread = new Thread(new RollupThread());
	rollupThread.start();	
	
	Runtime.getRuntime().addShutdownHook(new Thread() {
		public void run() {
		    log.fatal("Attempting to flush cache before exiting...");
		    rollupThread.interrupt();
		    log.fatal("Sleeping 10 seconds and quitting.");

		    try {
			Thread.sleep(10 * 1000);
		    } catch (InterruptedException e) {
			log.fatal("Unable to sleep.");
		    }
		}
	});
	
	log.info("Servlet initialized completed");
    }
	
    protected void doGet(HttpServletRequest httpServletRequest, HttpServletResponse httpServletResponse) throws ServletException, IOException {
	doMetrics(httpServletRequest, httpServletResponse);
    }	
	
    protected void doPost(HttpServletRequest httpServletRequest, HttpServletResponse httpServletResponse) throws ServletException, IOException {
	doGet(httpServletRequest, httpServletResponse);
    }
	
    private void doMetrics(HttpServletRequest request, HttpServletResponse response) throws IOException {	
	int i_hitType;
		
	String s_appver = request.getParameter("appver");		
	int ver;
	if(s_appver == null || s_appver.isEmpty()) {
	    ver = 127;
	}
	else {
	    ver = Integer.parseInt(s_appver);		
	}

	String requestURI = request.getRequestURI();
	if(requestURI.contains("exclick")) {
	    i_hitType = AdWhirlUtil.HITTYPE.CLICK.ordinal();
	}
	else if(requestURI.contains("exmet")) {
	    i_hitType = AdWhirlUtil.HITTYPE.IMPRESSION.ordinal();
	}
	else {
	    log.warn("Unable to determine hitType from request: " + requestURI);
	    response.sendError(HttpServletResponse.SC_BAD_REQUEST, "Unknown request URI");
	    return;
	}

	String aid = request.getParameter("appid");
	if(aid == null || aid.isEmpty()) {
	    response.sendError(HttpServletResponse.SC_BAD_REQUEST, "Parameter <appid> is required");
	    return;
	}	
	aid = aid.trim();
		
	Element element = null;

	HitObject ho = null;

	Cache cache = null;
	Object key = null;

	int type;
		
	//Version 2.* uses NID and TYPE to store metrics, whereas version 1.* uses a composite key of AID and TYPE
	if(ver >= 200) {
	    String s_type = request.getParameter("type");
	    if(s_type == null || s_type.isEmpty()) {
		response.sendError(HttpServletResponse.SC_BAD_REQUEST, "Parameter <type> is required");
		return;
	    }			
	    type = Integer.parseInt(s_type);

	    //The NID variable here is a UUID identifying the network specific to the application
	    String nid = request.getParameter("nid");
	    if(nid == null || nid.isEmpty()) {
		response.sendError(HttpServletResponse.SC_BAD_REQUEST, "Parameter <nid> is required");
		return;
	    }			

	    cache = hitsCache;
	    
	    // Note: The key for the normal hits cache is a HitObjectKey
	    key = new HitObjectKey(nid, aid);
	}
	else {
	    //Yes, the legacy type variable is also NID. This NID is an int representing the network type 
	    String s_type = request.getParameter("nid");
	    if(s_type == null || s_type.isEmpty()) {
		response.sendError(HttpServletResponse.SC_BAD_REQUEST, "Parameter <nid> is required");
		return;
	    }	
	    type = Integer.parseInt(s_type);

	    cache = legacyHitsCache;
	    
	    // Note: The key for the legacy hits cache is a string
	    key = aid + "_" + type;
	}
		
	log.debug("Cache key for request: " + key);
		
	element = cache.get(key);
			
	if(element == null) {
	    //TODO: Depending on traffic, this may need a semaphore or be generated beforehand
	    ho = new HitObject(type);
	    element = new Element(key, ho);
	    cache.put(element);
	}
	else {
	    //We don't need to put again, since the reference didn't change
	    ho = (HitObject)element.getObjectValue();
	}
			
	//Atomically record the hit
	if(i_hitType == AdWhirlUtil.HITTYPE.IMPRESSION.ordinal()) {
	    ho.impressions.incrementAndGet();
	}
	else {
	    ho.clicks.incrementAndGet();
	}

	response.setContentType("text/html");
	response.setContentLength(0);
	response.setStatus(HttpServletResponse.SC_OK);
    }
}
