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

require_once('modules/apps/appsBase.php');

class oneApp extends appsBase {
  protected $app;

  public function __construct() {
    parent::__construct();
    $this->jsFiles[] = '/js/traffic.js';
    $this->jsFiles[] = '/js/jquery.hint.js';
		$this->jsFiles[] = "/js/jqsm135.js";		
    $this->jsFiles[] = '/js/jquery.tablesorter.min.js';

  }

  public function __default() {
    return $this->appNetworks();
  }

  public function houseAdsSubmit() {
    $this->printHeader = false;
    $this->printFooter = false;	  
    $this->needsApp();
    $ahids = isset($_REQUEST['ahids'])?$_REQUEST['ahids']:'';
    $weight = isset($_REQUEST['weight'])?$_REQUEST['weight']:'';
    fb('ahids',$ahids);
    fb('weight',$weight);
    for ($i = 0; $i < count($ahids); $i++) {
      $appHouseAd = new AppHouseAd($ahids[$i]);
      $appHouseAd->weight = $weight[$i];
      fb('aha',$appHouseAd);
      $appHouseAd->put();
    }		    
    $this->redirect('/apps/oneApp/appHouseAds?aid=' . $this->app->id);
  }
  public function appHouseAds() {
    $this->needsApp();
    $this->breadcrumbs[] = array("text"=>"House Ads","link"=>"/apps/oneApp/appHouseAds");

    $appHouseAds = array();
    foreach (AppHouseAdUtil::getAppHouseAdsByAid($this->app->id) as $appHouseAd) {
      $appHouseAds[$appHouseAd->cid] = $appHouseAd;
    }
    $houseAds = array();
    fb("getAllHouseAds","");
    $allHouseAds = HouseAdUtil::getHouseAdsByUid($this->user->id);
    // fb("houseAds",HouseAdUtil::getHouseAdsByAid($this->app->id));
    fb("gotAllHouseAdsForThisUser","");    
    $houseAdsForThisApp = HouseAdUtil::getHouseAdsByAid($this->app->id, $allHouseAds);
    fb("getAllHouseAdsForThisApp","");
    foreach ($houseAdsForThisApp as $houseAd) {
      if (array_key_exists($houseAd->id, $appHouseAds)) {
	      $houseAds[] = $houseAd;
	      $houseAd->weight = $appHouseAds[$houseAd->id]->weight;
	      $houseAd->ahid = $appHouseAds[$houseAd->id]->id;
      }
    }
    fb("prepAddableHouseAds","");
    $addableHouseAds = array(''=>'Choose an Ad');
    foreach ($allHouseAds as $houseAd) {
      if (!array_key_exists($houseAd->id, $appHouseAds)) {
	      if (		($this->app->platform=='1' && $houseAd->isForiPhone())  ||
			  ($this->app->platform=='2' && $houseAd->isForAndroid())  ) {
	        $addableHouseAds[$houseAd->id] = $houseAd->name;
	      }
      }
    }
    fb("doneAddableHouseAds",$addableHouseAds);
    $networks=$this->app->getNetworks();
    if (array_key_exists('9',$networks) && $networks['9']->adsOn==1) {
      $this->smarty->assign('houseAdShare', $networks['9']->weight);
    }
	  usort($houseAds, create_function('$a,$b', "return strcasecmp(\$a->name, \$b->name);"));
    
    $this->subtitle = "House Ads";
    $this->smarty->assign('app', $this->app);
    $this->smarty->assign('sideNav_current', 'houseAds');
    $this->smarty->assign('linkLabels',HouseAd::$HOUSEAD_LINKTYPES);		
    $this->smarty->assign('houseAds', $houseAds);
    $this->smarty->assign('addableHouseAds', $addableHouseAds);
    $this->smarty->assign('houseAdTypes', HouseAd::$HOUSEAD_TYPES);
    fb("smarty",$this->smarty->get_template_vars());
    return $this->smarty->fetch('../tpl/www/apps/appHouseAds.tpl');

  }
	
	function appNetworkComparator($a, $b) {
        $retval = -strnatcmp($a->adsOn, $b->adsOn);
	   if (!$retval) $retval = -strnatcmp($a->weight,  $b->weight);
	  if(!$retval) $retval = strcasecmp(Network::$NETWORKS[$a->type]['name'], Network::$NETWORKS[$b->type]['name']);
	  return $retval;
	}
	
