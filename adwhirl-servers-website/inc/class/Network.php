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

require_once('inc/class/CacheUtil.php');

class Network extends SDBObject {
  public static $SDBDomain = 'networks';

  const NETWORK_TYPE_ADMOB = 1;
  const NETWORK_TYPE_JUMPTAP = 2;
  const NETWORK_TYPE_VIDEOEGG = 3;
  const NETWORK_TYPE_MILLENIAL = 6;
  const NETWORK_TYPE_GREYSTRIPE = 7;
  const NETWORK_TYPE_QUATTRO = 8;
  const NETWORK_TYPE_HOUSE = 9;
  const NETWORK_TYPE_MOBCLIX = 11;
  const NETWORK_TYPE_MDOTM = 12;
  const NETWORK_TYPE_ADSENSE = 14;
  const NETWORK_TYPE_GENERIC = 16; 
  const NETWORK_TYPE_EVENT = 17;
  const NETWORK_TYPE_INMOBI = 18;
  const NETWORK_TYPE_IAD = 19;
  const NETWORK_TYPE_ZESTADZ = 20;

  const MAX_PRIORITY = 99;

  // Alphabetical
  public static $NETWORKS = array(Network::NETWORK_TYPE_ADMOB => array('name' => 'AdMob',
								       'Website' => 'http://www.admob.com', 'keyinfo' => array('PublisherID'), 'iphone' => true, 'android' => true),
				  Network::NETWORK_TYPE_ADSENSE => array('name' => 'Google AdSense',
									 'Website' => 'http://www.google.com/ads/mobileapps/','keyinfo' => array('Publisher ID'), 'iphone' => true, 'android' => true),
				  Network::NETWORK_TYPE_GREYSTRIPE => array('name' => 'Greystripe', 
									    'Website' => 'http://www.greystripe.com/', 'keyinfo' => array('API Key'), 'iphone' => true, 'android' => false),
				  Network::NETWORK_TYPE_IAD =>  array('name' => 'iAd', 'Website' => 
								      'https://developer.apple.com/iphone/prerelease/library/navigation/index.html#section=Frameworks&topic=iAd', 
								      'keyinfo' => array('Apple ID'), 'iphone' => true, 'android' => false),
				  Network::NETWORK_TYPE_INMOBI => array('name' => 'InMobi (legacy only)', 'keyinfo' => array('API Key'), 'iphone' => true, 'android' => false),
				  Network::NETWORK_TYPE_JUMPTAP => array('name' => 'Jumptap',
									 'Website' => 'http://www.jumptap.com','keyinfo' => array('PublisherID', 'SiteID', 'SpotID'), 'iphone' => true, 'android' => false),
				  Network::NETWORK_TYPE_MDOTM => array('name' => 'MdotM',
								       'Website' => 'http://www.mdotm.com/', 'keyinfo' => array('API Key'), 'iphone' => true, 'android' => false),
				  Network::NETWORK_TYPE_MILLENIAL => array('name' => 'Millennial Media', 'Website' => 'http://www.millennialmedia.com',
									   'keyinfo' => array('API Key'), 'iphone' => true, 'android' => true),
				  Network::NETWORK_TYPE_MOBCLIX => array('name' => 'MobClix (legacy only)', 'Website' => 'http://www.mobclix.com',
									 'keyinfo' => array('API Key'), 'iphone' => true, 'android' => false),
				  Network::NETWORK_TYPE_QUATTRO => array('name' => 'Quattro', 'Website' => 'http://www.quattrowireless.com/affiliates/adwhirl?promocode=aff-adwhirl-ja',
									 'keyinfo' => array('SiteID', 'PublisherID'), 'iphone' => true, 'android' => true),
				  Network::NETWORK_TYPE_VIDEOEGG => array('name' => 'VideoEgg',	'Website' => 'http://www.videoegg.com/',
									  'keyinfo' => array('PartnerID', 'SiteID'), 'iphone' => true, 'android' => false),
				  Network::NETWORK_TYPE_ZESTADZ => array('name' => 'ZestADZ', 'keyinfo' => array('Site ID'), 'iphone' => true, 'android' => true),
				  Network::NETWORK_TYPE_HOUSE => array('name' => 'House Ads', 'keyinfo' => array(), 'iphone' => true, 'android' => true),
				  Network::NETWORK_TYPE_GENERIC => array('name' => 'Generic Notification', 'keyinfo' => array(), 'iphone' => true, 'android' => true),
				  Network::NETWORK_TYPE_EVENT => array('name' => 'Custom Event', 'keyinfo' => array('Name', 'Function Name'), 'iphone' => true, 'android' => true));

  const KEY_SPLIT = '|;|';

  public $id;
  public $aid;

  public $type;

  // Key is only used internally
  public $key;

  // Use keys for display on website
  public $keys;

  public $priority;
  public $weight;
  public $adsOn;

  public static $SDBFields = array('aid' => array(),
				   'type' => array(),
				   'key' => array(),
				   'priority' => array(),
				   'weight' => array(),
				   'adsOn' => array());

  function getSDBDomain() {
    return self::$SDBDomain;
  }

  function getSDBFields() {
    return self::$SDBFields;
  }

  function get() {
    parent::get();
    $this->postGet();
  }

  function postGet() {
    $this->keys = explode(Network::KEY_SPLIT, $this->key);
  }

  function put() {
    $this->prePut();
    parent::put();

    CacheUtil::invalidateApp($this->aid);
  }
  
  function delete() {
    parent::delete();

    CacheUtil::invalidateApp($this->aid);
  }

  function prePut() {
    if (!empty($this->keys))
      $this->key = implode(Network::KEY_SPLIT, $this->keys);
  }
}
