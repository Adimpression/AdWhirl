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

package com.adwhirl;

import com.adwhirl.AdWhirlLayout.AdWhirlInterface;
import com.adwhirl.adapters.AdWhirlAdapter;
import com.adwhirl.util.AdWhirlUtil;

import android.app.Activity;
import android.os.Bundle;
import android.util.DisplayMetrics;
import android.util.Log;
import android.view.Gravity;
import android.view.ViewGroup.LayoutParams;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import java.util.Arrays;
import java.util.HashSet;

public class Invoker extends Activity implements AdWhirlInterface {
  // For more easily detecting memory leaks.
  // byte[] garbage = new byte[1000 * 1024];

  /** Called when the activity is first created. */
  @Override
  public void onCreate(Bundle savedInstanceState) {
    super.onCreate(savedInstanceState);

    setContentView(R.layout.main);
    LinearLayout layout = (LinearLayout) findViewById(R.id.layout_main);

    if (layout == null) {
      Log.e("AdWhirl", "Layout is null!");
      return;
    }

    // These are density-independent pixel units, as defined in
    // http://developer.android.com/guide/practices/screens_support.html
    int width = 320;
    int height = 52;

    DisplayMetrics displayMetrics = getResources().getDisplayMetrics();
    float density = displayMetrics.density;

    width = (int) (width * density);
    height = (int) (height * density);

    AdWhirlTargeting.setAge(23);
    AdWhirlTargeting.setGender(AdWhirlTargeting.Gender.MALE);
    String keywords[] = { "online", "games", "gaming" };
    AdWhirlTargeting
        .setKeywordSet(new HashSet<String>(Arrays.asList(keywords)));
    AdWhirlTargeting.setPostalCode("94123");
    AdWhirlTargeting.setTestMode(false);

    AdWhirlAdapter.setGoogleAdSenseAppName("AdWhirl Test App");
    AdWhirlAdapter.setGoogleAdSenseCompanyName("AdWhirl");

    // Optional, will fetch new config if necessary after five minutes.
    AdWhirlManager.setConfigExpireTimeout(1000 * 60 * 5);

    // References AdWhirlLayout defined in the layout XML.
    AdWhirlLayout adWhirlLayout = (AdWhirlLayout) findViewById(R.id.adwhirl_layout);
    adWhirlLayout.setAdWhirlInterface(this);
    adWhirlLayout.setMaxWidth(width);
    adWhirlLayout.setMaxHeight(height);

    // Instantiates AdWhirlLayout from code.
    // Note: Showing two ads on the same screen is for illustrative purposes
    // only.
    // You should check with ad networks on their specific policies.
    AdWhirlLayout adWhirlLayout2 = new AdWhirlLayout(this,
        "46a9e26bb1f5499ab7b00c9807ae034b");
    adWhirlLayout2.setAdWhirlInterface(this);
    adWhirlLayout2.setMaxWidth(width);
    adWhirlLayout2.setMaxHeight(height);
    RelativeLayout.LayoutParams adWhirlLayoutParams = new RelativeLayout.LayoutParams(
        LayoutParams.WRAP_CONTENT, LayoutParams.WRAP_CONTENT);
    adWhirlLayoutParams.addRule(RelativeLayout.CENTER_HORIZONTAL);
    layout.setGravity(Gravity.CENTER_HORIZONTAL);
    layout.addView(adWhirlLayout2, adWhirlLayoutParams);

    TextView textView = new TextView(this);
    textView.setText("Below AdWhirlLayout from code");
    layout.addView(textView, adWhirlLayoutParams);
    layout.setGravity(Gravity.CENTER_HORIZONTAL);
    layout.invalidate();
  }

  public void adWhirlGeneric() {
    Log.e(AdWhirlUtil.ADWHIRL, "In adWhirlGeneric()");
  }
}
