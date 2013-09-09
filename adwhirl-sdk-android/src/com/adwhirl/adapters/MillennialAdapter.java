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

package com.adwhirl.adapters;

import com.adwhirl.AdWhirlLayout;
import com.adwhirl.AdWhirlTargeting;
import com.adwhirl.AdWhirlLayout.ViewAdRunnable;
import com.adwhirl.AdWhirlTargeting.Gender;
import com.adwhirl.obj.Extra;
import com.adwhirl.obj.Ration;
import com.adwhirl.util.AdWhirlUtil;
import com.millennialmedia.android.MMAdView;
import com.millennialmedia.android.MMAdView.MMAdListener;
import com.millennialmedia.android.MMAdViewSDK;

import android.app.Activity;
import android.text.TextUtils;
import android.util.Log;

import java.util.Hashtable;

public class MillennialAdapter extends AdWhirlAdapter implements MMAdListener {
  public MillennialAdapter(AdWhirlLayout adWhirlLayout, Ration ration) {
    super(adWhirlLayout, ration);
  }

  @Override
  public void handle() {
    AdWhirlLayout adWhirlLayout = adWhirlLayoutReference.get();
    if (adWhirlLayout == null) {
      return;
    }

    Hashtable<String, String> map = new Hashtable<String, String>();

    final AdWhirlTargeting.Gender gender = AdWhirlTargeting.getGender();
    if (gender == Gender.MALE) {
      map.put(MMAdView.KEY_GENDER, "male");
    } else if (gender == Gender.FEMALE) {
      map.put(MMAdView.KEY_GENDER, "female");
    }

    final int age = AdWhirlTargeting.getAge();
    if (age != -1) {
      map.put(MMAdView.KEY_AGE, String.valueOf(age));
    }

    final String postalCode = AdWhirlTargeting.getPostalCode();
    if (!TextUtils.isEmpty(postalCode)) {
      map.put(MMAdView.KEY_ZIP_CODE, postalCode);
    }
    final String keywords = AdWhirlTargeting.getKeywordSet() != null ? TextUtils
        .join(",", AdWhirlTargeting.getKeywordSet())
        : AdWhirlTargeting.getKeywords();
    if (!TextUtils.isEmpty(keywords)) {
      map.put(MMAdView.KEY_KEYWORDS, keywords);
    }

    // MM requests this pair to be specified
    map.put(MMAdView.KEY_VENDOR, "adwhirl");

    // Instantiate an ad view and add it to the view
    MMAdView adView = new MMAdView((Activity) adWhirlLayout.getContext(),
        ration.key, MMAdView.BANNER_AD_TOP, MMAdView.REFRESH_INTERVAL_OFF, map);
    adView.setId(MMAdViewSDK.DEFAULT_VIEWID);
    adView.setListener(this);
    adView.callForAd();

    Extra extra = adWhirlLayout.extra;
    if (extra.locationOn == 1 && adWhirlLayout.adWhirlManager.location != null) {
      adView.updateUserLocation(adWhirlLayout.adWhirlManager.location);
    }

    adView.setHorizontalScrollBarEnabled(false);
    adView.setVerticalScrollBarEnabled(false);
  }

  public void MMAdReturned(MMAdView adView) {
    Log.d(AdWhirlUtil.ADWHIRL, "Millennial success");
    adView.setListener(null);

    AdWhirlLayout adWhirlLayout = adWhirlLayoutReference.get();
    if (adWhirlLayout == null) {
      return;
    }

    adWhirlLayout.adWhirlManager.resetRollover();
    adWhirlLayout.handler.post(new ViewAdRunnable(adWhirlLayout, adView));
    adWhirlLayout.rotateThreadedDelayed();
  }

  public void MMAdFailed(MMAdView adView) {
    Log.d(AdWhirlUtil.ADWHIRL, "Millennial failure");
    adView.setListener(null);

    AdWhirlLayout adWhirlLayout = adWhirlLayoutReference.get();
    if (adWhirlLayout == null) {
      return;
    }

    adWhirlLayout.rollover();
  }

  public void MMAdClickedToNewBrowser(MMAdView adview) {
    Log.d(AdWhirlUtil.ADWHIRL, "Millennial Ad clicked, new browser launched");
  }

  public void MMAdClickedToOverlay(MMAdView adview) {
    Log.d(AdWhirlUtil.ADWHIRL, "Millennial Ad Clicked to overlay");
  }

  public void MMAdOverlayLaunched(MMAdView adview) {
    Log.d(AdWhirlUtil.ADWHIRL, "Millennial Ad Overlay Launched");
  }

  public void MMAdRequestIsCaching(MMAdView adView) {
    //do nothing
  }
  
  public void MMAdCachingCompleted(MMAdView adview, boolean success) {
    // Do nothing. This callback is not used for banner ads.
  }
}
