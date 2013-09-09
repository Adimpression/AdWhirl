package com.adwhirl.adapters;

import android.app.Activity;
import android.graphics.Color;
import android.util.Log;
import android.view.View;

import com.adwhirl.AdWhirlLayout;
import com.adwhirl.AdWhirlLayout.ViewAdRunnable;
import com.adwhirl.obj.Extra;
import com.adwhirl.obj.Ration;
import com.adwhirl.util.AdWhirlUtil;
import com.mdotm.android.ads.MdotMManager;
import com.mdotm.android.ads.MdotMView;
import com.mdotm.android.ads.MdotMView.MdotMActionListener;

/**
 * This file was provided by MdotM. Please contact support@mdotm.com with any
 * questions or concerns.
 */
public class MdotMAdapter extends AdWhirlAdapter implements MdotMActionListener {
  public MdotMAdapter(AdWhirlLayout adWhirlLayout, Ration ration) {
    super(adWhirlLayout, ration);
  }

  @Override
  public void handle() {
    AdWhirlLayout adWhirlLayout = adWhirlLayoutReference.get();
    if(adWhirlLayout == null) {
      return;
    }

    try {
      String ration_key = this.ration.key;
      MdotMManager.setPublisherId(ration_key);
      MdotMManager.setMediationLayerName(AdWhirlUtil.ADWHIRL);
      MdotMManager.setMediationLayerVersion(AdWhirlUtil.VERSION);
    }
    // Thrown on invalid publisher id
    catch(IllegalArgumentException e) {
      adWhirlLayout.rollover();
      return;
    }

    Activity activity = adWhirlLayout.activityReference.get();
    if(activity == null) {
      return;
    }
    MdotMView mdotm = new MdotMView(activity, this);

    mdotm.setListener(this);
    Extra extra = adWhirlLayout.extra;
    int bgColor = Color.rgb(extra.bgRed, extra.bgGreen, extra.bgBlue);
    int fgColor = Color.rgb(extra.fgRed, extra.fgGreen, extra.fgBlue);

    mdotm.setBackgroundColor(bgColor);
    mdotm.setTextColor(fgColor);
  }

  public void adRequestCompletedSuccessfully(MdotMView adView) {
    Log.d(AdWhirlUtil.ADWHIRL, "MdotM success");

    AdWhirlLayout adWhirlLayout = adWhirlLayoutReference.get();
    if(adWhirlLayout == null) {
      return;
    }
    adView.setListener(null);
    adView.setVisibility(View.VISIBLE);

    adWhirlLayout.adWhirlManager.resetRollover();
    adWhirlLayout.handler.post(new ViewAdRunnable(adWhirlLayout, adView));
    adWhirlLayout.rotateThreadedDelayed();
  }

  public void adRequestFailed(MdotMView adView) {
    Log.d(AdWhirlUtil.ADWHIRL, "MdotM failure");
    adView.setListener(null);

    AdWhirlLayout adWhirlLayout = adWhirlLayoutReference.get();
    if(adWhirlLayout == null) {
      return;
    }
    adWhirlLayout.rollover();
  }
}
