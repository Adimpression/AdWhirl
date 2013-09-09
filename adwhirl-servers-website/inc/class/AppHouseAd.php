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

require_once('inc/class/HouseAdUtil.php');
require_once('inc/class/NetworkUtil.php');
require_once('inc/class/CacheUtil.php');

class AppHouseAd extends SDBObject {
  public static $SDBDomain = 'app_customs';

  public static $SDBFields = array('aid' => array(),
					    'cid' => array(),
					    'weight' => array());
	
  public $id;

  public $aid;
  public $cid;
  public $weight;
  
  public function put() {
    parent::put();
    
    CacheUtil::invalidateApp($this->aid);
  }
  
  public function delete() {
    parent::delete();

    CacheUtil::invalidateApp($this->aid);
  }

  public function getSDBDomain() {
    return self::$SDBDomain;
  }
  
  public function getSDBFields() {
    return self::$SDBFields;
  }
}
