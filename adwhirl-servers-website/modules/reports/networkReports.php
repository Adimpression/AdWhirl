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

require_once('modules/reports/reportsBase.php');

class networkReports extends reportsBase {
  public function __construct() {
    parent::__construct();
    $this->breadcrumbs[] = array('text' => 'Networks',
		   'link' => '/reports/networkReports');
  }

  public function __default() {
    return $this->networkReports();
  }

  public function networkReports() {

    $reports = ReportUtil::getNetworkReportsByUid($this->user->id, $this->startDate, $this->endDate);

    $this->subtitle = "Reporting";
    $this->smarty->assign('sideNav_current', 'networks');
    $this->smarty->assign('reports', $reports);
    $this->smarty->assign('dataURL', 'http://'.$_SERVER['SERVER_NAME'].'/reports/networkReports/getXML');

    return $this->smarty->fetch('../tpl/www/reports/networkReports.tpl');
  }
  
  public function getTable() {

      $reports = ReportUtil::getNetworkReportsByUid($this->user->id, $this->startDate, $this->endDate);

      $dates = array();
      for($i=$this->startDate; $i<=$this->endDate; $i=date('Y-m-d', strtotime('+1 day', strtotime($i)))) {
        $dates[] = $i;
      }
      var_dump($reports);
      die();
      $networks = array();
      foreach($reports['types'] as $type => $discard) {
        $networks[$type] = isset(Network::$NETWORKS[$type]) ? Network::$NETWORKS[$type]['name'] : 'Unknown';
      }

      $this->smarty->assign('networks', $networks);
      $this->smarty->assign('reports', $reports);
      $this->smarty->assign('dates', $dates);
  }
  
  public function getXML() {
    $this->printHeader = false;
    $this->printFooter = false;
    

    $reports = ReportUtil::getNetworkReportsByUid($this->user->id, $this->startDate, $this->endDate);

    $dates = array();
    for($i=$this->startDate; $i<=$this->endDate; $i=date('Y-m-d', strtotime('+1 day', strtotime($i)))) {
      $dates[] = $i;
    }

    $networks = array();
    foreach($reports['types'] as $type => $discard) {
      $networks[$type] = isset(Network::$NETWORKS[$type]) ? Network::$NETWORKS[$type]['name'] : 'Unknown';
    }

    $this->smarty->assign('networks', $networks);
    $this->smarty->assign('reports', $reports);
    $this->smarty->assign('dates', $dates);

    return $this->smarty->fetch('../tpl/www/reports/networkReportsXML.tpl');
  }
}
