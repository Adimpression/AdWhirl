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
require_once('inc/class/AppHouseAd.php');
require_once('inc/class/S3.php');

class AppHouseAdUtil {
  public static $SDBDomain = 'app_customs';

  public static $SDBFields = array('aid' => array(),
					    'cid' => array(),
					    'weight' => array());
					
	public static function addRemoveAppHouseAds($cid, $aids_of_apps_to_add, $ahids_of_appHouseAds_to_delete) {
		fb("AppHouseAdUtil","addRemoveAppHouseAds");
		$ret = array();
		if (isset($aids_of_apps_to_add) && isset($cid)) {
			foreach($aids_of_apps_to_add as $aid) {
				fb("Adding",$aid);
				$sum = 0;
				$appHouseAds = AppHouseAdUtil::getAppHouseAdsByAid($aid);
				foreach ($appHouseAds as $appHouseAd) {
					$sum += $appHouseAd->weight;
				}
				$appHouseAd = new AppHouseAd();
				$appHouseAd->id = SDB::uuid();
				$appHouseAd->cid = $cid;
				$appHouseAd->aid = $aid;
				$appHouseAd->weight = 100-$sum; // if the app has no ads then 100% otherwise 0%
				$appHouseAd->put();
				fb('new_appHouseAd', $appHouseAd);
				$app = new App($aid);				
				$ret[$aid] = $app->adjustHouseAdNetwork($appHouseAd->id, null);
			}
		}
		fb("appHouseAdsToDelete", $ahids_of_appHouseAds_to_delete);
		if (isset($ahids_of_appHouseAds_to_delete)) {
			foreach($ahids_of_appHouseAds_to_delete as $ahid) {
				fb("Removing",$ahid);
				$appHouseAd = new AppHouseAd($ahid);
				$aid = $appHouseAd->aid;
				$removed_weight = floatval($appHouseAd->weight);
				$appHouseAd->delete();
				$app = new App($aid);
				$ret[$aid] = $app->adjustHouseAdNetwork(null, $ahid);				
				$appHouseAds = AppHouseAdUtil::getAppHouseAdsByAid($aid);
				fb("apphouseAds",$appHouseAds);
				$non_zero_appHouseAds = array();
				$total = 0;
				foreach ($appHouseAds as $key=>$appHouseAd) {
					if ($appHouseAd->id==null) continue;	
					if ($appHouseAd->id==$ahid) {
						unset($appHouseAds[$key]);
						continue;
					}
					if ($appHouseAd->weight>0) {
					  $total += intval($appHouseAd->weight);
						$non_zero_appHouseAds[] = $appHouseAd;
					}
				}
				fb("non_zero",$non_zero_appHouseAds);
				if (count($non_zero_appHouseAds)>0) {
					$appHouseAds_to_increase = $non_zero_appHouseAds;
				} else { // no non_zero
					$appHouseAds_to_increase = $appHouseAds;
				}
				if (count($appHouseAds_to_increase)==0) continue;
        		fb("non_zero",$non_zero_appHouseAds);
				$last = end($appHouseAds_to_increase);
				$sum = 0;
				fb("increase-rem weight", $removed_weight);
				fb("total",$total);
				$inc = $removed_weight/count($appHouseAds_to_increase);
				fb("inc",$inc);
				fb('count',count($appHouseAds_to_increase));
				foreach ($appHouseAds_to_increase as $appHouseAd) {
					fb("aha",$appHouseAd);
					if ($appHouseAd!=$last) {
						$weight = intval($appHouseAd->weight);
						fb("old_weight",$weight);
						$weight = intval($inc+$weight);
						fb("new_weight",$weight);
						$sum += $weight;
						fb("sum",$sum);
						$appHouseAd->weight = strval($weight);
					} else {
						$appHouseAd->weight = strval(100-$sum);
					}
					$appHouseAd->put();
				}
			}		
		}
		return $ret;
	}
  public static function getAppHouseAdsByAid($aid) {
    $sdb = SDB::getInstance();
    $domain = self::$SDBDomain;

    $aaa = array();
    foreach(self::$SDBFields as $field => $meta) {
      $aaa[] = $field;
    }
    
    $where = "where `aid` = '$aid'";
    if(!$sdb->select($domain, $aaa, $where)) {
      return null;
    }
    $appHouseAds = array();
    foreach($aaa as $aa) {
      $id = $aa['id'];
      $appHouseAd = new AppHouseAd();
      $appHouseAd->id = $aa['id'];
      foreach(self::$SDBFields as $field => $meta) {
				if (array_key_exists ($field,$aa)) $appHouseAd->$field = $aa[$field];
      }
      if($appHouseAd != null) {
	      $appHouseAds[] = $appHouseAd;
      }
    }
    return $appHouseAds;
  }

  public static function getAppHouseAdsByCid($cid) {
	    $sdb = SDB::getInstance();
	    $domain = self::$SDBDomain;

	    $aaa = array();
	    foreach(self::$SDBFields as $field => $meta) {
	      $aaa[] = $field;
	    }

	    $where = "where `cid` = '$cid'";
	    if(!$sdb->select($domain, $aaa, $where)) {
	      return null;
	    }
	    $appHouseAds = array();
	    foreach($aaa as $aa) {
	      $id = $aa['id'];
	      $appHouseAd = new AppHouseAd($id);

	      if($appHouseAd != null) {
		$appHouseAds[] = $appHouseAd;
	      }
	    }
	    return $appHouseAds;
  }
}
