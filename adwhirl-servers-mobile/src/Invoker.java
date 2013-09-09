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

import com.amazonaws.services.simpledb.AmazonSimpleDB;
import com.amazonaws.services.simpledb.model.CreateDomainRequest;

import org.apache.log4j.Logger;
import org.mortbay.jetty.Handler;
import org.mortbay.jetty.NCSARequestLog;
import org.mortbay.jetty.Server;
import org.mortbay.jetty.handler.ContextHandlerCollection;
import org.mortbay.jetty.handler.HandlerCollection;
import org.mortbay.jetty.handler.RequestLogHandler;
import org.mortbay.jetty.servlet.Context;
import org.mortbay.jetty.servlet.ServletHolder;

import servlet.ConfigServlet;
import servlet.CustomsServlet;
import servlet.HealthCheckServlet;
import servlet.MetricsServlet;

import util.AdWhirlUtil;
import util.CacheUtil;

public class Invoker {
	static Logger log = Logger.getLogger("Invoker");
	
	private static AmazonSimpleDB sdb;
	
	public static void main(String[] args) {
        sdb = AdWhirlUtil.getSDB();
        
		/*
		try {
			setupSimpleDB();
		} catch (AmazonSimpleDBException e) {
			log.fatal("Unable to initialize SimpleDB databases: " + e.getMessage());
			System.exit(0);
		}
		*/

		CacheUtil.initalize();
		CacheUtil.preload();

		Server server = new Server(80);

		//import org.mortbay.thread.QueuedThreadPool;
		/*
		  QueuedThreadPool threadPool = new QueuedThreadPool();
		  threadPool.setMinThreads(10);
		  threadPool.setMaxThreads(100);
		  server.setThreadPool(threadPool);
		*/
		
		try {
			HandlerCollection handlers = new HandlerCollection();
			ContextHandlerCollection contextHandlerCollection = new ContextHandlerCollection();
	        Context servletContext = new Context(contextHandlerCollection, "/");
	        
	        // CustomsServlet must be added before ConfigServlet
			servletContext.addServlet(new ServletHolder(new	CustomsServlet()), "/custom.php");
			servletContext.addServlet(new ServletHolder(new	ConfigServlet()), "/getInfo.php");
			
			ServletHolder metricsServletHolder = new ServletHolder(new MetricsServlet());
			servletContext.addServlet(metricsServletHolder, "/exmet.php");
			servletContext.addServlet(metricsServletHolder, "/exclick.php");
			
			servletContext.addServlet(new ServletHolder(new	HealthCheckServlet()), "/ping");
			
	        RequestLogHandler requestLogHandler = new RequestLogHandler();
	        NCSARequestLog requestLog = new NCSARequestLog("/mnt/adwhirl/jetty-yyyy_mm_dd.request.log");
	        requestLog.setRetainDays(8);
	        requestLog.setAppend(true);
	        requestLog.setExtended(false);
	        requestLog.setLogTimeZone("GMT");
	        requestLogHandler.setRequestLog(requestLog);
	        
	        handlers.setHandlers(new Handler[]{contextHandlerCollection ,requestLogHandler});
	        server.setHandler(handlers);
	        server.start();
		} 
		catch (Exception e) {
			log.fatal("Unable to start server: " + e.getMessage());
			System.exit(0);
		}		
	}

	@SuppressWarnings("unused")
	private static void setupSimpleDB() {
		createDomain(AdWhirlUtil.DOMAIN_APP_CUSTOMS);
		createDomain(AdWhirlUtil.DOMAIN_APPS);
		createDomain(AdWhirlUtil.DOMAIN_APPS_INVALID);
		createDomain(AdWhirlUtil.DOMAIN_NETWORKS);
		createDomain(AdWhirlUtil.DOMAIN_CUSTOMS);
		createDomain(AdWhirlUtil.DOMAIN_CUSTOMS_INVALID);
		createDomain(AdWhirlUtil.DOMAIN_STATS);
		createDomain(AdWhirlUtil.DOMAIN_STATS_TEMP);
		createDomain(AdWhirlUtil.DOMAIN_STATS_INVALID);
		createDomain(AdWhirlUtil.DOMAIN_USERS);
		createDomain(AdWhirlUtil.DOMAIN_USERS_FORGOT);
		createDomain(AdWhirlUtil.DOMAIN_USERS_UNVERIFIED);
	}
	
	private static void createDomain(String domain) {
		log.info("Creating Amazon SimpleDB domain: " + domain);
		CreateDomainRequest request = new CreateDomainRequest(domain);
		sdb.createDomain(request);
	}
}
