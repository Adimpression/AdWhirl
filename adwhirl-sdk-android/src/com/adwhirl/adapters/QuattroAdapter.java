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
import com.adwhirl.obj.Extra;
import com.adwhirl.obj.Ration;
import com.adwhirl.util.AdWhirlUtil;
import com.qwapi.adclient.android.data.Ad;
import com.qwapi.adclient.android.data.Status;
import com.qwapi.adclient.android.requestparams.AdRequestParams;
import com.qwapi.adclient.android.requestparams.AnimationType;
import com.qwapi.adclient.android.requestparams.DisplayMode;
import com.qwapi.adclient.android.requestparams.MediaType;
import com.qwapi.adclient.android.requestparams.Placement;
import com.qwapi.adclient.android.view.AdEventsListener;
import com.qwapi.adclient.android.view.QWAdView;

import android.app.Activity;
import android.content.Context;
import android.graphics.Color;
import android.text.TextUtils;
import android.util.Log;

import java.util.GregorianCalendar;

import org.json.JSONException;
import org.json.JSONObject;

public class QuattroAdapter extends AdWhirlAdapter implements AdEventsListener {
  private QWAdView quattroView;

  private String siteId = null;
  private String publisherId = null;

  public QuattroAdapter(AdWhirlLayout adWhirlLayout, Ration ration)
      throws JSONException {
    super(adWhirlLayout, ration);

    JSONObject jsonObject = new JSONObject(this.ration.key);
    siteId = jsonObject.getString("siteID");
    publisherId = jsonObject.getString("publisherID");
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

    QWAdView quattro = new QWAdView(activity, siteId, publisherId,
        MediaType.banner, Placement.top, DisplayMode.normal, 0,
        AnimationType.slide, this, true);
    // Make sure to store the view, as Quattro callbacks don't have references
    // to it
    quattroView = quattro;

    Extra extra = adWhirlLayout.extra;
    int bgColor = Color.rgb(extra.bgRed, extra.bgGreen, extra.bgBlue);
    int fgColor = Color.rgb(extra.fgRed, extra.fgGreen, extra.fgBlue);
    quattroView.setBackgroundColor(bgColor);
    quattroView.setTextColor(fgColor);

    // Quattro callbacks will queue rotate
  }

  // This block contains the Quattro listeners
  /*******************************************************************/
  public void onAdClick(Context arg0, Ad arg1) {
  }

  public void onAdRequest(Context context, AdRequestParams params) {
    if (params != null) {
      params.setTestMode(AdWhirlTargeting.getTestMode());

      final AdWhirlTargeting.Gender gender = AdWhirlTargeting.getGender();
      if (gender == AdWhirlTargeting.Gender.FEMALE) {
        params
            .setGender(com.qwapi.adclient.android.requestparams.Gender.female);
      } else if (gender == AdWhirlTargeting.Gender.MALE) {
        params.setGender(com.qwapi.adclient.android.requestparams.Gender.male);
      }

      final GregorianCalendar birthDate = AdWhirlTargeting.getBirthDate();
      if (birthDate != null) {
        params.setBirthDate(birthDate.getTime());
      }

      final String postalCode = AdWhirlTargeting.getPostalCode();
      if (!TextUtils.isEmpty(postalCode)) {
        params.setZipCode(postalCode);
      }
    }
  }

  public void onAdRequestFailed(Context arg0, AdRequestParams arg1, Status arg2) {
    Log.d(AdWhirlUtil.ADWHIRL, "Quattro failure");
    quattroView.setAdEventsListener(null, false);
    quattroView = null;

    AdWhirlLayout adWhirlLayout = adWhirlLayoutReference.get();
    if (adWhirlLayout == null) {
      return;
    }

    adWhirlLayout.rollover();
  }

  public void onAdRequestSuccessful(Context arg0, AdRequestParams arg1, Ad arg2) {
    Log.d(AdWhirlUtil.ADWHIRL, "Quattro success");
    quattroView.setAdEventsListener(null, false);

    AdWhirlLayout adWhirlLayout = adWhirlLayoutReference.get();
    if (adWhirlLayout == null) {
      return;
    }

    adWhirlLayout.adWhirlManager.resetRollover();
    adWhirlLayout.handler.post(new ViewAdRunnable(adWhirlLayout, quattroView));
    adWhirlLayout.rotateThreadedDelayed();
  }

  public void onDisplayAd(Context arg0, Ad arg1) {
  }
  /*******************************************************************/
  // End of Quattro listeners
}