	function makeSortFunction($field1, $asc=true)
	{
		$code = "\$retval = ".($asc?'':'-')."strnatcmp(\$a->$field1, \$b->$field1);
	  if(!\$retval) return strcasecmp(Network::\$NETWORKS[\$a->type]['name'], Network::\$NETWORKS[\$b->type]['name']);
	  return \$retval;";
	  return create_function('$a,$b', $code);
	}

	function orderByName($a,$b) {
		return strcasecmp(Network::$NETWORKS[$a->type]['name'], Network::$NETWORKS[$b->type]['name']);
	}

  public function appNetworks() {
    $this->needsApp();
    // $this->breadcrumbs[count($this->breadcrumbs)-1]['link']="";
    $this->breadcrumbs[] = array("text"=>"Ad Network Settings","link"=>"/apps/oneApp/appNetworks");
    $this->smarty->assign('sideNav_current', 'networks');
    $this->smarty->assign('app', $this->app);
    $this->smarty->assign('backfill', FALSE);
    if (!empty($_REQUEST['showNoNetworkRunning'])) {
        $this->smarty->assign('showNoNetworkRunning', TRUE);
    }
    $networks = array();
    $appHasTypes = array();
    foreach (Network::$NETWORKS as $type => $typeInfo) {
      $appHasTypes[$type] = false;
    }
    $appNetworks = NetworkUtil::getAllNetworksByAid($this->app->id);
    fb("appNetworks", $appNetworks);
    foreach ($appNetworks as $network) {
      $appHasTypes[$network->type] = true;
      $networks[] = $network;
    }
    fb("hasType",$appHasTypes);
    foreach (Network::$NETWORKS as $type => $typeInfo) {
      if (!$appHasTypes[$type]) {
	$network = new Network();
      	$network->aid = '';
      	$network->type = $type;
      	$network->adsOn = 0;
      	$network->weight = 0;				
	$networks[] = $network;
      }
    }
        // $compare = $this->makeSortFunction('weight',false);
		usort($networks, array($this,'appNetworkComparator'));
    $this->smarty->assign('networks', $networks);
    $this->smarty->assign('networkTypes', Network::$NETWORKS);
    fb("smarty",$this->smarty->get_template_vars());
    return $this->smarty->fetch('../tpl/www/apps/appNetworks.tpl');
  }
  
  public function backfillPriority() {
    $this->needsApp();
    $this->breadcrumbs[] = array("text"=>"Backfill Priority","link"=>"/apps/oneApp/backfillPriority");
    $this->smarty->assign('sideNav_current', 'backFill');

    $this->smarty->assign('app', $this->app);
    $this->smarty->assign('backfill',TRUE);
	    $networks = array();
	    $appHasTypes = array();
	    foreach (Network::$NETWORKS as $type => $typeInfo) {
	      $appHasTypes[$type] = false;
	    }
	    $appNetworks = NetworkUtil::getAllNetworksByAid($this->app->id);
	    fb("appNetworks", $appNetworks);
	    foreach ($appNetworks as $network) {
	      $appHasTypes[$network->type] = true;
	      $networks[] = $network;
	    }
	    fb("hasType",$appHasTypes);
	    foreach (Network::$NETWORKS as $type => $typeInfo) {
	      if (!$appHasTypes[$type]) {
		$network = new Network();
	      	$network->aid = '';
	      	$network->type = $type;
	      	$network->adsOn = 0;
	      	$network->weight = 0;				
		$networks[] = $network;
	      }
	    }
	    $compare = $this->makeSortFunction('priority',true);
  		
			usort($networks, $compare);

    $this->smarty->assign('networks', $networks);
    fb('networks', $networks);

    $this->smarty->assign('networkTypes', Network::$NETWORKS);
    fb("smarty",$this->smarty->get_template_vars());
    return $this->smarty->fetch('../tpl/www/apps/appNetworks.tpl');    
  }

