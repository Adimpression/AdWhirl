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

require_once('inc/class/AppUtil.php');
require_once('modules/reports/reportsBase.php');

class applicationReports extends reportsBase {
  public function __construct() {
    parent::__construct();
    $this->breadcrumbs[] = array('text' => 'Application',
		   'link' => '/reports/applicationReports');
		$appsArray = AppUtil::getAppsByUid($_SESSION['uid']);
		// if (count($appsArray)==0) {
		// 	// no apps!!
		// 	$this->redirect('/apps/apps?msg=You%20currently%20have%20no%20apps.%20Add%20an%20app!');
		// } else {
		// 	$this->aid = isset($_REQUEST['aid'])?$_REQUEST['aid']:$appsArray[0]->id;			
		// }
		if (count($appsArray)>0) {
			$this->aid = isset($_REQUEST['aid'])?$_REQUEST['aid']:$appsArray[0]->id;			
		}
		
  }
	protected $aid;
  public function __default() {
    return $this->applicationReports();
  }

  private function getTableData($collapse=false) {
    $apps = AppUtil::getAppsByUid($this->user->id);    
    $types = array();
    foreach($apps as $app) {
      $app->reports = ReportUtil::getReportsByAid($app->id, $this->startDate, $this->endDate);
      $totals = array();
      if (isset($app->reports)) {
        foreach ($app->reports as $report) {
          foreach ($report as $type => $stats) {
	    $types[$type] = 1;
	    if(isset($totals[$type])) {
	      $totals[$type]['impressions'] += $stats['impressions'];
	      $totals[$type]['clicks'] += $stats['clicks'];
	    }
	    else {
	      $totals[$type]['impressions'] = $stats['impressions'];
	      $totals[$type]['clicks'] = $stats['clicks'];
	    }                  
          }
        }
      }

      $app->totals = $totals;
    }

    $networks = array();
    foreach($types as $type => $discard) {
      $networks[$type] = isset(Network::$NETWORKS[$type]) ? Network::$NETWORKS[$type]['name'] : 'Unknown';
    }
    $networks[0] = 'Total';

    ksort($networks);
        
    return array("apps"=>$apps, "networks" => $networks);
    
  }
  public function applicationReports() {
    $result = $this->getTableData();    
    $this->smarty->assign('showDeleted', isset($_SESSION['showDeleted']));    
    $apps = array();
    $appsArray = AppUtil::getAppsByUid($_SESSION['uid'], false);
    usort($appsArray, 'sortByName');
		
    foreach ($appsArray as $app) {
      $apps[$app->id] = $app->name . ($app->deleted?' -- deleted':'');
    }
    $this->subtitle = empty($this->aid)?"Reports":$apps[$this->aid];
    $this->smarty->assign('appsOption', $apps);
    $this->smarty->assign('selectedApp', $this->aid);
    $this->smarty->assign('selectedDate', $this->selectedDate);
    $this->smarty->assign('networks', $result['networks']);
    $this->smarty->assign('sideNav_current', 'applications');
    $this->smarty->assign('apps', $result['apps']);
    $this->smarty->assign('dataURL', 'http://'.$_SERVER['SERVER_NAME'].'/reports/applicationReports/getXML');
    $this->smarty->assign('dateOptions', $this->dateOptions);
    $this->smarty->assign('queryParam', $this->selectedDate);
    $this->smarty->assign('csvURL', '/reports/applicationReports/getCSV');
    $this->smarty->assign('htmlTableURL', '/reports/applicationReports/getHTMLTable');
    return $this->smarty->fetch('../tpl/www/reports/applicationReports.tpl');
  }
  public function getCSV() {
    $this->printHeader = false;
    $this->printFooter = false;


    $reports = ReportUtil::getReportsByAid($this->aid, $this->startDate, $this->endDate,1000);
    $dates = array();
    for($i=$this->startDate; $i<=$this->endDate; $i=date('Y-m-d', strtotime("+ 1day", strtotime($i)))) {
      $dates[] = $i;
    }
    fb('getHTMLTable-reports',$reports);
    $this->smarty->assign('reports', $reports);
    $this->smarty->assign('nets', $reports['nets']);
    $this->smarty->assign('dates', $dates);
    $this->smarty->assign('metric', 'impressions');
    $this->smarty->assign('metricLabel', 'Total Impressions');
    return $this->smarty->fetch('../tpl/www/reports/applicationReportsOneAppCSV.tpl');

  }
  
  public function getHTMLTable() {
    $this->printHeader = false;
    $this->printFooter = false;
    $reports = ReportUtil::getReportsByAid($this->aid, $this->startDate, $this->endDate);
    $dates = array();
    for($i=$this->startDate; $i<=$this->endDate; $i=date('Y-m-d', strtotime("+ 1day", strtotime($i)))) {
      $dates[] = $i;
    }
    fb('getHTMLTable-reports', $reports);
    $this->smarty->assign('reports', $reports);
    $this->smarty->assign('nets', $reports['nets']);
    $this->smarty->assign('dates', $dates);
    $this->smarty->assign('metric', 'impressions');
    $this->smarty->assign('metricLabel', 'Total Impressions');
    return $this->smarty->fetch('../tpl/www/reports/houseAdReportsTable.tpl');    
  }
  
  public function getXML() {
    $this->printHeader = false;
    $this->printFooter = false;

    if($this->aid != null) {
      $app = new App($this->aid);
    } else {
      $apps = AppUtil::getAppsByUid($this->user->id);
      $app = $apps['0'];
    }

    $reports = ReportUtil::getReportsByAid($app->id, $this->startDate, $this->endDate);

    fb("reports",$reports);
    $new_reports = $this->collapseReport($reports);
    fb("nr",$new_reports);
    
    $this->smarty->assign('reports', $new_reports);
    $this->smarty->assign('nets', $reports['nets']);
    $this->smarty->assign('dates', $new_reports['dates']);
    $this->smarty->assign('metric', 'impressions');
    return $this->smarty->fetch('../tpl/www/reports/applicationReportsXML.tpl');
  }
}
