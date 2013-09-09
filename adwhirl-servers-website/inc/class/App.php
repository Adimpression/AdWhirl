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
require_once('inc/class/HouseAdUtil.php');
require_once('inc/class/NetworkUtil.php');
require_once('inc/class/CacheUtil.php');

class App extends SDBObject {
  public static $SDBDomain = 'apps';
  public static $SDBDomainInvalid = 'apps_invalid';

  const ANIMATION_NONE = 0;
  const ANIMATION_FLIP_FROM_LEFT = 1;
  const ANIMATION_FLIP_FROM_RIGHT = 2;
  const ANIMATION_CURL_UP = 3;
  const ANIMATION_CURL_DOWN = 4;
  const ANIMATION_SLIDE_FROM_LEFT = 5;
  const ANIMATION_SLIDE_FROM_RIGHT = 6;
  const ANIMATION_FADE_IN = 7;
  const ANIMATION_RANDOM = 8;

  public $id;

  public $uid;
  public $name;
  public $platform;
  public $storeUrl;
  public $adsOn;

  public $cycleTime=30;
  public $fgColor="FFFFFF";
  public $bgColor="000000";
  public $transition=App::ANIMATION_RANDOM;
  public $locationOn=0;

  public $reports;
  public $totals;
  public $deleted=false;

  public $ahid; // transient

  // Lazy loading
  private $networks;
  private $houseAds;
  
  public static $SDBFields = array('uid' => array(),
				   'name' => array(),
				   'platform' => array(),
				   'storeUrl' => array(),
				   'adsOn' => array(),
				   'cycleTime' => array(),
				   'fgColor' => array(),
				   'bgColor' => array(),
				   'transition' => array(),
				   'locationOn' => array());
  
  public function getSDBDomain() {
    return self::$SDBDomain;
  }
  
  public function getSDBFields() {
    return self::$SDBFields;
  }

  function __construct($id = null) {
    parent::__construct($id);

    if($id != null) {
      $uid = isset($_SESSION['uid']) ? $_SESSION['uid'] : null;
      if($uid == null || $uid != $this->uid) {
	die;
      }
    }
  }

  public function put() {
    $uid = isset($_SESSION['uid']) ? $_SESSION['uid'] : null;
    if($uid == null || $uid != $this->uid) {
      die;
    }

    parent::put();

    CacheUtil::invalidateApp($this->id);
  }

  public function delete() {
    $appHouseAds = AppHouseAdUtil::getAppHouseAdsByAid($this->id);
    foreach($appHouseAds as $appHouseAd) {
      $appHouseAd->delete();
    }

    $networks = $this->getNetworks();
    foreach($networks as $network) {
      $network->delete();
    }

    parent::delete();

    CacheUtil::invalidateApp($this->id);
  }
  
  public static function setWeights($networks) {
    $sum = 0;
    $on = array();
    foreach ($networks as $network) {
        if ($network->adsOn) {
            $on[] = $network;
            $sum += intval($network->weight);            
        }
    }
    fb("ons",$on);
    if ($sum<100) {
      $removed_weight = 100-$sum;
      $non_zero_networks = array();
      foreach ($on as $network) {
        if (intval($network->weight)>0) {
          $non_zero_networks[] = $network;
        }
      }
      fb("non_zero_networks",$non_zero_networks);
      if (count($non_zero_networks)>0) {
        $networks_to_increase = $non_zero_networks;
      } else { // no non_zero
        $networks_to_increase = $on;
      }

      $last = end($networks_to_increase);
      $sum = 0;
      fb("networks_to_increase",$networks_to_increase);
      foreach ($networks_to_increase as $network) {
        if ($network!=$last) {
          $weight = intval($network->weight);
          $weight += $removed_weight/count($networks_to_increase);
          $weight = intval($weight);
          $sum += $weight;
          $network->weight = strval($weight);
        } else {
          $network->weight = strval(100-$sum);
          $sum = 100;
        }
        $network->put();
      }				      
    }        
    return $sum;
  }

  public function adjustHouseAdNetwork($add_ahid, $del_ahid) {
	$retVal = "doNothing";	
    fb("adjusthouseAdNet","start");
    $networks = $this->getNetworks();
    if($networks != null) {
      foreach ($networks as $type => $network) {
	      if ($type==Network::NETWORK_TYPE_HOUSE) $houseAdNet = $network;
      }
    }
    
    $appHouseAds = AppHouseAdUtil::getAppHouseAdsByAid($this->id);
    $count = count($appHouseAds);
    if ($count==0 && $add_ahid!=null) {
        $count++;
    }
    else if ($count==1 && $appHouseAds['0']->id==$del_ahid) {
        $count--;
    }
    fb("appHouseAds", $appHouseAds);
    fb($count,'count');
    fb(isset($houseAdNet),'hasHouseAdNet');		
    if ($count>0 && !isset($houseAdNet)) { // add houseAdNet
      fb("Should Add");
      $network = new Network();
      $network->id = SDB::uuid();
      $network->aid = $this->id;
      $network->type = Network::NETWORK_TYPE_HOUSE;
      $network->adsOn = 0;
      $network->weight = 0;
      $network->priority = 99;
      $network->keys = array("_CUSTOMS_");
      $network->put();
      $retVal = "add";				
    }
    else if ($count==0 && isset($houseAdNet)) {			// should remove or turn off
      $hasHouseAds = count(HouseAdUtil::getHouseAdsByUid($_SESSION['uid']))>0;
      if ($hasHouseAds) {
          fb("should turn off");
          $houseAdNet->adsOn = 0;
          $houseAdNet->put();
          $retVal = "turnOff";
      } else {
          fb("Should Remove",$houseAdNet);
          $houseAdNet->delete();
          unset($networks['9']);     
          $retVal = "delete";     
      }
      App::setWeights($networks);
      App::setPriorities($networks);
    }
    fb("adjustHouseAdNet","end");
    return $retVal;
  }
	  
  public static function setPriorities($networks) {
    $on = array();
    $i = 0;
    foreach($networks as $network) {
      if ($network->adsOn=="1") {
	      $on[$network->priority*1000+$i] = $network;
      }	else {
        if ($network->priority!=Network::MAX_PRIORITY) {
  	      $network->priority = Network::MAX_PRIORITY;
  	      $network->put();          
        }
      }		
      $i++;
    }
    ksort($on);
    $count = 0;
    foreach ($on as $network) {
      $count++;
      if ($count!=$network->priority) {
        $network->priority = $count;
        $network->put();        
      }
    }    
  }
  public function getNetworks() {
    if($this->networks == null) {
      $this->networks = NetworkUtil::getNetworksByAid($this->id);
    }
    return $this->networks;
  }

  public function getNetworksIndexNid() {
    if($this->networks == null) {
      $this->networks = NetworkUtil::getNetworksByAidIndexNid($this->id);
    }
    return $this->networks;
  }

  public function getActiveNetworksCount() {
    $networks = $this->getNetworks();
    $count = 0;
    foreach($networks as $network) {
      if($network->adsOn == '1') {
	$count++;
	fb($network,$count);
      }
    }

    return $count;
  }

  public function getHouseAds() {
    if($this->houseAds == null) {
      $this->houseAds = HouseAdUtil::getHouseAdsByAid($this->id);
    }

    return $this->houseAds;
  }
}