  public function appNetworksSubmit() {
    $this->printHeader = false;
    $this->printFooter = false;
    $this->needsApp();

    if($this->user->id != $this->app->uid) {
      die;
    }

    $nids = isset($_POST['nid']) ? $_POST['nid'] : null;
    $priorities = isset($_POST['priority']) ? $_POST['priority'] : null;
    $weights = isset($_POST['weight']) ? $_POST['weight'] : null;
    $adsOn = isset($_POST['adsOn']) ? $_POST['adsOn'] : null;
    fb('nids',$nids);
    fb('priorities',$priorities);
    fb('adsOn',$adsOn);
    fb('weights',$weights);
    $off = array();
    $on = array();
    for($i=0; $i<count($nids); $i++) { 
      $nid = $nids[$i];
      if(!empty($nid)) { 
	      $network = new Network($nid);
	      $network->postGet();
      } else {
	      continue;
      	/* We don't need this, since we can only add change things once they have keys
      	$network = new Network();
      	$nid = SDB::uuid();
      	$network->id = $nid;
      	*/
      }
      $network->aid = $this->app->id;
      $network->weight = isset($weights[$i]) ? $weights[$i] : null;
      $network->adsOn = $adsOn[$i];			
      if ($adsOn[$i]=="1") {
	      $network->priority = $priorities[$i];
	      $on[$network->priority*1000+$i] = $network;
      }	else {
	      $network->priority = Network::MAX_PRIORITY;
	      $network->put();
      }		
    }

    ksort($on);
    fb("on",$on);
    $count = 0;
    foreach ($on as $network) {
      $network->priority = ++$count;
      $network->put();
    }

    return "OK";
    //return $this->appNetworks();
  }

  public function appNetworkKeySubmit() {
    $this->needsApp();
    $this->printHeader = false;
    $this->printFooter = false;

    if($this->user->id != $this->app->uid) {
      die;
    }
    
    $aid = $_POST['aid'];
    $nid = $_POST['nid'];
    $type = $_POST['type'];
    $keys = $_POST['keys'];

    $network = new Network($nid);
    if($network->id == null) {
      if(!isset($type)) {
        return "Error";
	//return $this->appNetworks();
      }
      else {
      	$network = new Network();
      	$network->id = SDB::uuid();
      	$network->aid = $aid;
      	$network->type = $type;
      	$network->adsOn = 0;
      	$network->weight = 0;
	$network->priority = Network::MAX_PRIORITY;
      }
    }
    $network->keys = $keys;
    fb("network",$network);
    $network->put();
    return $network->id;
  }

  public function create() {
    $this->breadcrumbs[] = array("text"=>"Create App","link"=>"/apps/oneApp/create");
    $app = new App();
    $this->jsFiles[] = "/js/jquery.validate.min.js";
    $this->smarty->assign('app',$app);
    $this->create_and_info_smarty_options();
    return $this->smarty->fetch('../tpl/www/apps/create.tpl');
  }
  private function create_and_info_smarty_options()  {
    $this->smarty->assign('returnPage',$_SERVER['HTTP_REFERER']);
    
    $this->smarty->assign('iPhone_sdk_link', 'http://adwhirl_iphone_sdk');
    $this->smarty->assign('Android_sdk_link', 'http://adwhirl_android_sdk');
    $this->smarty->assign('iPhone_instruction_link', 'http://adwhirl_iphone_instruction');
    $this->smarty->assign('Android_instruction_link', 'http://adwhirl_android_instruction');

    $cycles_time = array(15,30,45,60,120,180,240,300,600);
    $cycles_label = array_map(
			      create_function('$x', 'return $x . " seconds";'),
			      $cycles_time
			      );
    array_splice($cycles_time, 0, 0, 30000);
    array_splice($cycles_label, 0, 0, 'Disabled');

    $animation_val = array(App::ANIMATION_SLIDE_FROM_RIGHT, App::ANIMATION_SLIDE_FROM_LEFT, App::ANIMATION_FLIP_FROM_RIGHT, App::ANIMATION_FLIP_FROM_LEFT, App::ANIMATION_FADE_IN, App::ANIMATION_CURL_UP, App::ANIMATION_CURL_DOWN, App::ANIMATION_RANDOM, App::ANIMATION_NONE);
    $animation_label = array("Slide From Right", "Slide From Left", "Flip From Right", "Flip From Left", 
			     "Fade In", "Curl Up", "Curl Down", "Random", "None");
		

    $this->smarty->assign('cycleTime', $cycles_time);
    $this->smarty->assign('cycleLabel', $cycles_label);
    $this->smarty->assign('animationValues', $animation_val);
    $this->smarty->assign('animationLabels', $animation_label);
  }
  public function createSubmit() {
    if (isset($_REQUEST['n_aid']) || !isset($_REQUEST['name'])) {
      $this->redirect('/apps/apps');
    }
    $this->printHeader = false;
    $this->printFooter = false;
  
    $name = $_POST['name'];
    $storeUrl = $_POST['storeUrl'];
    $platform = $_POST['platform'];
    $fgColor = $_POST['fgColor'];
    $bgColor = $_POST['bgColor'];
    $cycleTime = $_POST['cycleTime'];
    $transition = $_POST['transition'];
    $locationOn = $_POST['locationOn'];

    $app = new App();
    $app->id = SDB::uuid();
    $app->uid = $this->user->id;
    $app->name = $name;
    $app->storeUrl = $storeUrl;
    $app->platform = $platform;
    $app->fgColor = $fgColor;
    $app->bgColor = $bgColor;
    $app->cycleTime = $cycleTime;
    $app->transition = $transition;
    $app->locationOn = $locationOn;
    $app->adsOn = 1;

    $app->put();
    $_POST['aid'] = $app->id;

    // before finishing, check to see if the new app has been added to th SDB
    $sdb = SDB::getInstance();
    // sleeps for half a second until we see an entry for this id
    $fields = "name";
    while(!($sdb->get($app->getSDBDomain(), $app->id, $fields))) {
      $fields = "name";// necessary since get overwrites $fields
      usleep(500);
    }
  }

