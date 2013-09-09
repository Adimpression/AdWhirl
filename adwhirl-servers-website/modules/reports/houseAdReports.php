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
require_once('modules/reports/reportsBase.php');

class houseAdReports extends reportsBase {
  protected $cid;
  protected $houseAds;
  protected $catOptions;
	
  public function __construct() {
    parent::__construct();
    $this->breadcrumbs[] = array('text' => 'House Ads',
				 'link' => '/reports/houseAdReports');
    $this->houseAds = HouseAdUtil::getHouseAdsByUid($_SESSION['uid'], false);
    fb('had',$this->houseAds);
    fb('ha',HouseAdUtil::getHouseAdsByUid($_SESSION['uid']));
    usort($this->houseAds, 'sortByName');
    
		if (count($this->houseAds)>0)
    	$this->cid = isset($_REQUEST['cid'])?$_REQUEST['cid']:$this->houseAds[0]->id;
    $this->catOptions = array('3-all'=>'Top 3 Apps', '5-all' => 'Top 5 Apps', '5-android' => 'Top Android Apps', '5-iphone' => 'Top iPhone Apps');
    if (!isset($_SESSION['metricTypeSelected'])) $_SESSION['metricTypeSelected']='Impressions';
    $this->metricTypeSelected  = isset($_REQUEST['metricTypeSelected']) ? $_REQUEST['metricTypeSelected'] : $_SESSION['metricTypeSelected'];
    $_SESSION['metricTypeSelected'] = $this->metricTypeSelected;
    $_SESSION['selectedCat']  = isset($_REQUEST['selectedCat']) ? $_REQUEST['selectedCat'] : '3-all';
    $cat = split('-',$_SESSION['selectedCat']);
    fb("selectedCat",$_SESSION['selectedCat']);
    fb("cat",$cat);
    $this->numCat = $cat[0];
    switch(strtolower($cat[1])) {
    case 'all': $this->platform = 0;break;
    case 'iphone': $this->platform = 1;break;
    case 'android': $this->platform = 2;break;
    }			
  }

  public function __default() {
    return $this->houseAdReports();
  }

