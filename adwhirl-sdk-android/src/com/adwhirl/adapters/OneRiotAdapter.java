package com.adwhirl.adapters;

import java.util.ArrayList;
import android.app.Activity;
import android.util.Log;
import com.adwhirl.AdWhirlLayout;
import com.adwhirl.AdWhirlLayout.ViewAdRunnable;
import com.adwhirl.obj.*;
import com.adwhirl.util.*;
import com.oneriot.*;

public class OneRiotAdapter extends AdWhirlAdapter implements
               OneRiotAdActionListener {
  public OneRiotAdapter(AdWhirlLayout adWhirlLayout, Ration ration) {
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

    OneRiotAd adView = new OneRiotAd(activity, ration.key);
    Extra extra = adWhirlLayout.extra;
    
    adView.setListener(this);
    adView.setRefreshInterval(oneRiotRefreshInterval);
    adView.setReportGPS(extra.locationOn == 1);

    for(String s : oneRiotContextParameters){
      adView.addContextParameters(s);
    }
        
    adView.loadAd(activity);
  }
    
  protected static int oneRiotRefreshInterval = 0;
  protected static ArrayList<String> oneRiotContextParameters =
                                       new ArrayList<String>();

  public static void setOneRiotRefreshInterval(int interval) {
    oneRiotRefreshInterval = interval;
  }
    
  public static void setOneRiotContextParameters(ArrayList<String>
                                                 contextParameters) {
    oneRiotContextParameters = contextParameters;
  }

  @Override
  public void adRequestCompletedSuccessfully(OneRiotAd adView) {
    Log.d(AdWhirlUtil.ADWHIRL, "OneRiot success");
    
    AdWhirlLayout adWhirlLayout = adWhirlLayoutReference.get();

    adWhirlLayout.adWhirlManager.resetRollover();
    adWhirlLayout.handler.post(new ViewAdRunnable(adWhirlLayout, adView));    
    adWhirlLayout.rotateThreadedDelayed();
  }

  @Override
  public void adRequestFailed(OneRiotAd adView) {
    Log.d(AdWhirlUtil.ADWHIRL, "OneRiot failure");
    adView.setListener(null);

    AdWhirlLayout adWhirlLayout = adWhirlLayoutReference.get();

    if (adWhirlLayout == null) {
      return;
    }

    adWhirlLayout.rollover();
  }
}
