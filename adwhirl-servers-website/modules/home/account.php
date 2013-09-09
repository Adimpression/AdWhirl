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

require_once('inc/class/User.php');

class account extends Webpage {
  public function __default() {
	  $this->smarty->assign('returnPage',$_SERVER['HTTP_REFERER']);  
    $this->breadcrumbs[] = array('text' => 'Account Settings',
		   'link' => '/home/account');
    $this->smarty->assign("user",$this->user);
    $this->jsFiles[] = "/js/jquery.validate.min.js";    
  	$this->jsFiles[] = "/js/jqsm135.js";
    return $this->smarty->fetch('../tpl/www/home/account.tpl');
  }
  public function setPref() {
    $this->user->postGet();    
    $this->user->setPref($_REQUEST['key'], $_REQUEST['value']);
    $this->user->put();
  }
  public function update() {
    $email = $_POST['email'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];

//    $oldPassword = $_POST['oldPassword'];
//    $newPassword = $_POST['newPassword'];

    $allowEmail = isset($_POST['allowEmail']) ? 1 : 0;

    $this->user->email = $email;
    $this->user->firstName = $firstName;
    $this->user->lastName = $lastName;

//    if($this->user->password == $this->user->getHashedPassword($oldPassword)) {
//      $this->user->password = $this->user->getHashedPassword($newPassword);
//    }

    $this->user->allowEmail = $allowEmail;

    $this->user->put();
    // return $this->__default();
		$this->redirect($_REQUEST['returnPage']);    

  }

  public function delete() {
  
    $this->smarty->assign("user",$this->user);
    return $this->smarty->fetch('../tpl/www/home/delete.tpl');
  }

  public function deleteConfirmed() {
    $this->smarty->assign("user",$this->user);
    $this->smarty->assign("confirmDelete",TRUE);
    $this->user->delete();
    return $this->smarty->fetch('../tpl/www/home/delete.tpl');
  }
  
  public function requiresUser() {
    return true;
  }
}
