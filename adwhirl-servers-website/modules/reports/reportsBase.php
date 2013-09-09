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

require_once('inc/class/ReportUtil.php');

function sortByName($a, $b) {
  if ($a->deleted && !$b->deleted) return 1;
  if (!$a->deleted && $b->deleted) return -1;  
  if (strtolower($a->name) == strtolower($b->name)) {
    return 0;
  }
  return (strtolower($a->name) < strtolower($b->name)) ? -1 : 1;
}


abstract class reportsBase extends Webpage {
  protected $startDate;
  protected $endDate;
  protected $interval;
  protected $dateOptions;
  protected $selectedDate;
  public function __construct() {
    parent::__construct();
    if (!isset($_GET['end]'])&&!isset($_GET['start']) && isset($_SESSION['start']) && isset($_SESSION['end'])) {
      $this->endDate = $_SESSION['end'];
      $this->startDate = $_SESSION['start'];
    } else {
      $this->endDate = isset($_GET['end']) ? $_GET['end'] :date('Y-m-d');    
      $this->startDate = isset($_GET['start']) ? $_GET['start'] :date('Y-m-d', strtotime('-7 day'));			
    }
    $_SESSION['start'] = $this->startDate;
    $_SESSION['end'] = $this->endDate;
    fb($_SESSION);
    $this->selectedDate = '?start='. $this->startDate.'&end='.$this->endDate;
    $days = (strtotime($this->endDate, 0) - strtotime($this->startDate, 0))/(60*60*24);
    if ($days<2) {
      $this->interval = '+ 3hour';
    } elseif ($days<8) {
      $this->interval = '+ 1day';
    } elseif ($days<31) {
      $this->interval = '+ 3day';
    } elseif ($days<91) {
      $this->interval = '+ 15day';
    } elseif ($days<366) {
      $this->interval = '+ 30day';
    } elseif ($days<730) {
      $this->interval = '+ 60day';
    } else {
      $this->interval = '+ ' . floor($days/365)*30 . 'day';
    }
    fb("interval",$this->interval);
    $this->dateOptions = array(
			       // '?start=' . date('Y-m-d') . '&end=' . date('Y-m-d',strtotime('+1 day')) => 'Today',
			       // '?start=' . date('Y-m-d',strtotime('-1 day')) . '&end=' . date('Y-m-d') => 'Yesterday',
			       '?start=' . date('Y-m-d', strtotime('-7 day')) . '&end=' . date('Y-m-d') => 'Last 7 days',
			       '?start=' . date('Y-m-d', strtotime('-30 day')) . '&end=' . date('Y-m-d') => 'Last 30 days',
			       '?start=' . date('Y-m-d', strtotime('-90 day')) . '&end=' . date('Y-m-d') => 'Last 90 days',
			       '?start=' . date('Y-m-d', strtotime('-365 day')) . '&end=' . date('Y-m-d') => 'Last 365 days'
			       );
			
    $this->breadcrumbs[] =   array('text' => 'Reports',
				   'link' => '/reports/applicationReports');
    $this->tab_current = 'reports';
    $this->jsFiles[] = "/FusionCharts/FusionCharts.js";
    
  }
  public function showDeleted() {
    $this->printHeader = false;
    $this->printFooter = false;
    $_SESSION['showDeleted'] = true;
    return "OK";
  }
  
  public function notShowDeleted() {
    unset($_SESSION['showDeleted']);
  }
  public function collapseReport($reports) {
    $dates = array();
    $new_reports = array();
    $preFilled = array('0'=>array('impressions'=>0,'clicks'=>0,'ctr'=>0));
    
    if($reports != null) {
      foreach ($reports['nets'] as $k => $v) {
	$preFilled[$k] = 	$preFilled['0'];
      }
    }

    fb("pf",$preFilled);
    for($i=$this->startDate; $i<=$this->endDate; $i=date('Y-m-d', strtotime($this->interval, strtotime($i)))) {
      $dates[] = $i;
      $new_reports[$i] = $preFilled;
    }

    $showDates = $dates;
    fb("showDates",$showDates);
    $showDate = array_pop($showDates);
    for($i=$this->endDate; $i>=$this->startDate; $i=date('Y-m-d', strtotime("-1 day", strtotime($i)))) {
      if($reports != null) {
	if (array_key_exists($i,$reports)) {
	  foreach ($reports[$i] as $k1 => $v1) {
	    foreach ($v1 as $k2 => $v2) {
	      $new_reports[$showDate][$k1][$k2] += $v2;
	    }
	  }
	}
      }
      if ($i==$showDate) {
	      $showDate = array_pop($showDates);
      }
    }
    $dateLabels = array();
    foreach ($dates as $date) {
      if ($this->interval != '+ 1day') {
        $dateLabels[$date] = $date . ' - ' . date('Y-m-d', strtotime('- 1day', strtotime(date('Y-m-d', strtotime($this->interval, strtotime($date))))));
      } else {
        $dateLabels[$date] = $date;
      }      
    }
    $new_reports['dates'] = $dateLabels;
    
    return $new_reports;
  }
  public function calculateCTR(&$new_reports) {
    if (isset($new_reports)) {
      foreach ($new_reports as $date => $stats) {
	if($date == 'dates') {
	  continue;
	}

	foreach ($stats as $type => $stat) {
	  if ($new_reports[$date][$type]['impressions']!=0) {
	    $new_reports[$date][$type]['ctr'] = ($new_reports[$date][$type]['clicks'] * 100.0) / ($new_reports[$date][$type]['impressions']);
	  }
	}
      }
    }
  }
  public function requiresUser() {
    return true;
  }
}
