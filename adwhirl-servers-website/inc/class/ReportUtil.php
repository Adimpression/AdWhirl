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

require_once('inc/class/SDB.php');
require_once('inc/class/Report.php');
require_once('inc/class/AppUtil.php');

class ReportUtil {
  public static function getReportsByAid($aid, $startDate, $endDate, $numCat=4) {

		return ReportUtil::getReportsByIdAndGroup('aid',$aid,'type',$startDate,$endDate,NetworkUtil::getNetworksByAid($aid),$numCat);
	}
	public static function getReportsByCid($cid, $startDate, $endDate, $numCat=4,$desiredPlatform) {
		$apps = array();
		$excludeApps = array();
		foreach (AppUtil::getAppsByUid($_SESSION['uid']) as $app) {
			if ($desiredPlatform==0 || $desiredPlatform==$app->platform) 
				$apps[$app->id] = $app->name;
			else
				$excludeApps[$app->id] = $app->name;
		}
    fb("getRbyCid");
		fb("apps",$apps);
		return ReportUtil::getReportsByIdAndGroup('nid',$cid,'aid',$startDate,$endDate,$apps,$numCat,$excludeApps);

  }
  public static function getReportsByIdAndGroup($keyName, $keyId, $groupBy, $startDate, $endDate, $cats, $numCat=4, $excludeApps = array()) {
		fb("getRByIdAndGroup");
		$aid = $keyId;
    $sdb = SDB::getInstance();
    $aaa = array($groupBy, 'clicks', 'impressions', 'dateTime');
		$nets = array();
		// foreach (NetworkUtil::getNetworksByAid($aid) as $type => $net) {
		// 	$nets[$type] = isset(Network::$NETWORKS[$type]) ? Network::$NETWORKS[$type]['name'] : 'Unknown';
		// }

    $sdb->select(Report::$SDBDomain, $aaa, "where `$keyName` = '$aid' and `dateTime` >= '$startDate' and `dateTime` <= '$endDate'");

    if(empty($aaa)) {
      return null;
    }

    $reports = array();
		$sum = array();
    foreach($aaa as $aa) {
      $dateTime = $aa['dateTime'];
      
      $type = $aa[$groupBy];

			if ($type == Network::NETWORK_TYPE_HOUSE) continue;
			if ($groupBy=='aid' && array_key_exists($type, $excludeApps)) continue;
      $clicks = $aa['clicks'];
      $impressions = $aa['impressions'];
      if (!isset($sum[$type])) {
				$sum[$type] = array();
				$sum[$type][$groupBy] = $type;
				$sum[$type]['clicks'] = $clicks;
				$sum[$type]['impressions'] = $impressions;	
			} else {
				$sum[$type]['clicks'] += $clicks;
				$sum[$type]['impressions'] += $impressions;	
			}
		}
		foreach ($cats as $type => $discard) {
			if (!array_key_exists($type,$sum)) {
				$sum[$type] = array();
				$sum[$type][$groupBy] = $type;
				$sum[$type]['clicks'] = 0;
				$sum[$type]['impressions'] = 0;	
			}
		}
		fb("cats",$cats);
		usort($sum, array( __CLASS__, 'cmp' ));
		fb('sorted',$sum);
		$types = array();
		if ($keyName=='aid')
			$types[Network::NETWORK_TYPE_HOUSE] = true;
		foreach ($sum as $idx => $aa) {
			$type = $aa[$groupBy];
			$types[$type] = true;
			if ($keyName=='aid')
				$nets[$type] = isset(Network::$NETWORKS[$type]) ? Network::$NETWORKS[$type]['name'] : 'Unknown';
			else
				$nets[$type] = array_key_exists($type, $cats) ? $cats[$type] : 'Unknown';
			if (count($types)>=$numCat) break;
		}
		$nets['others'] = 'Others';
		if ($keyName=='aid')
			$nets[Network::NETWORK_TYPE_HOUSE] = 'House Ad';
		fb("types",$types);
		fb('nets-ru',$nets);
    foreach($aaa as $aa) {
      $dateTime = $aa['dateTime'];
      
      $type = $aa[$groupBy];
			if ($groupBy=='aid' && array_key_exists($type, $excludeApps)) continue;


			if (!array_key_exists($type, $types)) {
				$type='others';
			}
      $clicks = $aa['clicks'];
      $impressions = $aa['impressions'];

      if(isset($reports[$dateTime][$type])) {
				$reports[$dateTime][$type]['clicks'] += $clicks;
				$reports[$dateTime][$type]['impressions'] += $impressions;
      }
      else {
				$reports[$dateTime][$type]['clicks'] = $clicks;
				$reports[$dateTime][$type]['impressions'] = $impressions;
      }
      
      if(isset($reports[$dateTime][0])) {
				$reports[$dateTime][0]['clicks'] += $clicks;
				$reports[$dateTime][0]['impressions'] += $impressions;
      }
      else {
				$reports[$dateTime][0] = array();
				$reports[$dateTime][0]['clicks'] = $clicks;
				$reports[$dateTime][0]['impressions'] = $impressions;
      }
    }
		$reports['nets'] = $nets;
  	$reports['sum'] = $sum;
    return $reports;
  }


