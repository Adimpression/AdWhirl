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

class login extends Webpage {


  public function __construct() {
    parent::__construct();
    $this->jsFiles[] = "/js/validate.js";
  }

  public function __default() {
    return $this->smarty->fetch('../tpl/www/home/login.tpl');
  }
  
  public function login() {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = UserUtil::getUser($email, $password);
    if($user != null) {
      $_SESSION['uid'] = $user['id'];
      $this->redirect('/home/apps/apps');
    } else {
			$this->redirect('/home/home?invalidlogin=true');
    }

    
  }
  public function changePassword() {
    $this->printHeader = false;
    $this->printFooter = false;
    $email = $_POST['email'];
    $password = $_POST['password'];
    $new_password = $_POST['new_password'];
    $user = UserUtil::getUser($email, $password);
    $user = new User($user['id']);
    if($user->id != null) {
      $user->password = User::getHashedPassword($new_password);
      $user->put();
      return 'true';
    } else {
      return 'false';
    }
  }

	public function checkPassword() {
    $this->printHeader = false;
    $this->printFooter = false;

    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = UserUtil::getUser($email, $password);
    if($user != null) {
      return 'true';
    } else {
      return 'false';
    }
		
	}

  public function logout() {
    $_SESSION = array();
    $this->redirect('/');
  }

  public function forgotPassword() {
    return $this->smarty->fetch('../tpl/www/home/forgotPassword.tpl');
  }

  public function isValidLogin() {
    $this->printHeader = false;
    $this->printFooter = false;
    $email = $_REQUEST['email'];
    
    return UserUtil::hasUser($email)=='true'?'false':'true';
  }
  
  public function forgotPasswordProcessed() {
    $email = $_POST['email'];

    $result = UserUtil::setupForgotPassword($email);
        
    return $this->smarty->fetch('../tpl/www/home/forgotPasswordProcessed.tpl');
  }

  public function passwordReset() {
    $ufid = $_GET['ufid'];

    $this->smarty->assign('ufid', $ufid);

    return $this->smarty->fetch('../tpl/www/home/passwordReset.tpl');
  }

  public function passwordResetProcessed() {
    $ufid = $_POST['ufid'];
    $password = $_POST['password'];

    $result = UserUtil::passwordReset($ufid, $password);

    return $this->smarty->fetch('../tpl/www/home/passwordResetProcessed.tpl');
  }
  
  public function requiresUser() {
    return false;
  }
}
