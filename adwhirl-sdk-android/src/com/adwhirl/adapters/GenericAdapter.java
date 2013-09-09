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
import com.adwhirl.obj.Ration;
import com.adwhirl.util.AdWhirlUtil;

import android.util.Log;

public class GenericAdapter extends AdWhirlAdapter {
  public GenericAdapter(AdWhirlLayout adWhirlLayout, Ration ration) {
    super(adWhirlLayout, ration);
  }

  @Override
  public void handle() {
    Log.d(AdWhirlUtil.ADWHIRL, "Generic notification request initiated");

    AdWhirlLayout adWhirlLayout = adWhirlLayoutReference.get();
    if (adWhirlLayout == null) {
      return;
    }

    // If the user set a handler for notifications, call it
    if (adWhirlLayout.adWhirlInterface != null) {
      adWhirlLayout.adWhirlInterface.adWhirlGeneric();
    } else {
      Log.w(AdWhirlUtil.ADWHIRL,
          "Generic notification sent, but no interface is listening");
    }

    adWhirlLayout.adWhirlManager.resetRollover();
    adWhirlLayout.rotateThreadedDelayed();
  }
}
