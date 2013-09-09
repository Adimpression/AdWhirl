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

import org.apache.log4j.Logger;

import thread.InvalidateDaemon;
import thread.RollupDaemon;

public class Daemon {
	static Logger log = Logger.getLogger("Daemon");
	
	public static void main(String[] args) {
		Thread rollupDaemon = new Thread(new RollupDaemon());
		rollupDaemon.start();
		
		Thread invalidateDaemon = new Thread(new InvalidateDaemon());
		invalidateDaemon.start();	
	}
}
