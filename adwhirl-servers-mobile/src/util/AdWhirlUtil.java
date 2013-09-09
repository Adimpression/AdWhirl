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

import org.apache.log4j.Logger;

import com.amazonaws.AmazonClientException;
import com.amazonaws.AmazonServiceException;
import com.amazonaws.auth.BasicAWSCredentials;
import com.amazonaws.services.simpledb.AmazonSimpleDB;
import com.amazonaws.services.simpledb.AmazonSimpleDBClient;

public class AdWhirlUtil {
        public static final String SERVER =
            "CHANGEME";


	public static final String DOMAIN_APP_CUSTOMS = "app_customs";
	public static final String DOMAIN_APPS = "apps";
	public static final String DOMAIN_APPS_INVALID = "apps_invalid";
	public static final String DOMAIN_NETWORKS = "networks";
	public static final String DOMAIN_CUSTOMS = "customs";
	public static final String DOMAIN_CUSTOMS_INVALID = "customs_invalid";
	public static final String DOMAIN_STATS = "stats";
	public static final String DOMAIN_STATS_TEMP = "stats_temp";
	public static final String DOMAIN_STATS_INVALID = "stats_invalid";
	public static final String DOMAIN_USERS = "users";
	public static final String DOMAIN_USERS_FORGOT = "users_forgot";
	public static final String DOMAIN_USERS_UNVERIFIED = "users_unverified";

        public static final String myAccessKey =
            "CHANGEME";


        public static final String mySecretKey =
            "CHANGEME";


	//Special characters need to be escaped.
	public static final String KEY_SPLIT = "\\|;\\|";

	public static enum NETWORKS {
		PADDING, //We want to be 1-indexed
		ADMOB,
		JUMPTAP,
		VIDEOEGG,
		MEDIALETS,
		LIVERAIL,
		MILLENNIAL,
		GREYSTRIPE,
		QUATTRO,
		CUSTOM,
		ADWHIRL,
		MOBCLIX,
		MDOTM,
		FOURTHSCREEN,
		GOOGLE_ADSENSE,
		GOOGLE_DOUBLECLICK,
		GENERIC,
		EVENT,
		INMOBI,
		IAD,
		ZESTADZ
	}

	public static String getNetworkPrefix(int type) {
		switch(type) {
		case 1:
			return "admob";
		case 2:
			return "jumptap";
		case 3:
			return "videoegg";
		case 4:
			return "medialets";
		case 5:
			return "liverail";
		case 6:
			return "millennial";
		case 7:
			return "greystripe";
		case 8:
			return "quattro";
		case 9:
			return "custom";
		case 10:
			return "adrollo";  //adwhirl
		case 11:
			return "mobclix";
		case 12:
			return "mdotm";
		case 13:
			return "adrollo";  //4thscreen
		case 14:
			return "google_adsense";
		case 15:
			return "google_doubleclick";
		case 16:
			return "generic";
		case 17:
			return "event";
		case 18:
			return "inmobi";
		case 19:
			return "iad";
		case 20:
			return "zestadz";
		default:
			return "unknown";
		}
	}

	public enum HITTYPE {
		PADDING,
		IMPRESSION,
		CLICK
	}

	public enum LAUNCH_TYPE {
		PADDING,
		LAUNCH_TYPE_SAFARI,
		LAUNCH_TYPE_CANVAS,
		LAUNCH_TYPE_LS
	}

	public static AmazonSimpleDB getSDB() {
		return new AmazonSimpleDBClient(new BasicAWSCredentials(AdWhirlUtil.myAccessKey, AdWhirlUtil.mySecretKey));
	}

	public static void logException(Exception e, Logger log) {
		Class<? extends Exception> eClass = e.getClass();

		if(eClass == AmazonServiceException.class) {
			AmazonServiceException ase = (AmazonServiceException)e;
			log.warn("Caught an AmazonServiceException:");
			log.warn("\tError Message:    " + ase.getMessage());
			log.warn("\tHTTP Status Code: " + ase.getStatusCode());
			log.warn("\tAWS Error Code:   " + ase.getErrorCode());
			log.warn("\tError Type:       " + ase.getErrorType());
			log.warn("\tRequest ID:       " + ase.getRequestId());
		}
		else if(eClass == AmazonClientException.class) {
			AmazonClientException ace = (AmazonClientException)e;
			log.warn("Caught an AmazonClientException:");
			log.warn("\tError Message: " + ace.getMessage());
		}
		else {
			log.warn("Exception caught: ", e);
		}
	}

	public static double round(double number, int precision) {
		return Math.round(number * Math.pow(10, precision))/Math.pow(10, precision);
	}
}
