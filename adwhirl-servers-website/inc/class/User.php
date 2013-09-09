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

require_once('inc/class/SDBObject.php');
require_once('inc/class/AppUtil.php');

class User extends SDBObject {
  public static $SDBDomain = 'users';

  public static $PASSWORD_SALT = 'eJz4KL3i97';

  public $id;
  public $firstName;
  public $lastName;

  public $email;
  public $password;
  public $prefText;

  // Lazy loading
  private $pref;
  private $apps;
  private $houseAds;
  
  public static $SDBFields = array('email' => array(),
				   'firstName' => array(),
				   'lastName' => array(),
				   'password' => array(),
				   'prefText' => array());
  
  function get() {
    parent::get();
    $this->postGet();
  }
  function postGet() {
    if (empty($this->prefText)) {
      $this->pref = array();
    } else {
      $this->pref = unserialize($this->prefText);
    }    
  }
  function put() {
    $this->prePut();
    parent::put();
  }
  function prePut() {
    if (!empty($this->pref) && count($this->pref)>0) {
      $this->prefText = serialize($this->pref);
    }
      
  }
  public function getPref($key) {
    return isset($this->pref[$key])?$this->pref[$key]:'';
  }
  public function setPref($key, $value) {
    $this->pref[$key] = $value;
  }
  public function getSDBDomain() {
    return self::$SDBDomain;
  }
  
  public function getSDBFields() {
    return self::$SDBFields;
  }

  public function getApps() {
    if($this->apps == null) {
      $this->apps = AppUtil::getAppsByUid($this->id);
    }

    return $this->networks;
  }

  public function getHouseAds() {
    if($this->houseAds == null) {
      $this->houseAds = HouseAdUtil::getHouseAdsByUid($this->id);
    }

    return $this->houseAds;
  }
  public function delete() {
    // TODO - delete house ad associations

    $apps = $this->getApps();
    foreach($apps as $app) {
      $app->delete();
    }
    $houseAds = $this->getHouseAds();
    foreach($houseAds as $houseAd) {
      $houseAd->delete();
    }

    parent::delete();
  }
  public function getHashedPassword($password) {
    return md5($password.self::$PASSWORD_SALT);
  }
}
