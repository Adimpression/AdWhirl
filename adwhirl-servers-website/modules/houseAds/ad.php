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

require_once('modules/houseAds/houseAdsBase.php');

class ad extends houseAdsBase {
  protected $houseAd;

  public function __default() {}
  
  public function create() {
    $this->jsFiles[] = "/js/jquery.validate.min.js";
		// $this->jsFiles[] = "/js/query.simplemodal-1.3.5.min.js";
    $this->subtitle = "Create New House Ad";
    $apps = AppUtil::getAppsByUid($this->user->id);
    $this->smarty->assign('apps', $apps);
		
    $hasiPhoneApp = false;
    $hasAndroidApp = false;
    foreach ($apps as $app) {
      $hasiPhoneApp |= ($app->platform==1);
      $hasAndroidApp |= ($app->platform==2);
    }
    $this->smarty->assign('hasNoiPhoneApp',!$hasiPhoneApp);
    $this->smarty->assign('hasNoAndroidApp',!$hasAndroidApp);
    $this->smarty->assign('returnPage',$_SERVER['HTTP_REFERER']);
    $this->breadcrumbs[] = array('text'=>"Create",'link'=>'houseAds/ad/create');    
    $this->styleSheets[] = "/css/preview.css";
    $this->jsFiles[] = "/js/ajaxupload.js";
    $this->smarty->assign('linkTypeOptions',HouseAd::$HOUSEAD_LINKTYPES);
    $this->smarty->assign('typeOptions',HouseAd::$HOUSEAD_TYPES);

    if (isset($_REQUEST['aid'])) {
      $this->smarty->assign('createOrEdit','createForApp');
      $this->smarty->assign('aid',$_REQUEST['aid']);
    } else {
      $this->smarty->assign('createOrEdit','create');
    }
		

    return $this->smarty->fetch('../tpl/www/houseAds/create.tpl');
  }

  public function createSubmit() {
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $type = isset($_POST['type']) ? $_POST['type'] : HouseAd::HOUSEAD_TYPE_ICON;
    $link = isset($_POST['link']) ? $_POST['link'] : '';
    $linkType = isset($_POST['linkType']) ? $_POST['linkType'] : HouseAd::HOUSEAD_LINKTYPE_WEBSITE;
    $imageLink = isset($_POST['imageLink']) ? $_POST['imageLink'] : '';
    $houseAd = new HouseAd();
    $houseAd->id = SDB::uuid();
    $houseAd->uid = $_SESSION['uid'];
    $houseAd->name = $name;
    $houseAd->description = $description;
    $houseAd->type = $type;
    $houseAd->link = HouseAdUtil::getFixedLink($link, $linkType);
    $houseAd->linkType = $linkType;
    $houseAd->launchType = HouseAdUtil::getLaunchType($linkType);
    $houseAd->imageLink = $imageLink;

    $result = $houseAd->put();

    $aids_of_apps_to_add = isset($_POST['apps']) ? $_POST['apps'] : null;
		
    AppHouseAdUtil::addRemoveAppHouseAds($houseAd->id, $aids_of_apps_to_add, array());
		
    fb($houseAd);
    $this->redirect($_REQUEST['returnPage'] . '?&n_cid=' . $houseAd->id . '&n_name=' . $houseAd->name . '&n_type=' . $houseAd->type . '&n_linkType=' . $houseAd->linkType);
  }

  public function uploadImage() {
    $this->printHeader = false;
    $this->printFooter = false;
    $type = isset($_POST['type']) ? $_POST['type'] : HouseAd::HOUSEAD_TYPE_ICON;
    return HouseAdUtil::storeHouseAdImage($type);
  }
  
  public function edit() {
		$this->jsFiles[] = "/js/jqsm135.js";
	
    $this->jsFiles[] = "/js/jquery.validate.min.js";
	
    $this->needsHouseAd();
    $this->jsFiles[] = "/js/ajaxupload.js";
    $this->smarty->assign('returnPage',isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null);

    $apps = AppUtil::getAppsByUid($this->user->id);
    $appHouseAds = array();
    foreach (AppHouseAdUtil::getAppHouseAdsByCid($this->houseAd->id) as $appHouseAd) {
      $appHouseAds[$appHouseAd->aid] = $appHouseAd;
    }
    foreach ($apps as $app) {
      if (array_key_exists($app->id, $appHouseAds)) {
	$app->ahid = $appHouseAds[$app->id]->id;
      }
    }
    $this->smarty->assign('apps', $apps);
    $hasiPhoneApp = false;
    $hasAndroidApp = false;
    foreach ($apps as $app) {
      $hasiPhoneApp |= ($app->platform==1);
      $hasAndroidApp |= ($app->platform==2);
    }
    $this->smarty->assign('hasNoiPhoneApp',!$hasiPhoneApp);
    $this->smarty->assign('hasNoAndroidApp',!$hasAndroidApp);
		
    $this->styleSheets[] = "/css/preview.css";
    $this->smarty->assign('linkTypeOptions',HouseAd::$HOUSEAD_LINKTYPES);
    $this->smarty->assign('typeOptions',HouseAd::$HOUSEAD_TYPES);
    $this->smarty->assign('createOrEdit','edit');
    $this->smarty->assign('appHouseAds',$appHouseAds);
    $this->breadcrumbs[] =   array('text' => "Edit",
				   'link' => '/houseAds/ad/edit');  
    
    $this->smarty->assign('houseAd', $this->houseAd);
    fb("smarty",$this->smarty->get_template_vars());
    return $this->smarty->fetch('../tpl/www/houseAds/create.tpl');
    
  }

