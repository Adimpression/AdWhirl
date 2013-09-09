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

import org.apache.log4j.Logger;

public class HealthCheckServlet extends HttpServlet {
    private static final long serialVersionUID = 4298132357865054869L;
	 
    static Logger log = Logger.getLogger("HealthCheckServlet");
	 
    public void init(ServletConfig servletConfig) throws ServletException {
	log.info("Servlet initialized completed");
    }
	
    protected void doGet(HttpServletRequest httpServletRequest, HttpServletResponse httpServletResponse) throws ServletException, IOException {
	httpServletResponse.setContentType("text/html");
	PrintWriter out = httpServletResponse.getWriter();
	out.print("OK");
	out.close();	
    }
}
