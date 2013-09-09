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

import javax.servlet.ServletConfig;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import net.sf.ehcache.Cache;
import net.sf.ehcache.Element;

import org.apache.log4j.Logger;

import util.CacheUtil;

public class ConfigServlet extends HttpServlet {
    private static final long serialVersionUID = 7298139537865054861L;
    
    static Logger log = Logger.getLogger("ConfigServlet");

	//We only want one instance of the cache and db client per instance
	private static Cache configsCache;
	
	public void init(ServletConfig servletConfig) throws ServletException {
		configsCache = CacheUtil.getCacheConfigs();
	}

	protected void doGet(HttpServletRequest httpServletRequest, HttpServletResponse httpServletResponse) throws ServletException, IOException {
		String aid = httpServletRequest.getParameter("appid");
		if(aid == null || aid.isEmpty()) {	
			httpServletResponse.sendError(HttpServletResponse.SC_BAD_REQUEST, "Parameter <appid> is required.");
			return;
		}		
		aid = aid.trim().replaceAll("%20", "");

		String s_appver = httpServletRequest.getParameter("appver");
		int appver;
		if(s_appver == null || s_appver.isEmpty()) {	
		    // Default to 127 if no version is passed in
		    s_appver = "127";
		}

		try {
			appver = Integer.parseInt(s_appver);
		}
		catch(java.lang.NumberFormatException e) {
			s_appver = s_appver.replace(".", "");

			appver = Integer.parseInt(s_appver);
		}

		appver = cacheConfigVersion(appver);

		//The response varies between versions, so we use a composite key
		String key = aid + "_" + appver;
		Element cachedConfig = configsCache.get(key);

		String jsonConfig = null;

		if(cachedConfig != null) {
			log.debug("Cache hit on \"" + key + "\"");
			jsonConfig = (String)cachedConfig.getObjectValue();
		}
		else {
			log.warn("Cache <config> miss on \"" + key + "\"");
		    jsonConfig = "[]";
		}

		httpServletResponse.setCharacterEncoding("UTF-8");
		httpServletResponse.setContentType("application/json");

		PrintWriter out = httpServletResponse.getWriter();
		if(jsonConfig == null) {
			out.print("[]");
		}
		else {
			out.print(jsonConfig);
		}
		out.close();	
	}

	private int cacheConfigVersion(int appver) {
		if(appver >= 250) 
			return 250;
		else if(appver >= 200)
			return 200;
		else if(appver >= 127) 
			return 127;
		else 
			return 103;
	}
}

