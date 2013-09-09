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

function numAppAsc($a, $b) {
  return (count($a->apps) - count($b->apps));
}

function numAppDsc($a, $b) {
  return - (count($a->apps) - count($b->apps));
}


function makeSortFunction($field1, $asc=true)
{
	$code = "\$retval = ".($asc?'':'-')."strnatcmp(strtolower(\$a->$field1), strtolower(\$b->$field1));
  return \$retval;";
  return create_function('$a,$b', $code);
}

class houseAds extends houseAdsBase {
  public function __default() {
    $this->user->postGet();    
    $a = $this->user->getPref('msg_002');    
    if (empty($a)) {
      $msg_002 = "<span class='msg'>Learn about our upgraded <a target='_newtab' href='http://helpcenter.adwhirl.com/content/step-6-allocate-your-house-ads'>House Ads</a> functionality</span>";
      $this->smarty->assign('message', $msg_002);      
      $this->smarty->assign('msg_id', 'msg_002');
      
    }
    $this->smarty->assign('returnPage',isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : null);    
    $this->styleSheets[] = "/css/preview.css";
    $this->jsFiles[] = "/js/jqsm135.js";	
	  $this->displayFinalArrowInBreadcrumbs = true;
    $houseAds = HouseAdUtil::getHouseAdsByUid($_SESSION['uid']);
		$apps = AppUtil::getAppsByUid($this->user->id);
		$o = isset($_REQUEST['o'])?$_REQUEST['o']:0;
    $o = intval($o);

    $haveMissingAd = false; // Assume we don't have the missing ad until we scan them all
    if (!empty($_REQUEST['n_cid'])) {
      foreach ($houseAds as $houseAd) {
        // if the current ad has the same id, we know we have the missing ad
        $haveMissingAd |= $houseAd->id == $_REQUEST['n_cid'];

        // Stop if we know we have the missing ad
        if($haveMissingAd) break;
      }      
    } else {
      $haveMissingAd = true; // no new ad id passed in, so all ads should be in the DB
    }

    if (!empty($_REQUEST['del_cid'])) {
      foreach ($houseAds as $idx => $houseAd) {       
        if ($houseAd->id == $_REQUEST['del_cid']) {
          unset($houseAds[$idx]);
        }
      }      
    }    

    fb('haveMissingAd', $haveMissingAd);
    // Do we have all the ads? If not, let's add the missing one.
    if (!$haveMissingAd) {
      $houseAd = new HouseAd();
      $houseAd->id = $_REQUEST['n_cid'];
      $houseAd->name = $_REQUEST['n_name'];
      $houseAd->type = intval($_REQUEST['n_type']);
      $houseAd->linkType = intval($_REQUEST['n_linkType']);      
      $houseAds[] = $houseAd;
      fb("houseAd", $houseAd);      
    }
    $fields = array('name','linkType','type');
    $total = count($houseAds);
    
    if ($total<=10) {
      $fields[] = 'numApp';
    }
    $sortBy = isset($_REQUEST['sortBy'])?$_REQUEST['sortBy']:'nameAsc';
    if (substr($sortBy,-1)=='?') $sortBy = substr($sortBy,0,-1);
    $isAsc = substr($sortBy,-3)=='Asc';
    $sortBy = substr($sortBy,0,strlen($sortBy)-3);
    foreach ($fields as $field) {
      $fname = $field;      
      if ($field==$sortBy) {
        $val = $fname . ($isAsc?'Dsc':'Asc');
      } else {
        $val = isset($_REQUEST[$fname])?$_REQUEST[$fname]:$field . 'Asc';
      }
      $this->smarty->assign($fname, $val);
    }
    $itemsPerPage = 10;		
    if ($sortBy=='numApp' && $total<=10) {
      $fname = 'numApp' . ($isAsc?'Asc':'Dsc');
      foreach ($houseAds as $houseAd) {
        $houseAd->getApps();
      }
      usort($houseAds, $fname);
    } else {
      if ($sortBy=='numApp') {
        $sortBy='name';
        $isAsc = 'true';
      }
		  usort($houseAds, makeSortFunction($sortBy,$isAsc));
  		$houseAds = array_slice($houseAds,$o,$itemsPerPage);
      foreach ($houseAds as $houseAd) {
        $houseAd->getApps();
      }
	  }		
    
		$this->smarty->assign('appsCount', count($apps));
		$this->smarty->assign('current_offset', $o);
    $this->smarty->assign('total', $total);
		$this->smarty->assign('itemsPerPage', $itemsPerPage);
		
    $this->subtitle = "House Ads";
		$this->smarty->assign('linkLabels',HouseAd::$HOUSEAD_LINKTYPES);
		fb($houseAds);		
    $this->smarty->assign('houseAds', $houseAds);
    $this->smarty->assign('houseAdTypes', HouseAd::$HOUSEAD_TYPES);
    fb("smarty",$this->smarty->get_template_vars());
    
    return $this->smarty->fetch('../tpl/www/houseAds/houseAds.tpl');
  }
	public function deleteAppHouseAds() {
		$this->printHeader = false;
        $this->printFooter = false;
		$aid = isset($_REQUEST['aid']) ? $_REQUEST['aid'] : null;
		$del_ahids = isset($_POST['del_ahids']) ? $_POST['del_ahids'] : null;
		fb('del_ahids',$del_ahids);
		$ret = AppHouseAdUtil::addRemoveAppHouseAds(null,null,$del_ahids);		
		fb("retVal",$ret);
		$this->redirect("/apps/oneApp/appHouseAds?aid=$aid");		
	}
	

	public function canDelete() {
		$this->printHeader = false;
    $this->printFooter = false;
		$del_cids = isset($_REQUEST['deletes']) ? $_REQUEST['deletes'] : null;
		$hasApps = array();
		fb('del_cids',$del_cids);
		foreach ($del_cids as $del_cid) {
			$apps = HouseAdUtil::getAppsByCid($del_cid);
			$count = count($apps);
			if ($count>0) $hasApps[] = $del_cid;
		}
		if (count($hasApps)==0) return 'OK';
		else return implode(",", $hasApps);
	}
	
  public function delete() {
	  $this->printHeader = false;
    $this->printFooter = false;
		$deletes = isset($_REQUEST['deletes']) ? $_REQUEST['deletes'] : null;
		for($i=0; $i<count($deletes); $i++) { 
			$cid = $deletes[$i];
			$houseAd = new HouseAd($cid);	
			$houseAd->delete();
		}
		$this->redirect('/houseAds/houseAds/');
	}
}
