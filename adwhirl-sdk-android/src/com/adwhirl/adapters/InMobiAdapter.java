package com.adwhirl.adapters;

import com.adwhirl.AdWhirlLayout;
import com.adwhirl.AdWhirlTargeting;
import com.adwhirl.AdWhirlLayout.ViewAdRunnable;
import com.adwhirl.AdWhirlTargeting.Gender;
import com.adwhirl.obj.Extra;
import com.adwhirl.obj.Ration;
import com.adwhirl.util.AdWhirlUtil;
import com.inmobi.androidsdk.IMAdListener;
import com.inmobi.androidsdk.IMAdRequest;
import com.inmobi.androidsdk.IMAdRequest.ErrorCode;
import com.inmobi.androidsdk.IMAdRequest.GenderType;
import com.inmobi.androidsdk.IMAdView;

import android.app.Activity;
import android.util.Log;

import java.util.HashMap;
import java.util.Map;

/**
 * An adapter for the InMobi Android SDK.
 *
 * Note: The InMobi site Id is looked up using ration.key
 */

public final class InMobiAdapter extends AdWhirlAdapter implements IMAdListener {
  private Extra extra = null;

  public InMobiAdapter(AdWhirlLayout adWhirlLayout, Ration ration) {
    super(adWhirlLayout, ration);
    extra = adWhirlLayout.extra;
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

    IMAdView adView = new IMAdView(activity, IMAdView.INMOBI_AD_UNIT_320X50, ration.key);
    adView.setIMAdListener(this);
    IMAdRequest imAdRequest = new IMAdRequest();
    imAdRequest.setAge(AdWhirlTargeting.getAge());
    imAdRequest.setGender(this.getGender());
    imAdRequest.setLocationInquiryAllowed(this.isLocationInquiryAllowed());
    imAdRequest.setTestMode(AdWhirlTargeting.getTestMode());
    imAdRequest.setKeywords(AdWhirlTargeting.getKeywords());
    imAdRequest.setPostalCode(AdWhirlTargeting.getPostalCode());

    // Setting tp key based on InMobi's implementation of this adapter.
    Map<String, String> map = new HashMap<String, String>();
    map.put("tp", "c_adwhirl");
    imAdRequest.setRequestParams(map);

    // Set the auto refresh off.
    adView.setRefreshInterval(IMAdView.REFRESH_INTERVAL_OFF);
    adView.loadNewAd(imAdRequest);
  }

  @Override
  public void onAdRequestCompleted(IMAdView adView) {
    Log.d(AdWhirlUtil.ADWHIRL, "InMobi success");

    AdWhirlLayout adWhirlLayout = adWhirlLayoutReference.get();
    if (adWhirlLayout == null) {
      return;
    }

    adWhirlLayout.adWhirlManager.resetRollover();
    adWhirlLayout.handler.post(new ViewAdRunnable(adWhirlLayout, adView));
    adWhirlLayout.rotateThreadedDelayed();
  }

  @Override
  public void onAdRequestFailed(IMAdView adView, ErrorCode errorCode) {
    Log.d(AdWhirlUtil.ADWHIRL, "InMobi failure (" + errorCode + ")");
    AdWhirlLayout adWhirlLayout = adWhirlLayoutReference.get();
    if (adWhirlLayout == null) {
      return;
    }
    adWhirlLayout.rollover();
  }

  @Override
  public void onShowAdScreen(IMAdView adView) {
  }

  @Override
  public void onDismissAdScreen(IMAdView adView) {
  }

  public GenderType getGender() {
    Gender gender = AdWhirlTargeting.getGender();
    if (Gender.MALE == gender) {
      return GenderType.MALE;
    }
    if (Gender.FEMALE == gender) {
      return GenderType.FEMALE;
    }
    return GenderType.NONE;
  }

  public boolean isLocationInquiryAllowed() {
    if (extra.locationOn == 1) {
      return true;
    } else {
      return false;
    }
  }

  @Override
  public void onLeaveApplication(IMAdView adView) {
  }
}
