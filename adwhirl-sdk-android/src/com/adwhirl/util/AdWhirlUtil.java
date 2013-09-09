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

package com.adwhirl.util;

import android.app.Activity;
import android.content.Context;
import android.os.Build;
import android.provider.Settings;
import android.util.DisplayMetrics;

import java.math.BigInteger;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.util.Locale;

public class AdWhirlUtil {
  public static final String urlConfig = "http://mob.adwhirl.com/getInfo.php?appid=%s&appver=%d&client=2";
  public static final String urlImpression = "http://met.adwhirl.com/exmet.php?appid=%s&nid=%s&type=%d&uuid=%s&country_code=%s&appver=%d&client=2";
  public static final String urlClick = "http://met.adwhirl.com/exclick.php?appid=%s&nid=%s&type=%d&uuid=%s&country_code=%s&appver=%d&client=2";
  public static final String urlCustom = "http://cus.adwhirl.com/custom.php?appid=%s&nid=%s&uuid=%s&country_code=%s%s&appver=%d&client=2";

  public static final String locationString = "&location=%f,%f&location_timestamp=%d";

  // Don't change anything below this line
  /***********************************************/

  public static final int VERSION = 320;

  public static final String ADWHIRL = "AdWhirl SDK";

  // Could be an enum, but this gives us a slight performance improvement
  public static final int NETWORK_TYPE_ADMOB = 1;
  public static final int NETWORK_TYPE_JUMPTAP = 2;
  public static final int NETWORK_TYPE_VIDEOEGG = 3;
  public static final int NETWORK_TYPE_MEDIALETS = 4;
  public static final int NETWORK_TYPE_LIVERAIL = 5;
  public static final int NETWORK_TYPE_MILLENNIAL = 6;
  public static final int NETWORK_TYPE_GREYSTRIP = 7;
  public static final int NETWORK_TYPE_QUATTRO = 8;
  public static final int NETWORK_TYPE_CUSTOM = 9;
  public static final int NETWORK_TYPE_ADWHIRL = 10;
  public static final int NETWORK_TYPE_MOBCLIX = 11;
  public static final int NETWORK_TYPE_MDOTM = 12;
  public static final int NETWORK_TYPE_4THSCREEN = 13;
  public static final int NETWORK_TYPE_ADSENSE = 14;
  public static final int NETWORK_TYPE_DOUBLECLICK = 15;
  public static final int NETWORK_TYPE_GENERIC = 16;
  public static final int NETWORK_TYPE_EVENT = 17;
  public static final int NETWORK_TYPE_INMOBI = 18;
  public static final int NETWORK_TYPE_ZESTADZ = 20;
  public static final int NETWORK_TYPE_ONERIOT = 23;
  public static final int NETWORK_TYPE_NEXAGE = 24;

  public static final int CUSTOM_TYPE_BANNER = 1;
  public static final int CUSTOM_TYPE_ICON = 2;

  private static double density = -1;

  public static String convertToHex(byte[] data) {
    StringBuffer buf = new StringBuffer();
    for (byte element : data) {
      int halfbyte = (element >>> 4) & 0x0F;
      int two_halfs = 0;
      do {
        if ((0 <= halfbyte) && (halfbyte <= 9)) {
          buf.append((char) ('0' + halfbyte));
        } else {
          buf.append((char) ('a' + (halfbyte - 10)));
        }
        halfbyte = element & 0x0F;
      } while (two_halfs++ < 1);
    }
    return buf.toString();
  }

  /**
   * Gets the screen density for the device.
   * 
   * @param activity
   *          is the current activity.
   * 
   * @return A double value representing the device's screen density.
   */
  public static double getDensity(Activity activity) {
    if (density == -1) {
      DisplayMetrics displayMetrics = new DisplayMetrics();
      activity.getWindowManager().getDefaultDisplay()
          .getMetrics(displayMetrics);
      density = displayMetrics.density;
    }

    return density;
  }

  /**
   * Converts device independent pixels to screen pixels.
   * 
   * @param dipPixels
   *          is the amount of device independent pixels.
   * @param density
   *          is the device's screen density.
   * 
   * @return An integer representing the value in screen pixels.
   */
  public static int convertToScreenPixels(int dipPixels, double density) {
    return (int) convertToScreenPixels((double) dipPixels, density);
  }

  /**
   * Converts device independent pixels to screen pixels.
   * 
   * @param dipPixels
   *          is the amount of device independent pixels.
   * @param density
   *          is the device's screen density.
   * 
   * @return A double representing the value in screen pixels.
   */
  public static double convertToScreenPixels(double dipPixels, double density) {
    return (density > 0) ? (dipPixels * density) : dipPixels;
  }
  
  /**
   * Gets the md5 hashed and upper-cased device id.
   *
   * @param context
   *          the application context.
   *
   * @return The encoded device id.
   */
  public static String getEncodedDeviceId(Context context) {
    String androidId = Settings.Secure.getString(
        context.getContentResolver(), Settings.Secure.ANDROID_ID);

    String hashedId;
    if ((androidId == null) || isEmulator()) {
      hashedId = md5("emulator");
    } else {
      hashedId = md5(androidId);
    }

    if (hashedId == null) {
      return null;
    }

    return hashedId.toUpperCase(Locale.US);
  }
  
  /**
   * Method for returning an md5 hash of a string.
   *
   * @param val
   *          the string to hash.
   *
   * @return A hex string representing the md5 hash of the input.
   */
  private static String md5(String val) {
    String result = null;

    if ((val != null) && (val.length() > 0)) {
      try {
        MessageDigest md5 = MessageDigest.getInstance("MD5");
        md5.update(val.getBytes(), 0, val.length());
        result = String.format("%032X", new BigInteger(1, md5.digest()));
      } catch (NoSuchAlgorithmException nsae) {
        result = val.substring(0, 32);
      }
    }

    return result;
  }
  
  /**
   * Checks whether or not the running device is an emulator.
   *
   * @return Boolean indicating if the app is currently running in an emulator.
   */
  public static boolean isEmulator() {
    return (Build.BOARD.equals("unknown")
        && Build.DEVICE.equals("generic")
        && Build.BRAND.equals("generic"));
  }
}
