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
import com.adwhirl.AdWhirlLayout.ViewAdRunnable;
import com.adwhirl.obj.Ration;
import com.adwhirl.util.AdWhirlUtil;
import com.zestadz.android.AdManager;
import com.zestadz.android.ZestADZAdView;
import com.zestadz.android.ZestADZAdView.ZestADZListener;

import android.app.Activity;
import android.util.Log;

public class ZestAdzAdapter extends AdWhirlAdapter implements ZestADZListener {
  public ZestAdzAdapter(AdWhirlLayout adWhirlLayout, Ration ration) {
    super(adWhirlLayout, ration);
  }

  @Override
  public void handle() {
    AdWhirlLayout adWhirlLayout = adWhirlLayoutReference.get();
    if (adWhirlLayout == null) {
      return;
    }

    try {
      AdManager.setadclientId(ration.key);
    }
    // Thrown on invalid client id.
    catch (IllegalArgumentException e) {
      adWhirlLayout.rollover();
      return;
    }

    try {
      Activity activity = adWhirlLayout.activityReference.get();
      if (activity == null) {
        return;
      }

      ZestADZAdView adView = new ZestADZAdView(activity);
      adView.setListener(this);
      adView.displayAd();
    } catch (Exception e) {
      adWhirlLayout.rollover();
    }
  }

  // This block contains the ZestADZ listeners
  /*******************************************************************/
  public void AdReturned(ZestADZAdView adView) {
    Log.d(AdWhirlUtil.ADWHIRL, "ZestADZ success");

    AdWhirlLayout adWhirlLayout = adWhirlLayoutReference.get();
    if (adWhirlLayout == null) {
      return;
    }

    adWhirlLayout.adWhirlManager.resetRollover();
    adWhirlLayout.handler.post(new ViewAdRunnable(adWhirlLayout, adView));
    adWhirlLayout.rotateThreadedDelayed();
  }

  public void AdFailed(ZestADZAdView adView) {
    Log.d(AdWhirlUtil.ADWHIRL, "ZestADZ failure");

    adView.setListener(null);

    AdWhirlLayout adWhirlLayout = adWhirlLayoutReference.get();
    if (adWhirlLayout == null) {
      return;
    }

    adWhirlLayout.rollover();
  }

}
