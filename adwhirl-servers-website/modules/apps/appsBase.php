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
require_once('inc/class/AppHouseAdUtil.php');
abstract class appsBase extends Webpage {
  protected $sideNav_current;

  public function __construct() {
    parent::__construct();
    $this->breadcrumbs[] = array('text' => 'App List',
		   'link' => '/apps/apps');
    if(isset($sideNav_current)) {
      $this->smarty->assign('sideNav_current', $sideNav_current);
    }

    $this->tab_current = 'apps';
  }
  

  public function requiresUser() {
    return true;
  }
}
