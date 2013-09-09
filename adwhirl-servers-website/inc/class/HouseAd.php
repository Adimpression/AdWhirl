<?php
  /*
   -----------------------------------------------------------------------
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
   ------------------------------------------------------------------------
  */
?>
<?php

require_once('inc/class/AppHouseAdUtil.php');
require_once('inc/class/CacheUtil.php');

class HouseAd extends SDBObject {
  public $id;

  public $uid;
  public $name;
  public $description;
  public $type; // HouseAd Type (Image Only or Image & Text)
  public $link; // URL
  public $linkType; // (GoalType)
  public $launchType;
  public $imageLink;

  public $weight; // transient
  public $ahid; // transient
	
  public $apps;
  public $deleted = false;

  public $reports;
  public $totals;

  public static $SDBDomain = 'customs';
  public static $SDBDomainInvalid = 'customs_invalid';

  public static $SDBFields = array('uid' => array(),
				   'name' => array(),
				   'description' => array(),
				   'type' => array(),
				   'link' => array(),
				   'linkType' => array(),
				   'launchType' => array(),
				   'imageLink' => array());

  public function getSDBDomain() {
    return self::$SDBDomain;
  }
  
  public function isForAndroid() {
    return ($this->linkType!=6 && $this->linkType!=2);
  }
  public function isForiPhone() {
    return $this->linkType!=8;
  }
  public function getSDBFields() {
    return self::$SDBFields;
  }

  public function put() {
    parent::put();

    CacheUtil::invalidateCustom($this->id);
  }

  public function delete() {
    $appHouseAds = AppHouseAdUtil::getAppHouseAdsByCid($this->id);
    foreach($appHouseAds as $appHouseAd) {
      $appHouseAd->delete();
    }

    if(!empty($this->imageLink)) {
      if(extension_loaded('curl') && !@dl(PHP_SHLIB_SUFFIX == 'so' ? 'curl.so' : 'php_curl.dll')) {
        $s3 = new S3(HouseAd::$HOUSEAD_AWS_KEY, HouseAd::$HOUSEAD_AWS_SECRET);
        $s3->deleteObject(HouseAd::$HOUSEAD_BUCKET, basename($this->imageLink));
      }
    }

    parent::delete();

    CacheUtil::invalidateCustom($this->id);
  }

  public function getApps() {
    if($this->apps == null) {
      $this->apps = HouseAdUtil::getAppsByCid($this->id);
    }

    return $this->apps;
  }
  
  public static $HOUSEAD_BUCKET = 
  	 'CHANGEME';


  public static $HOUSEAD_BUCKET_PREFIX = 'http://s3.amazonaws.com/';

  public static $HOUSEAD_AWS_KEY = 
  	 'CHANGEME';


  public static $HOUSEAD_AWS_SECRET = 
  	 'CHANGEME';


  const HOUSEAD_TYPE_BANNER = 1;
  const HOUSEAD_TYPE_ICON = 2;
  
  public static $HOUSEAD_TYPES = array(HouseAd::HOUSEAD_TYPE_BANNER => "Banner",
				       HouseAd::HOUSEAD_TYPE_ICON => "Image and Text");
				
  public static $HOUSEAD_LINKTYPES = array(
					   HouseAd::HOUSEAD_LINKTYPE_WEBSITE => "Website",
					   HouseAd::HOUSEAD_LINKTYPE_APP => "iPhone App Store",
					   HouseAd::HOUSEAD_LINKTYPE_MARKET => "Android Market",
					   HouseAd::HOUSEAD_LINKTYPE_CALL => "Click to Call",
					   HouseAd::HOUSEAD_LINKTYPE_VIDEO => "Video",
					   HouseAd::HOUSEAD_LINKTYPE_AUDIO => "Audio",
					   HouseAd::HOUSEAD_LINKTYPE_ITUNES => "iTunes",
					   HouseAd::HOUSEAD_LINKTYPE_MAP => "Maps"
					   );
  
  const HOUSEAD_LINKTYPE_WEBSITE = 1;
  const HOUSEAD_LINKTYPE_APP = 2;
  const HOUSEAD_LINKTYPE_CALL = 3;
  const HOUSEAD_LINKTYPE_VIDEO = 4;
  const HOUSEAD_LINKTYPE_AUDIO = 5;
  const HOUSEAD_LINKTYPE_ITUNES = 6;
  const HOUSEAD_LINKTYPE_MAP = 7;
  const HOUSEAD_LINKTYPE_MARKET = 8;
  
  const HOUSEAD_LAUNCHTYPE_SAFARI = 1;
  const HOUSEAD_LAUNCHTYPE_CANVAS = 2;
}
