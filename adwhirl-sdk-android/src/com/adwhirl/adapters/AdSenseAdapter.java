package com.adwhirl.adapters;

import com.adwhirl.AdWhirlLayout;
import com.adwhirl.AdWhirlTargeting;
import com.adwhirl.AdWhirlLayout.ViewAdRunnable;
import com.adwhirl.obj.Extra;
import com.adwhirl.obj.Ration;
import com.adwhirl.util.AdWhirlUtil;
import com.google.ads.AdSenseSpec;
import com.google.ads.AdViewListener;
import com.google.ads.GoogleAdView;
import com.google.ads.AdSenseSpec.AdFormat;
import com.google.ads.AdSenseSpec.ExpandDirection;

import android.content.Context;
import android.text.TextUtils;
import android.util.Log;
import android.view.View;
import android.view.ViewParent;
import android.view.ViewGroup.LayoutParams;
import android.widget.ListView;
import android.widget.ScrollView;

import java.util.ArrayList;
import java.util.List;

public class AdSenseAdapter extends AdWhirlAdapter implements AdViewListener {
  private GoogleAdView adView;

  public AdSenseAdapter(AdWhirlLayout adWhirlLayout, Ration ration) {
    super(adWhirlLayout, ration);
  }

  @Override
  public void handle() {
    AdWhirlLayout adWhirlLayout = adWhirlLayoutReference.get();
    if (adWhirlLayout == null) {
      return;
    }

    String clientId = ration.key;

    if (clientId == null || !clientId.startsWith("ca-mb-app-pub-")) {
      // Invalid publisher ID
      Log.w(AdWhirlUtil.ADWHIRL, "Invalid AdSense client ID");
      adWhirlLayout.rollover();
      return;
    }
    if (TextUtils.isEmpty(googleAdSenseCompanyName)
        || TextUtils.isEmpty(googleAdSenseAppName)) {
      // Missing required parameters
      Log.w(AdWhirlUtil.ADWHIRL,
          "AdSense company name and app name are required parameters");
      adWhirlLayout.rollover();
      return;
    }

    ExtendedAdSenseSpec spec = new ExtendedAdSenseSpec(clientId);
    spec.setCompanyName(googleAdSenseCompanyName);
    spec.setAppName(googleAdSenseAppName);
    if (!TextUtils.isEmpty(googleAdSenseChannel)) {
      spec.setChannel(googleAdSenseChannel);
    }

    spec.setAdFormat(AdFormat.FORMAT_320x50);

    boolean testMode = AdWhirlTargeting.getTestMode();
    spec.setAdTestEnabled(testMode);

    adView = new GoogleAdView(adWhirlLayout.getContext());
    adView.setAdViewListener(this);

    Extra extra = adWhirlLayout.extra;
    spec.setColorBackground(rgbToHex(extra.bgRed, extra.bgGreen, extra.bgBlue));

    final AdWhirlTargeting.Gender gender = AdWhirlTargeting.getGender();
    spec.setGender(gender);

    final int age = AdWhirlTargeting.getAge();
    spec.setAge(age);

    final String keywords = AdWhirlTargeting.getKeywordSet() != null ? TextUtils
        .join(",", AdWhirlTargeting.getKeywordSet())
        : AdWhirlTargeting.getKeywords();
    if (!TextUtils.isEmpty(keywords)) {
      spec.setKeywords(keywords);
    }

    // According to AdSense guidelines, we cannot display an expandable ad in a
    // ListView or ScrollView
    boolean canExpand = true;
    ViewParent p = adWhirlLayout.getParent();
    if (p == null) {
      // Null parent may indicate that the ad is inside of a ListView header
      canExpand = false;
    } else {
      do {
        if (p instanceof ListView || p instanceof ScrollView) {
          canExpand = false;
          break;
        }
        p = p.getParent();
      } while (p != null);
    }

    if (canExpand && googleAdSenseExpandDirection != null) {
      try {
        ExpandDirection dir = ExpandDirection
            .valueOf(googleAdSenseExpandDirection);
        spec.setExpandDirection(dir);
      } catch (IllegalArgumentException e) {
        // If an invalid expand direction is passed, don't set the expand
        // direction
      }
    }

    // The GoogleAdView has to be in the view hierarchy to make a request
    adView.setVisibility(View.INVISIBLE);
    adWhirlLayout.addView(adView, new LayoutParams(LayoutParams.WRAP_CONTENT,
        LayoutParams.WRAP_CONTENT));

    adView.showAds(spec);
  }

