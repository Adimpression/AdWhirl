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

require_once('inc/class/App.php');
require_once('inc/class/HouseAd.php');

class CacheUtil {
  public static function invalidateApp($aid) {
    $sdb = SDB::getInstance();

    $aa = array('dateTime' => gmdate('Y-m-d, H:i:s'));
    $sdb->put(App::$SDBDomainInvalid, $aid, $aa, true);
  }

  public static function invalidateCustom($nid) {
    $sdb = SDB::getInstance();

    $aa = array('dateTime' => gmdate('Y-m-d, H:i:s'));
    $sdb->put(HouseAd::$SDBDomainInvalid, $nid, $aa, true);
  }
}
