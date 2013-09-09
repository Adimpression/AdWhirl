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

import java.lang.ref.WeakReference;
import java.lang.reflect.Constructor;
import java.lang.reflect.InvocationTargetException;

public abstract class AdWhirlAdapter {
  protected final WeakReference<AdWhirlLayout> adWhirlLayoutReference;
  protected Ration ration;

  public AdWhirlAdapter(AdWhirlLayout adWhirlLayout, Ration ration) {
    this.adWhirlLayoutReference = new WeakReference<AdWhirlLayout>(
        adWhirlLayout);
    this.ration = ration;
  }

  private static AdWhirlAdapter getAdapter(AdWhirlLayout adWhirlLayout,
      Ration ration) {
    try {
      switch (ration.type) {
        case AdWhirlUtil.NETWORK_TYPE_ADMOB:
          if (Class.forName("com.google.ads.AdView") != null) {
            return getNetworkAdapter("com.adwhirl.adapters.GoogleAdMobAdsAdapter",
                                     adWhirlLayout, ration);
          } else {
            return unknownAdNetwork(adWhirlLayout, ration);
          }

        case AdWhirlUtil.NETWORK_TYPE_INMOBI:
          if (Class.forName("com.inmobi.androidsdk.IMAdView")
              != null) {
            return getNetworkAdapter("com.adwhirl.adapters.InMobiAdapter",
                adWhirlLayout, ration);
          } else {
            return unknownAdNetwork(adWhirlLayout, ration);
          }

        case AdWhirlUtil.NETWORK_TYPE_QUATTRO:
          if (Class.forName("com.qwapi.adclient.android.view.QWAdView")
              != null) {
            return getNetworkAdapter("com.adwhirl.adapters.QuattroAdapter",
                adWhirlLayout, ration);
          } else {
            return unknownAdNetwork(adWhirlLayout, ration);
          }

        case AdWhirlUtil.NETWORK_TYPE_MILLENNIAL:
          if (Class.forName("com.millennialmedia.android.MMAdView") != null) {
            return getNetworkAdapter("com.adwhirl.adapters.MillennialAdapter",
                adWhirlLayout, ration);
          } else {
            return unknownAdNetwork(adWhirlLayout, ration);
          }
          
        case AdWhirlUtil.NETWORK_TYPE_ADSENSE:
          if (Class.forName("com.google.ads.GoogleAdView") != null) {
            return getNetworkAdapter("com.adwhirl.adapters.AdSenseAdapter",
                adWhirlLayout, ration);
          } else {
            return unknownAdNetwork(adWhirlLayout, ration);
          }
          
        case AdWhirlUtil.NETWORK_TYPE_ZESTADZ:
          if (Class.forName("com.zestadz.android.ZestADZAdView") != null) {
            return getNetworkAdapter("com.adwhirl.adapters.ZestAdzAdapter",
                adWhirlLayout, ration);
          } else {
            return unknownAdNetwork(adWhirlLayout, ration);
          }
          
        case AdWhirlUtil.NETWORK_TYPE_MDOTM:
          return getNetworkAdapter("com.adwhirl.adapters.MdotMAdapter",
              adWhirlLayout, ration);
          
        case AdWhirlUtil.NETWORK_TYPE_ONERIOT:
            return getNetworkAdapter("com.adwhirl.adapters.OneRiotAdapter",
                adWhirlLayout, ration);

        case AdWhirlUtil.NETWORK_TYPE_NEXAGE:
            return getNetworkAdapter("com.adwhirl.adapters.NexageAdapter",
                adWhirlLayout, ration);

        case AdWhirlUtil.NETWORK_TYPE_CUSTOM:
          return new CustomAdapter(adWhirlLayout, ration);

        case AdWhirlUtil.NETWORK_TYPE_GENERIC:
          return new GenericAdapter(adWhirlLayout, ration);

        case AdWhirlUtil.NETWORK_TYPE_EVENT:
          return new EventAdapter(adWhirlLayout, ration);

        default:
          return unknownAdNetwork(adWhirlLayout, ration);
      }
    } catch (ClassNotFoundException e) {
      return unknownAdNetwork(adWhirlLayout, ration);
    } catch (VerifyError e) {
      Log.e("AdWhirl", "Caught VerifyError", e);
      return unknownAdNetwork(adWhirlLayout, ration);
    }
  }

  private static AdWhirlAdapter getNetworkAdapter(String networkAdapter,
      AdWhirlLayout adWhirlLayout, Ration ration) {
    AdWhirlAdapter adWhirlAdapter = null;

    try {
      @SuppressWarnings("unchecked")
      Class<? extends AdWhirlAdapter> adapterClass = 
          (Class<? extends AdWhirlAdapter>) Class.forName(networkAdapter);

      Class<?>[] parameterTypes = new Class[2];
      parameterTypes[0] = AdWhirlLayout.class;
      parameterTypes[1] = Ration.class;

      Constructor<? extends AdWhirlAdapter> constructor = 
          adapterClass.getConstructor(parameterTypes);

      Object[] args = new Object[2];
      args[0] = adWhirlLayout;
      args[1] = ration;

      adWhirlAdapter = constructor.newInstance(args);
    } catch (ClassNotFoundException e) {
    } catch (SecurityException e) {
    } catch (NoSuchMethodException e) {
    } catch (InvocationTargetException e) {
    } catch (IllegalAccessException e) {
    } catch (InstantiationException e) {
    }

    return adWhirlAdapter;
  }

  private static AdWhirlAdapter unknownAdNetwork(AdWhirlLayout adWhirlLayout,
      Ration ration) {
    Log.w(AdWhirlUtil.ADWHIRL, "Unsupported ration type: " + ration.type);
    return null;
  }

  public static AdWhirlAdapter handle(AdWhirlLayout adWhirlLayout, Ration ration) throws
      Throwable {
    AdWhirlAdapter adapter = AdWhirlAdapter.getAdapter(adWhirlLayout, ration);
    if (adapter != null) {
      Log.d(AdWhirlUtil.ADWHIRL, "Valid adapter, calling handle()");
      adapter.handle();
    } else {
      throw new Exception("Invalid adapter");
    }
    return adapter;
  }

  public abstract void handle();
  
  // Added to tell adapter that it's view will be destroyed.
  public void willDestroy() {
    Log.d(AdWhirlUtil.ADWHIRL, "Generic adapter will get destroyed");
  }

  protected static String googleAdSenseCompanyName;
  protected static String googleAdSenseAppName;
  protected static String googleAdSenseChannel;
  protected static String googleAdSenseExpandDirection;

  public static void setGoogleAdSenseCompanyName(String name) {
    googleAdSenseCompanyName = name;
  }

  public static void setGoogleAdSenseAppName(String name) {
    googleAdSenseAppName = name;
  }

  public static void setGoogleAdSenseChannel(String channel) {
    googleAdSenseChannel = channel;
  }

  public static void setGoogleAdSenseExpandDirection(String direction) {
    googleAdSenseExpandDirection = direction;
  }
}