  public static function cmp($a, $b)
	{
	  return $b['impressions']-$a['impressions'];
	}
  public static function getReportsByCidx($cid, $startDate, $endDate) {
    $sdb = SDB::getInstance();
    $aaa = array('clicks', 'impressions', 'dateTime');

    $sdb->select(Report::$SDBDomain, $aaa, "where `nid` = '$cid' and `dateTime` >= '$startDate' and `dateTime` <= '$endDate'");

    if(empty($aaa)) {
      return null;
    }

    $reports = array();
    foreach($aaa as $aa) {
      $dateTime = $aa['dateTime'];
      
      $clicks= $aa['clicks'];
      $impressions = $aa['impressions'];

      if(isset($reports[$dateTime])) {
	$reports[$dateTime]['clicks'] += $clicks;
	$reports[$dateTime]['impressions'] += $impressions;
      }
      else {
	$reports[$dateTime]['clicks'] = $clicks;
	$reports[$dateTime]['impressions'] = $impressions;
      }
    }
  
    return $reports;
  }

  public static function getNetworkReportsByUid($uid, $startDate, $endDate) {
    $sdb = SDB::getInstance();
    
    $apps = AppUtil::getAppsByUid($uid);
    
    $reports = array();
    foreach($apps as $app) {
      $aid = $app->id;

      $aaa = array($groupBy, 'clicks', 'impressions', 'dateTime');
      $sdb->select(Report::$SDBDomain, $aaa, "where `aid` = '$aid' and `dateTime` >= '$startDate' and `dateTime` <= '$endDate'");
      
      if(empty($aaa)) {
	continue;
      }
      
      foreach($aaa as $aa) {
	$dateTime = $aa['dateTime'];
	
	$type = $aa[$groupBy];
	$clicks = $aa['clicks'];
	$impressions = $aa['impressions'];

	$reports['types'][$type] = 1;
	
	if(isset($reports[$dateTime][$type])) {
	  $reports[$dateTime][$type]['clicks'] += $clicks;
	  $reports[$dateTime][$type]['impressions'] += $impressions;
	}
	else {
	  $reports[$dateTime][$type]['clicks'] = $clicks;
	  $reports[$dateTime][$type]['impressions'] = $impressions;
	}
	
	if(isset($reports[$dateTime][0])) {
	  $reports[$dateTime][0]['clicks'] += $clicks;
	  $reports[$dateTime][0]['impressions'] += $impressions;
	}
	else {
	  $reports[$dateTime][0] = array();
	  $reports[$dateTime][0]['clicks'] = $clicks;
	  $reports[$dateTime][0]['impressions'] = $impressions;
	}
      }
      
      return $reports;
    }
  }
}
