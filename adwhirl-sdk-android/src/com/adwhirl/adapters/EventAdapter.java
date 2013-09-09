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
import com.adwhirl.AdWhirlLayout.AdWhirlInterface;
import com.adwhirl.obj.Ration;
import com.adwhirl.util.AdWhirlUtil;

import android.util.Log;

import java.lang.reflect.Method;

public class EventAdapter extends AdWhirlAdapter {
  public EventAdapter(AdWhirlLayout adWhirlLayout, Ration ration) {
    super(adWhirlLayout, ration);
  }

  @Override
  public void handle() {
    Log.d(AdWhirlUtil.ADWHIRL, "Event notification request initiated");

    AdWhirlLayout adWhirlLayout = adWhirlLayoutReference.get();
    if (adWhirlLayout == null) {
      return;
    }

    // If the user set a handler for notifications, call it
    if (adWhirlLayout.adWhirlInterface != null) {
      String key = this.ration.key;
      String method = null;
      if (key == null) {
        Log.w(AdWhirlUtil.ADWHIRL, "Event key is null");
        adWhirlLayout.rollover();
        return;
      }

      int methodIndex = key.indexOf("|;|");
      if (methodIndex < 0) {
        Log.w(AdWhirlUtil.ADWHIRL, "Event key separator not found");
        adWhirlLayout.rollover();
        return;
      }

      method = key.substring(methodIndex + 3);

      Class<? extends AdWhirlInterface> listenerClass = adWhirlLayout.adWhirlInterface
          .getClass();
      Method listenerMethod;
      try {
        listenerMethod = listenerClass.getMethod(method, (Class[]) null);
        listenerMethod.invoke(adWhirlLayout.adWhirlInterface, (Object[]) null);
      } catch (Exception e) {
        Log.e(AdWhirlUtil.ADWHIRL, "Caught exception in handle()", e);
        adWhirlLayout.rollover();
        return;
      }
    } else {
      Log.w(AdWhirlUtil.ADWHIRL,
          "Event notification would be sent, but no interface is listening");
      adWhirlLayout.rollover();
      return;
    }

    // In your custom event code, you'll want to call some of the below methods.
    //
    // On success:
    // adWhirlLayout.adWhirlManager.resetRollover();
    // adWhirlLayout.rotateThreadedDelayed();
    //
    // On failure:
    // adWhirlLayout.rollover();
  }
}
