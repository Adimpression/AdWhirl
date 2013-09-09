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

class home extends Webpage {
  public function __default() {
    if(isset($_SESSION['uid'])) {
      $this->redirect('/apps/apps');
    }

    // $this->printHeader = false;
    // $this->printFooter = false;
    $this->tab_current = 'home';
		if (isset($_REQUEST['invalidlogin'])) {
			$this->smarty->assign('invalidlogin','true');
		}
    return $this->smarty->fetch('../tpl/www/home/new_home.tpl');
  }
  
  public function debug() {
	$_SESSION['adwhirl_debug']='true';
	return "Debug Set";
  }
  public function non_debug() {
	unset($_SESSION['adwhirl_debug']);
	return "Debug Unset";
  }
  public function requiresUser() {
    return false;
  }
}
