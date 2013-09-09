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
import java.io.PrintWriter;
import java.net.URL;
import java.util.List;
import java.util.Random;

import javax.servlet.ServletConfig;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import net.sf.ehcache.Cache;
import net.sf.ehcache.Element;
import obj.Ration;

import org.apache.log4j.Logger;

import util.CacheUtil;

public class CustomsServlet extends HttpServlet 
{	
	private static final long serialVersionUID = 5328043677943501293L;

	static Logger log = Logger.getLogger("CustomsServlet");

	//We only want one instance of the cache and db client per instance
	private static Cache customsCache;
	private static Cache appCustomsCache;

	public void init(ServletConfig servletConfig) throws ServletException {
		customsCache = CacheUtil.getCacheCustoms();
		appCustomsCache = CacheUtil.getCacheAppCustoms();
	}

	protected void doGet(HttpServletRequest httpServletRequest, HttpServletResponse httpServletResponse) throws ServletException, IOException {
		serveCustom(httpServletRequest, httpServletResponse);
	}	

	protected void doPost(HttpServletRequest httpServletRequest, HttpServletResponse httpServletResponse) throws ServletException, IOException {
		doGet(httpServletRequest, httpServletResponse);
	}

	private void serveCustom(HttpServletRequest request, HttpServletResponse response) throws IOException {
		String nid;
		String aid = null;

		String s_appver = request.getParameter("appver");
		int appver;

		//Legacy requests did not include a version
		if(s_appver == null || s_appver.isEmpty()) {
			appver = 127;
		}
		else {
			try {
				appver = Integer.parseInt(s_appver);
			}
			catch(java.lang.NumberFormatException e) {
				s_appver = s_appver.replace(".", "");
				appver = Integer.parseInt(s_appver);
			}
		}

		aid = request.getParameter("appid");
		if(aid == null || aid.isEmpty()) {	
			response.sendError(HttpServletResponse.SC_BAD_REQUEST, "Parameter <appid> is required");
			return;
		}
		aid = aid.trim().replaceAll("%20", "");
		
		appver = cacheVersionCustom(appver);

		if(appver >= 200) {
			nid = request.getParameter("nid");	
			if(nid == null || nid.isEmpty()) {	
				response.sendError(HttpServletResponse.SC_BAD_REQUEST, "Parameter <nid> is required");
				return;
			}
		}
		else if(appver > 0) {
			nid = pickNid(aid);

			if(nid == null) {
				response.sendError(HttpServletResponse.SC_BAD_REQUEST, "Unable to determine nid from appId (" + aid + ")");
				return;
			}
		}
		else {
			response.sendError(HttpServletResponse.SC_BAD_REQUEST, "Unknown version: " + appver);
			return;
		}
		
		String metricsRequest = "http://localhost/exmet.php?nid=" + nid + "&appid=" + aid + "&type=9&appver=200";
		new URL(metricsRequest).openStream().close();
		
		String key = nid + "_" + appver;
		Element cachedCustom = customsCache.get(key);

		String jsonCustom = null;

		if(cachedCustom != null) {
			log.debug("Cache hit on \"" + key + "\"");
			jsonCustom = (String)cachedCustom.getObjectValue();

			//Replace the $aid placeholder with the real aid
			jsonCustom = jsonCustom.replaceAll("\\$aid", aid);
		}
		else {
			log.warn("Cache <customs> miss on \"" + key + "\"");
		    jsonCustom = "[]";
		}
		
		response.setCharacterEncoding("UTF-8");
		response.setContentType("application/json");
		PrintWriter out = response.getWriter();
		if(jsonCustom == null) {
			out.print("[]");
		}
		else {
			out.print(jsonCustom);
		}
		out.close();
	}

	@SuppressWarnings("unchecked")
	private String pickNid(String aid) {
		String key = aid;
		Element cachedAppCustoms = appCustomsCache.get(key);

		List<Ration> rations = null;

		if(cachedAppCustoms != null) {
			log.debug("Cache hit on \"" + key + "\"");
			rations = (List<Ration>)cachedAppCustoms.getObjectValue();
		}
		else {
			log.warn("Cache <appCustoms> miss on \"" + key + "\"");
		    return null;
		}

		if(rations == null || rations.isEmpty()) {
		    return null;
		}

		int weights = 0;
		for(Ration ration : rations) {
			weights += ration.getWeight();
		}
		
		if(weights == 0) {
			for(Ration ration : rations) {
				ration.setWeight(10);
				weights += 10;
			}
		}

		Random random = new Random();
		int r = random.nextInt(weights * 100) + 1;
		int o = 0;
		for(Ration ration : rations) {
			o += ration.getWeight() * 100;
			if(r <= o) {
				return ration.getNid();
			}
		}

		return null;
	}

	private int cacheVersionCustom(int appver) {
		// There's only one version of customs JSON right now.
		return 127;
	}
}
