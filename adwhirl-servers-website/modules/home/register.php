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

require_once('inc/class/UserUtil.php');

class register extends Webpage {
  public function __construct() {
    parent::__construct();
    $this->jsFiles[] = "/js/validate.js";
		$this->jsFiles[] = "/js/jquery.validate.min.js";
  }

  public function __default() {
		$this->subtitle = "Sign Up";
    return $this->smarty->fetch('../tpl/www/home/register.tpl');
		
  }

  public function registerProcessed() {
	  $email = strtolower($_POST['email']);
    $password = $_POST['password'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $allowEmail = isset($_POST['allowEmail']) ? 1 : 0;
	  $result = UserUtil::registerNewUser($email, $firstName, $lastName, $password, $allowEmail);
    $u = UserUtil::getUser($email);

    return $this->smarty->fetch('../tpl/www/home/registerProcessed.tpl');
  }
  
  public function confirm() {
		$_SESSION = array();
    $uid = $_GET['uid'];
    $result = UserUtil::confirmUser($uid);

    return $this->smarty->fetch('../tpl/www/home/confirmProcessed.tpl');
  }
 
  public function requiresUser() {
    return false;
  }
}
