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

public class CustomAd {
    private String nid = "";
    private int type = 2;
    private String imageLink = "";
    private String link = "";
    private String name = "";
    private String description = "";
    private String linkType = "";
    private String launchType = "";

    public CustomAd(String nid) {
	this.nid = nid;
    }
	
    public String getDescription() {
	return description;
    }
    public String getImageLink() {
	return imageLink;
    }
    public String getLink() {
	return link;
    }
    public String getLinkType() {
	return linkType;
    }
    public String getLaunchType() {
	return launchType;
    }
    public String getName() {
	return name;
    }
    public String getNid() {
	return nid;
    }
    public int getType() {
	return type;
    }
    
    public void setDescription(String description) {
	this.description = description;
    }
    public void setImageLink(String imageLink) {
	this.imageLink = imageLink;
    }
    public void setLink(String link) {
	this.link = link;
    }
    public void setLinkType(String linkType) {
	this.linkType = linkType;
    }
    public void setLaunchType(String launchType) {
	this.launchType = launchType;
    }
    public void setName(String name) {
	this.name = name;
    }
    public void setNid(String nid) {
	this.nid = nid;
    }
    public void setType(int type) {
	this.type = type;
    }
}