  public function editSubmit() {
    $this->printHeader = false;
    $this->printFooter = false;
	
    $this->needsHouseAd();

    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $type = isset($_POST['type']) ? $_POST['type'] : HouseAd::HOUSEAD_TYPE_ICON;
    $link = isset($_POST['link']) ? $_POST['link'] : '';
    $linkType = isset($_POST['linkType']) ? $_POST['linkType'] : HouseAd::HOUSEAD_LINKTYPE_WEBSITE;
    $imageLink = isset($_POST['imageLink']) ? $_POST['imageLink'] : null;

    $this->houseAd->name = $name;
    $this->houseAd->description = $description;
    $this->houseAd->type = $type;
    fb("link",$link);
    $this->houseAd->link = HouseAdUtil::getFixedLink($link, $linkType);
    fb("halink",$this->houseAd->link);
    $this->houseAd->linkType = $linkType;
    $this->houseAd->launchType = HouseAdUtil::getLaunchType($linkType);

    if($imageLink != null) {
      $this->houseAd->imageLink = $imageLink;
    }

    $result = $this->houseAd->put();
    fb("ha",$this->houseAd);
    // new apps to add
    $apps = isset($_POST['apps']) ? $_POST['apps'] : null;
    // old_apps to keep => do nothing
    $ahids = isset($_POST['ahids']) ? $_POST['ahids'] : null;
    // old_apps to delete
    $del_ahids = isset($_POST['del_ahids']) ? $_POST['del_ahids'] : null;
    AppHouseAdUtil::addRemoveAppHouseAds($this->houseAd->id, $apps, $del_ahids);
    $this->redirect($_REQUEST['returnPage']);
  }

  public function addApp() {
    $this->printHeader = false;
    $this->printFooter = false;
	  
    if (!isset($this->houseAd)) {
      $cid = isset($_REQUEST['cid']) ? $_REQUEST['cid'] : null;
      $this->houseAd = new HouseAd($cid);			
    }
    // new apps to add
    $apps = isset($_POST['apps']) ? $_POST['apps'] : null;
    // old_apps to keep => do nothing
    $ahids = isset($_POST['ahids']) ? $_POST['ahids'] : null;
    // old_apps to delete
    $del_ahids = isset($_POST['del_ahids']) ? $_POST['del_ahids'] : null;
    AppHouseAdUtil::addRemoveAppHouseAds($this->houseAd->id, $apps, $del_ahids);
  }
  public function delete() {
	  $this->printHeader = false;
    $this->printFooter = false;
  
    $this->needsHouseAd();
    // new apps to add
    $apps = isset($_POST['apps']) ? $_POST['apps'] : null;
    // old_apps to keep => do nothing
    $ahids = isset($_POST['ahids']) ? $_POST['ahids'] : null;
    // old_apps to delete
    $del_ahids = isset($_POST['del_ahids']) ? $_POST['del_ahids'] : null;
    AppHouseAdUtil::addRemoveAppHouseAds($this->houseAd->id, $apps, $del_ahids);
    $cid = $this->houseAd->id;
    $this->houseAd->delete();
    $this->redirect(isset($_REQUEST['returnPage']) ? $_REQUEST['returnPage'] . '?&del_cid=' . $cid : '/houseAds/houseAds?del_cid=' . $cid);
  }
  
  public function deleteSubmit() {
    $this->printHeader = false;
    $this->printFooter = false;
  
    $this->needsHouseAd();
    $cid = $this->houseAd->id;
    $this->houseAd->delete();
    $this->redirect('/houseAds/houseAds');

  }

  private function needsHouseAd() {
    $cid = isset($_REQUEST['cid']) ? $_REQUEST['cid'] : null;
    if($cid === null) {
      $this->redirect('/houseAds/houseAds/');
    }
    $this->houseAd = new HouseAd($cid);
    $this->breadcrumbs[] =   array('text' => $this->houseAd->name,
				   'link' => '/houseAds/ad/edit?cid='.$this->houseAd->id);
    $this->needSwitcher = true;
    $this->switcherText = 'Switch House Ad';
    $switchList = Array();
    $this->subtitle = $this->houseAd->name;
    foreach (HouseAdUtil::getHouseAdsByUid($this->user->id) as $houseAd) {
      if ($houseAd->id!=$cid) $switchList[] = $houseAd;
    }
    $this->switcherList = $switchList;
  }



}