  public function info() {
    $this->needsApp();
    $this->smarty->assign('returnPage',isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null);
    $this->breadcrumbs[] = array('text'=>"App Settings",'link'=>'apps/oneApp/info');
    $this->smarty->assign('sideNav_current', 'info');
    $this->jsFiles[] = "/js/jquery.validate.min.js";
    $this->smarty->assign('app', $this->app);
    $this->create_and_info_smarty_options();
    return $this->smarty->fetch('../tpl/www/apps/info.tpl');
  }

  public function infoSubmit() {
    $this->printHeader = false;
    $this->printFooter = false;

    $this->needsApp();

    $name = $_POST['name'];
    $storeUrl = $_POST['storeUrl'];
    $platform = $_POST['platform'];
    $fgColor = $_POST['fgColor'];
    $bgColor = $_POST['bgColor'];
    $cycleTime = $_POST['cycleTime'];
    $transition = $_POST['transition'];
    $locationOn = $_POST['locationOn'];

    $this->app->name = $name;
    $this->app->storeUrl = $storeUrl;
    $this->app->platform = $platform;
    $this->app->fgColor = $fgColor;
    $this->app->bgColor = $bgColor;
    $this->app->cycleTime = $cycleTime;
    $this->app->transition = $transition;
    $this->app->locationOn = $locationOn;

    $this->app->put();
    $this->redirect($_REQUEST['returnPage']);    		    
  }
  public function deleteNetwork() {
		$this->needsApp();
	    $this->printHeader = false;
	    $this->printFooter = false;
		$nid = $_REQUEST['nid'];
		$network = new Network($nid);
		$network->delete();
		$networks = $this->networks = NetworkUtil::getNetworksByAidIndexNid($this->app->id);
		unset($networks[$nid]);
		App::setPriorities($networks);
		$sum = App::setWeights($networks);
		$url = '/apps/oneApp/appNetworks?aid='.$this->app->id;
		if ($sum!=100) {
		    $url .= '&showNoNetworkRunning=true';
		}
		fb("sum", $sum);
		fb('url', $url);
		$this->redirect($url);
	}
  public function delete() {
    $this->needsApp();

    $this->smarty->assign('app', $this->app);
    
    return $this->smarty->fetch('../tpl/www/apps/delete.tpl');
  }

  public function deleteSubmit() {
    $this->printHeader = false;
    $this->printFooter = false;
    $this->needsApp();
    $aid = $this->app->id;
    $this->app->delete();

    $this->redirect('/apps/apps?del_aid='.$aid);
  }


  private function needsApp() {
    $this->app = AppUtil::getActiveApp($this->user);
    // $this->app->adjustHouseAdNetwork(null, null);
    if($this->app === null) {
      redirect('/apps/apps');
    }
    $crumb = array('text' => $this->app->name,
		   'link' => '/apps/oneApp?aid=' . $this->app->id);
    $this->breadcrumbs[] = $crumb;
    $this->subtitle = "<span style='display:inline-block;padding-top:7px;padding-right:7px'>".$this->app->name."</span><span style='padding-left:10px;font-size:11px;color:#666'>SDK Key:".$this->app->id."</span>";
    $this->needSwitcher = true;
    $this->switcherText = 'Switch App';
    $switchList = Array();
    foreach (AppUtil::getAppsByUid($this->user->id) as $app) {
      if ($app->id!=$this->app->id) $switchList[] = $app;
    }
    $this->switcherList = $switchList;
  }
}
