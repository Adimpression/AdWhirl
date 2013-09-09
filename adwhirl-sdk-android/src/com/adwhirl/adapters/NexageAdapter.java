/*
 Copyright 2011 Google, Inc.

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

import java.util.GregorianCalendar;

import android.app.Activity;
import android.graphics.Color;
import android.text.TextUtils;
import android.util.Log;

import com.adwhirl.AdWhirlLayout;
import com.adwhirl.AdWhirlLayout.ViewAdRunnable;
import com.adwhirl.AdWhirlTargeting;
import com.adwhirl.obj.Extra;
import com.adwhirl.obj.Ration;
import com.adwhirl.util.AdWhirlUtil;
import com.nexage.android.NexageAdManager;
import com.nexage.android.NexageAdView;
import com.nexage.android.NexageListener;

public class NexageAdapter extends AdWhirlAdapter implements NexageListener {

  public NexageAdapter(AdWhirlLayout adWhirlLayout, Ration ration) {
    super(adWhirlLayout, ration);
  }

  @Override
  public void handle() {
    AdWhirlLayout adWhirlLayout = adWhirlLayoutReference.get();
    if (adWhirlLayout == null) {
      return;
    }

    Activity activity = adWhirlLayout.activityReference.get();
    if (activity == null) {
      return;
    }

    final int age = AdWhirlTargeting.getAge();
    if (age > 0)
      NexageAdManager.setAge(age);

    NexageAdManager.setTestMode(AdWhirlTargeting.getTestMode());

    final AdWhirlTargeting.Gender gender = AdWhirlTargeting.getGender();
    if (gender == AdWhirlTargeting.Gender.FEMALE) {
      NexageAdManager.setGender(NexageAdManager.Gender.Female);
    }
    else if (gender == AdWhirlTargeting.Gender.MALE) {
      NexageAdManager.setGender(NexageAdManager.Gender.Male);
    }

    final GregorianCalendar birthDate = AdWhirlTargeting.getBirthDate();
    if (birthDate != null) {
      NexageAdManager.setBirthday(birthDate);
    }

    final String postalCode = AdWhirlTargeting.getPostalCode();
    if (!TextUtils.isEmpty(postalCode)) {
      NexageAdManager.setPostCode(postalCode);
    }

    final String keywords =
        AdWhirlTargeting.getKeywordSet() != null ?
            TextUtils.join(",", AdWhirlTargeting.getKeywordSet()) :
            AdWhirlTargeting.getKeywords();
    if (!TextUtils.isEmpty(keywords)) {
      NexageAdManager.setKeywords(keywords);
    }

    NexageAdView adView;
    try {
      String dcn = ration.key;
      NexageAdManager.setDCN(dcn);
      String position = ration.key2;
      adView = new NexageAdView(position, activity);
    }
    // Thrown on invalid position
    catch (IllegalArgumentException e) {
      adWhirlLayout.rollover();
      return;
    }
    Extra extra = adWhirlLayout.extra;
    int bgColor = Color.rgb(extra.bgRed, extra.bgGreen, extra.bgBlue);
    int fgColor = Color.rgb(extra.fgRed, extra.fgGreen, extra.fgBlue);
    adView.setBackgroundColor(bgColor);
    adView.setTextColor(fgColor);

    adView.setListener(this);
    adView.rollover();

    // Nexage callbacks will queue rotate
  }

  // This block contains the NexageListener
  /*******************************************************************/
  @Override
  public void onDisplayAd(NexageAdView arg0) {
  }

  @Override
  public void onFailedToReceiveAd(NexageAdView adView) {
    Log.d(AdWhirlUtil.ADWHIRL, "Nexage failure");

    adView.setListener(null);

    AdWhirlLayout adWhirlLayout = adWhirlLayoutReference.get();
    if (adWhirlLayout == null) {
      return;
    }

    adWhirlLayout.rollover();
  }

  @Override
  public void onReceiveAd(NexageAdView adView) {
    Log.d(AdWhirlUtil.ADWHIRL, "Nexage success");

    AdWhirlLayout adWhirlLayout = adWhirlLayoutReference.get();
    if (adWhirlLayout == null) {
      return;
    }

    adWhirlLayout.adWhirlManager.resetRollover();
    adWhirlLayout.handler.post(new ViewAdRunnable(adWhirlLayout, adView));
    adWhirlLayout.rotateThreadedDelayed();
  }
}