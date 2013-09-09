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
require_once('inc/class/S3.php');

class HouseAdUtil {
  public static $SDBDomain_JOIN_AID = 'app_customs';

  public static $SDBFields_JOIN_AID = array('aid' => array(),
					    'cid' => array(),
					    'weight' => array());

  public static function getHouseAdsByUid($uid, $showAliveOnly=true) {
    $sdb = SDB::getInstance();
    $domain = HouseAd::$SDBDomain;

    $aaa = array();
    foreach(HouseAd::$SDBFields as $field => $meta) {
      $aaa[] = $field;
    }
    if (!$showAliveOnly) {
      $aaa[] = 'deleted';
    }
    
    $where = "where `uid` = '$uid'";
    if(!$sdb->select($domain, $aaa, $where, $showAliveOnly)) {
      return null;
    }

    $houseAds = array();
    foreach($aaa as $aa) {
      $houseAd = new HouseAd();
      foreach(HouseAd::$SDBFields as $field => $meta) {
				if (array_key_exists ($field,$aa)) $houseAd->$field = $aa[$field];
      }
      $houseAd->id = $aa['id'];
      if (!$showAliveOnly) {
        $houseAd->deleted = isset($aa['deleted']) ? true : false;
      }
      $houseAds[] = $houseAd;
    }
    return $houseAds;
  }
  public static function getHouseAdsByAid($aid, $allHouseAds = null) {
    $sdb = SDB::getInstance();
    $domain = self::$SDBDomain_JOIN_AID;

    $aaa = array();
    foreach(self::$SDBFields_JOIN_AID as $field => $meta) {
      $aaa[] = $field;
    }
    
    $where = "where `aid` = '$aid'";
    if(!$sdb->select($domain, $aaa, $where)) {
      return null;
    }
    $houseAds = array();
    if (empty($allHouseAds)) {
        foreach($aaa as $aa) {
          $cid = $aa['cid'];

          $houseAd = new HouseAd($cid);
          if($houseAd != null && $houseAd->id!=null) {
    	$houseAds[] = $houseAd;
          }
        }      
    } else {
      $cids = array();
      foreach($aaa as $aa) {
        $cids[$aa['cid']]=true;
      }
      foreach($allHouseAds as $houseAd) {
        if (array_key_exists($houseAd->id, $cids)) {
          $houseAds[] = $houseAd;
        }
      }
    }
    return $houseAds;
  }

  public static function getAppsByCid($cid) {
    $sdb = SDB::getInstance();
    $domain = self::$SDBDomain_JOIN_AID;

    $aaa = array();
    foreach(self::$SDBFields_JOIN_AID as $field => $meta) {
      $aaa[] = $field;
    }
    
    $where = "where `cid` = '$cid'";
    if(!$sdb->select($domain, $aaa, $where)) {
      return null;
    }

    $apps = array();
    foreach($aaa as $aa) {
      $aid = $aa['aid'];
      $weight = $aa['weight'];

      $app = new App($aid);

      if($app != null) {
	$apps[] = $app;
      }
    }

    return $apps;
  }

  public static function storeHouseAdImage($type) {
    if($type == HouseAd::HOUSEAD_TYPE_ICON) {
      $scaledWidth = 38;
      $scaledHeight = 38;
    }
    elseif($type == HouseAd::HOUSEAD_TYPE_BANNER) {
      $scaledWidth = 320;
      $scaledHeight = 50;
    }
    else {
      return '';
    }
    
    $uploadedFile = isset($_FILES['image']['tmp_name']) ? $_FILES['image']['tmp_name'] : null;

    // TODO - Check file size
    
    if(isset($uploadedFile)) {
      if($_FILES["image"]["type"]=="image/jpg" || $_FILES["image"]["type"]=="image/jpeg") {
	$src = imagecreatefromjpeg($uploadedFile);
	$imgType = 'jpg';
      }
      elseif($_FILES["image"]["type"]=="image/gif") {
	$src = imagecreatefromgif($uploadedFile);
	$imgType = 'gif';
      }
      elseif($_FILES["image"]["type"]=="image/png") {
	$src = imagecreatefrompng($uploadedFile);
	$imgType = 'png';
      }
      else {
	return '';
      }
      
      list($width, $height) = getimagesize($uploadedFile);
      $tmp=imagecreatetruecolor($scaledWidth, $scaledHeight);
      imagecopyresampled($tmp, $src, 0, 0, 0, 0, $scaledWidth, $scaledHeight, $width, $height);
      
      $randToken = md5(uniqid());
      $filename = "../www.adwhirl.com/imagesTemp/".$randToken.'.jpg';
      
      imagejpeg($tmp, $filename, 100);
      
      imagedestroy($src);
      imagedestroy($tmp);
      
      $uploadFile = dirname(__FILE__).'/../'.$filename;
      $bucketName = HouseAd::$HOUSEAD_BUCKET;
      
      if(!file_exists($uploadFile) || !is_file($uploadFile)) {
	return '';
      }
      
      if(!extension_loaded('curl') && !@dl(PHP_SHLIB_SUFFIX == 'so' ? 'curl.so' : 'php_curl.dll')) {
	return '';
      }
      
      $s3 = new S3(HouseAd::$HOUSEAD_AWS_KEY, HouseAd::$HOUSEAD_AWS_SECRET);
      if ($s3->putObjectFile($uploadFile, $bucketName, baseName($uploadFile), S3::ACL_PUBLIC_READ)) {
	if(isset($oldImageLink)) {
	  $s3->deleteObject($bucketName, basename($oldImageLink));
	}
	
	$imageLink = HouseAd::$HOUSEAD_BUCKET_PREFIX.$bucketName.'/'.basename($uploadFile);
      } 
      else {
	return '';
      }
    }
    else {
      return '';
    }

    return $imageLink;
  }

  public static function getFixedLink($link, $linkType) {
    switch($linkType) {
    case HouseAd::HOUSEAD_LINKTYPE_WEBSITE:
	// Make sure the link starts with "<scheme>://" (e.g. 'http://', 'myapp://')
      if(preg_match('@^([\w-]+://).*@', $link) === 0) {
	return $link = 'http://'.$link;
      }
      break;
    case HouseAd::HOUSEAD_LINKTYPE_CALL:
      if(!(stripos($link, 'tel:') === 0)) {
	$link = 'tel:'.$link;
      }
      break;
    case HouseAd::HOUSEAD_LINKTYPE_MAP:
      if(!(stripos($link, 'http') === 0)) {
	$link = str_replace(' ', '+', $link);
	$link = 'http://maps.google.com/maps?q='.$link;
      }
      break;
    default:
      break;
    }

    return $link;
  }

  public static function getLaunchType($linkType) {
    switch($linkType) {
    case HouseAd::HOUSEAD_LINKTYPE_MARKET:
    case HouseAd::HOUSEAD_LINKTYPE_WEBSITE:
      return HouseAd::HOUSEAD_LAUNCHTYPE_CANVAS;
      break;
    default:
      return HouseAd::HOUSEAD_LAUNCHTYPE_SAFARI;
      break;
    }
  }
  
  
}
