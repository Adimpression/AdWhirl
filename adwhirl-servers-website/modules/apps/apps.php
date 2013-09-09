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

function sortAppsByName($a, $b) {
  if (strtolower($a->name) == strtolower($b->name)) {
    return 0;
  }
  return (strtolower($a->name) < strtolower($b->name)) ? -1 : 1;
}

class apps extends appsBase {

  public function __default() {
    $this->user->postGet();
    $b = $this->user->getPref('msg_003');
    if (empty($b)) {
      $msg_003 = "<span class='msg'>NEW! AdWhirl now explicitly supports Millennial Media on Android – download the new <a target='_newtab' href='http://code.google.com/p/adwhirl/downloads/list'>AdWhirl Android SDK</a></span>";
      $this->smarty->assign('message', $msg_003);      
      $this->smarty->assign('msg_id', 'msg_003');
    } else {        
      $a = $this->user->getPref('msg_001');
      if (empty($a)) {
        $msg_001 = "<span class='msg'>Learn about how you can add unlimited additional networks with <a target='_newtab' href='http://helpcenter.adwhirl.com/content/custom-events-and-generic-notifications'>Custom Events</a></span>";
        $this->smarty->assign('message', $msg_001);      
        $this->smarty->assign('msg_id', 'msg_001');
      }
    }
    
    $this->smarty->assign('returnPage',isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null);    
    $this->displayFinalArrowInBreadcrumbs = true;
    $o = isset($_REQUEST['o'])?$_REQUEST['o']:0;
    $o = intval($o);
    $apps = AppUtil::getAppsByUid($this->user->id);
    $missingApp = false;
    $notMissingApp = false || empty($_REQUEST['n_aid']);
    if (!empty($_REQUEST['n_aid'])) {
      foreach ($apps as $app) {       
        $missingApp |= ($app->id != $_REQUEST['n_aid']);
        $notMissingApp |= ($app->id == $_REQUEST['n_aid']);
        // echo $notMissingApp . "- " .  $missingApp .'-'. $app->id . '-'. $_REQUEST['n_aid'] . '--'.($app->id == $_REQUEST['n_aid']) .'<br>';
      }      
    }
    if (!empty($_REQUEST['del_aid'])) {
      foreach ($apps as $idx => $app) {       
        if ($app->id == $_REQUEST['del_aid']) {
          unset($apps[$idx]);
        }
      }      
    }
    
//    fb('missingApp',$missingApp);
    // echo "NotMissingApp $notMissingApp <br>";
    if (!$notMissingApp) {
      $app = new App();
      $app->id = $_REQUEST['n_aid'];
      $app->name = $_REQUEST['n_name'];
      $app->platform = intval($_REQUEST['n_platform']);
      $apps[] = $app;
      fb("App", $app);      
    }
    usort($apps, "sortAppsByName");
    
    fb('apps', $apps);
    $total = count($apps);
    $itemsPerPage = 10;
    fb('apps',$apps);
    $apps = array_slice($apps,$o,$itemsPerPage);
    fb('apps',$apps);
    // array_pop($this->breadcrumbs);
    //     $this->breadcrumbs[] = array("text"=>"App List","link"=>"/apps/apps");


    $this->subtitle = "App List";
    $this->smarty->assign('apps', $apps);
    $this->smarty->assign('current_offset', $o);
    $this->smarty->assign('total', $total);
    $this->smarty->assign('itemsPerPage', $itemsPerPage);
    fb("smarty",$this->smarty->get_template_vars());
    return $this->smarty->fetch('../tpl/www/apps/apps.tpl');
  }
}
