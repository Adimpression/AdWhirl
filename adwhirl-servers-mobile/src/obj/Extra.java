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

package obj;

import java.awt.Color;

public class Extra {
    private int adsOn;
	
    private String fgColor = "FFFFFF";
    private int fg_red = 255;
    private int fg_green = 255;
    private int fg_blue = 255;
    private int fg_alpha = 1;
	
    private String bgColor = "000000";
    private int bg_red = 0;
    private int bg_green = 0;
    private int bg_blue = 0;
    private int bg_alpha = 1;

    private int cycleTime = 30;
    private int locationOn = 1;
    private int transition = 1;
    private int fullscreen_wait_interval = 60;
    private int fullscreen_max_ads = 2;
    private String metrics_url = "";
    private int metrics_flag = 0;
	
    public Extra() {}
	
    public void setFgColor(String fgColor) {
	this.fgColor = fgColor;
		
	try {
		int rgb = Integer.decode("#" + fgColor);
		Color c = new Color(rgb);
		this.fg_red = c.getRed();
		this.fg_green = c.getGreen();
		this.fg_blue = c.getBlue();
		this.fg_alpha = 1;
	}
	catch(NumberFormatException e) {
		// Do nothing, we already have defaults
	}
    }	
	
    public void setBgColor(String bgColor) {
	this.bgColor = bgColor;
		
	try {
	int rgb = Integer.decode("#" + bgColor);
	Color c = new Color(rgb);
	this.bg_red = c.getRed();
	this.bg_green = c.getGreen();
	this.bg_blue = c.getBlue();
	this.bg_alpha = 1;
	}
	catch(NumberFormatException e) {
		// Do nothing, we already have defaults
	}
    }

    public int getAdsOn() {
	return adsOn;
    }

    public int getBg_alpha() {
	return bg_alpha;
    }

    public int getBg_blue() {
	return bg_blue;
    }

    public int getBg_green() {
	return bg_green;
    }

    public int getBg_red() {
	return bg_red;
    }

    public String getBgColor() {
	return bgColor;
    }

    public int getCycleTime() {
	return cycleTime;
    }

    public int getFg_alpha() {
	return fg_alpha;
    }

    public int getFg_blue() {
	return fg_blue;
    }

    public int getFg_green() {
	return fg_green;
    }

    public int getFg_red() {
	return fg_red;
    }

    public String getFgColor() {
	return fgColor;
    }

    public int getFullscreen_max_ads() {
	return fullscreen_max_ads;
    }

    public int getFullscreen_wait_interval() {
	return fullscreen_wait_interval;
    }

    public int getLocationOn() {
	return locationOn;
    }

    public int getMetrics_flag() {
	return metrics_flag;
    }

    public String getMetrics_url() {
	return metrics_url;
    }

    public int getTransition() {
	return transition;
    }

    public void setAdsOn(int adsOn) {
	this.adsOn = adsOn;
    }

    public void setBg_alpha(int bg_alpha) {
	this.bg_alpha = bg_alpha;
    }

    public void setBg_blue(int bg_blue) {
	this.bg_blue = bg_blue;
    }

    public void setBg_green(int bg_green) {
	this.bg_green = bg_green;
    }

    public void setBg_red(int bg_red) {
	this.bg_red = bg_red;
    }

    public void setCycleTime(int cycleTime) {
	this.cycleTime = cycleTime;
    }

    public void setFg_alpha(int fg_alpha) {
	this.fg_alpha = fg_alpha;
    }

    public void setFg_blue(int fg_blue) {
	this.fg_blue = fg_blue;
    }

    public void setFg_green(int fg_green) {
	this.fg_green = fg_green;
    }

    public void setFg_red(int fg_red) {
	this.fg_red = fg_red;
    }

    public void setFullscreen_max_ads(int fullscreen_max_ads) {
	this.fullscreen_max_ads = fullscreen_max_ads;
    }

    public void setFullscreen_wait_interval(int fullscreen_wait_interval) {
	this.fullscreen_wait_interval = fullscreen_wait_interval;
    }

    public void setLocationOn(int locationOn) {
	this.locationOn = locationOn;
    }

    public void setMetrics_flag(int metrics_flag) {
	this.metrics_flag = metrics_flag;
    }

    public void setMetrics_url(String metrics_url) {
	this.metrics_url = metrics_url;
    }

    public void setTransition(int transition) {
	this.transition = transition;
    }
}