  private function getTableData() {
    $houseAds = HouseAdUtil::getHouseAdsByUid($this->user->id);
    

    foreach($houseAds as $houseAd) {
      $houseAd->reports = ReportUtil::getReportsByCid($houseAd->id, $this->startDate, $this->endDate);
      $totals = array();
      if(isset($houseAd->reports)) {
        foreach ($houseAd->reports as $report) {
	  if(isset($totals['impressions'])) {
	    $totals['impressions'] += $report['impressions'];
	    $totals['clicks'] += $report['clicks'];
	  } else {
	    $totals['impressions'] = $report['impressions'];
	    $totals['clicks'] = $report['clicks'];
	  }        
	}          
      }
      
      if (isset($totals['clicks'])) {
        $totals['ctr'] = sprintf("%.3f", $totals['clicks']/$totals['impressions']);
      } else {
        $totals = array('ctr' => 'N/A', 'clicks' => '0', 'impressions' => '0');
      }

      $houseAd->totals = $totals;
    }
    return $houseAds;
  }
  public function houseAdReports() {
    $houseAdOptions = array();
    $this->smarty->assign('showDeleted', isset($_SESSION['showDeleted']));    
    foreach ($this->houseAds as $houseAd) {
      $houseAdOptions[$houseAd->id] = $houseAd->name . ($houseAd->deleted?' -- deleted':'');
    }
    // $this->subtitle = "Reporting";
    $this->subtitle = empty($this->cid)?"Reports":$houseAdOptions[$this->cid];
    
    $metricTypes = array('Impressions', 'Clicks' , 'CTR');
    $this->smarty->assign('metricTypes', $metricTypes);
    $this->smarty->assign('metricTypeSelected', $this->metricTypeSelected);
    $this->smarty->assign('houseAdOptions', $houseAdOptions);
    $this->smarty->assign('selectedHouseAd', $this->cid);
    $this->smarty->assign('dateOptions', $this->dateOptions);
    $this->smarty->assign('selectedDate', $this->selectedDate);
    $this->smarty->assign('catOptions',$this->catOptions);
    $this->smarty->assign('selectedCat', $_SESSION['selectedCat']);
		
    $this->smarty->assign('sideNav_current', 'houseAds');
    $this->smarty->assign('houseAds', $this->houseAds);
    $this->smarty->assign('houseAdTypes', HouseAd::$HOUSEAD_TYPES);
    $this->smarty->assign('dataURL', 'http://'.$_SERVER['SERVER_NAME'].'/reports/houseAdReports/getXML');
    $this->smarty->assign('queryParam', '?start='. $this->startDate.'&end='.$this->endDate);
    $this->smarty->assign('csvURL', '/reports/houseAdReports/getCSV');
    $this->smarty->assign('htmlTableURL', '/reports/houseAdReports/getHTMLTable');

    return $this->smarty->fetch('../tpl/www/reports/houseAdReports.tpl');
  }
  public function getCSV() {
    $this->printHeader = false;
    $this->printFooter = false;
    $reports = ReportUtil::getReportsByCid($this->cid, $this->startDate, $this->endDate, 1000, 0);
    $dates = array();
    for($i=$this->startDate; $i<=$this->endDate; $i=date('Y-m-d', strtotime("+ 1day", strtotime($i)))) {
      $dates[] = $i;
    }
    if ($this->metricTypeSelected=="CTR") $this->calculateCTR($reports);
    fb('getHTMLTable-reports',$reports);
    $this->smarty->assign('reports', $reports);
    $this->smarty->assign('nets', $reports['nets']);
    $this->smarty->assign('dates', $dates);
    $this->smarty->assign('metric', strtolower($this->metricTypeSelected));
    $this->smarty->assign('metricLabel', ($this->metricTypeSelected=='CTR'?"":"Total ") . $this->metricTypeSelected);
    return $this->smarty->fetch('../tpl/www/reports/applicationReportsOneAppCSV.tpl');
  }
  public function getHTMLTable() {
    $this->printHeader = false;
    $this->printFooter = false;
    $reports = ReportUtil::getReportsByCid($this->cid, $this->startDate, $this->endDate, $this->numCat, $this->platform);
    $dates = array();
    for($i=$this->startDate; $i<=$this->endDate; $i=date('Y-m-d', strtotime("+ 1day", strtotime($i)))) {
      $dates[] = $i;
    }
    if ($this->metricTypeSelected=="CTR") $this->calculateCTR($reports);

    fb('getHTMLTable-reports',$reports);
    $this->smarty->assign('reports', $reports);
    $this->smarty->assign('nets', $reports['nets']);
    $this->smarty->assign('dates', $dates);
    $this->smarty->assign('metric', strtolower($this->metricTypeSelected));
    $this->smarty->assign('metricLabel', ($this->metricTypeSelected=='CTR'?"":"Total ") . $this->metricTypeSelected);
    return $this->smarty->fetch('../tpl/www/reports/houseAdReportsTable.tpl');    
  }  
  public function getXML() {
    $this->printHeader = false;
    $this->printFooter = false;

    $apps = AppUtil::getAppsByUid($_SESSION['uid']);
    fb("app",$apps);

    $reports = ReportUtil::getReportsByCid($this->cid, $this->startDate, $this->endDate,$this->numCat,$this->platform);
    fb("reports",$reports);

    $new_reports = $this->collapseReport($reports);

    if ($this->metricTypeSelected=="CTR") $this->calculateCTR($new_reports);

    fb("nr",$new_reports);
    $this->smarty->assign('reports', $new_reports);
    $this->smarty->assign('nets', $reports['nets']);
    $this->smarty->assign('dates', $new_reports['dates']);
    $this->smarty->assign('metric', strtolower($this->metricTypeSelected));

    //fb("smarty",$this->smarty->get_template_vars());
    return $this->smarty->fetch('../tpl/www/reports/applicationReportsXML.tpl');
    
    // 
    //     foreach($houseAds as $houseAd) {
    //       $houseAd->reports = ReportUtil::getReportsByCid($houseAd->id, $this->startDate, $this->endDate);
    //     }
    // 
    //     $totals = array();
    //     $dates = array();
    //     for($i=$this->startDate; $i<=$this->endDate; $i=date('Y-m-d', strtotime('+1 day', strtotime($i)))) {
    //       $dates[] = $i;
    // 
    //       foreach($houseAds as $houseAd) {
    // if(isset($totals[$i])) {
    //   $totals[$i]['impressions'] += $houseAd->reports[$i]['impressions'];
    //   $totals[$i]['clicks'] += $houseAd->reports[$i]['clicks'];
    // }
    // else {
    //   $totals[$i]['impressions'] = $houseAd->reports[$i]['impressions'];
    //   $totals[$i]['clicks'] = $houseAd->reports[$i]['clicks'];
    // }
    //       }
    //     }
    // 
    //     $this->smarty->assign('houseAds', $houseAds);
    //     $this->smarty->assign('dates', $dates);
    //     $this->smarty->assign('totals', $totals);
    // 
    //     return $this->smarty->fetch('../tpl/www/reports/houseAdReportsXML.tpl');
  }
}