  // This block contains the AdSense listeners
  /*******************************************************************/
  public void onStartFetchAd() {
  }

  public void onFinishFetchAd() {
    Log.d(AdWhirlUtil.ADWHIRL, "AdSense success");
    adView.setAdViewListener(null);

    AdWhirlLayout adWhirlLayout = adWhirlLayoutReference.get();
    if (adWhirlLayout == null) {
      return;
    }

    adWhirlLayout.removeView(adView);
    adView.setVisibility(View.VISIBLE);
    adWhirlLayout.adWhirlManager.resetRollover();
    adWhirlLayout.handler.post(new ViewAdRunnable(adWhirlLayout, adView));
    adWhirlLayout.rotateThreadedDelayed();
  }

  public void onClickAd() {
  }

  public void onAdFetchFailure() {
    Log.d(AdWhirlUtil.ADWHIRL, "AdSense failure");
    adView.setAdViewListener(null);

    AdWhirlLayout adWhirlLayout = adWhirlLayoutReference.get();
    if (adWhirlLayout == null) {
      return;
    }

    adWhirlLayout.removeView(adView);
    adWhirlLayout.rollover();
  }

  /*******************************************************************/
  // End of AdSense listeners

  private String rgbToHex(int r, int g, int b) {
    String rHex = channelValueToHex(r);
    String gHex = channelValueToHex(g);
    String bHex = channelValueToHex(b);

    if (rHex == null || gHex == null || bHex == null) {
      return null;
    }

    return new StringBuilder(rHex).append(gHex).append(bHex).toString();
  }

  private String channelValueToHex(int channelValue) {
    if (channelValue < 0 || channelValue > 255) {
      return null;
    }

    if (channelValue <= 15) {
      return "0" + Integer.toHexString(channelValue);
    } else {
      return Integer.toHexString(channelValue);
    }
  }

  // Targeting class to generate/set AdSense targeting codes.
  class ExtendedAdSenseSpec extends AdSenseSpec {
    public int ageCode = -1;
    public int genderCode = -1;

    public ExtendedAdSenseSpec(String clientId) {
      super(clientId);
    }

    public void setAge(int age) {
      if (age <= 0) {
        ageCode = -1;
      } else if (age <= 17) {
        ageCode = 1000;
      } else if (age <= 24) {
        ageCode = 1001;
      } else if (age <= 34) {
        ageCode = 1002;
      } else if (age <= 44) {
        ageCode = 1003;
      } else if (age <= 54) {
        ageCode = 1004;
      } else if (age <= 64) {
        ageCode = 1005;
      } else {
        ageCode = 1006;
      }
    }

    public void setGender(AdWhirlTargeting.Gender gender) {
      if (gender == AdWhirlTargeting.Gender.MALE) {
        genderCode = 1;
      } else if (gender == AdWhirlTargeting.Gender.FEMALE) {
        genderCode = 2;
      } else {
        genderCode = -1;
      }
    }

    @Override
    public List<Parameter> generateParameters(Context context) {
      List<Parameter> parameters = new ArrayList<Parameter>(super
          .generateParameters(context));

      if (ageCode != -1) {
        parameters.add(new Parameter("cust_age", Integer.toString(ageCode)));
      }
      if (genderCode != -1) {
        parameters.add(new Parameter("cust_gender", Integer
            .toString(genderCode)));
      }

      return parameters;
    }
  }
}
