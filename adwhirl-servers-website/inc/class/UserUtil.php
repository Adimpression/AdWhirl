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
require_once('inc/class/SDB.php');

class UserUtil {
  public static $DOMAIN_USERS_FORGOT = "users_forgot";
  public static $DOMAIN_USERS_UNVERIFIED = "users_unverified";

  public static function getUser($email, $password=null) {
    $sdb = SDB::getInstance();
    $domain = User::$SDBDomain;

    $aaa = array();
    foreach(User::$SDBFields as $field => $meta) {
      $aaa[] = $field;
    }
    
    $email_l = strtolower($email);

    if($password === null) {
      $where = "where `email` = '$email' or `email` = '$email_l'";
    }
    else {
      $password = User::getHashedPassword($password);
      $where = "where (`email` = '$email' or `email` = '$email_l') and `password` = '$password'";
    }

    if(!$sdb->select($domain, $aaa, $where)) {
      return null;
    }

    if(empty($aaa)) {
      return null;
    }

    $user = array();
    foreach(User::$SDBFields as $field => $meta) {
      $user[$field] = isset($aaa[0][$field]) ? $aaa[0][$field] : '';
    }
    $user['id'] = $aaa[0]['id'];

    return $user;
  }
	public static function hasUser($email) {
    $sdb = SDB::getInstance();
    $user = UserUtil::getUser($email);
		return $user!=NULL?'true':'false';
	}
  public static function setupForgotPassword($email) {
    $sdb = SDB::getInstance();

    $user = UserUtil::getUser($email);
    
    $ufid = SDB::uuid();
    
    $aa = array('uid' => $user['id'],
                'createdAt' => date('Y-m-d'));
    
    if(!$sdb->put(self::$DOMAIN_USERS_FORGOT, $ufid, $aa, true)) {
      return false;
    }
    
    $activationLink = 'http://'.$_SERVER['HTTP_HOST'].'/home/login/passwordReset?ufid='.$ufid;
    
    $to      = $email;
    $subject = 'AdWhirl Password Reset';
    $message = 'Hello AdWhirl User,
    
        We received a request to reset your password. Click on the link below to set up a new password for your account.
        
        '.$activationLink.'
        
        If you did not request to reset your password, ignore this email - the link will expire on its own.
        
        Best,
        AdWhirl Team
        ';
    
    mail($to, $subject, $message);

    return true;
  }

  public static function passwordReset($ufid, $password) {
    $sdb = SDB::getInstance();

    $aaa = 'uid';
    if($sdb->select(self::$DOMAIN_USERS_FORGOT, $aaa, "where itemName() = '$ufid'")) {
      $uid = $aaa[0]['uid'];

      $user = new User($uid);

      $user->password = md5($password.User::$PASSWORD_SALT);
      $user->put();

      $sdb->delete(self::$DOMAIN_USERS_FORGOT, $ufid);

      return true;
    }

    return false;
  }

  public static function registerNewUser($email, $firstName, $lastName, $password, $allowEmail) {
    $sdb = SDB::getInstance();

    $user = new User($email);

    if($user->id != null) {
      return false;
    }

    $uid = SDB::uuid();

    $password = User::getHashedPassword($password);

    $aa = array('email' => $email,
		  'password' => $password,
		  'allowEmail' => $allowEmail,
      'firstName' => $firstName,
      'lastName' => $lastName,
      'createdAt' => date('Y-m-d'));
    $sdb->put(self::$DOMAIN_USERS_UNVERIFIED, $uid, $aa, true);

    $activationLink = 'http://'.$_SERVER['HTTP_HOST'].'/home/register/confirm?uid='.$uid;
    
    $to      = $email;
    $subject = 'AdWhirl Account Registration';
		$message = "Hello iPhone and Android Developer,

Thanks for registering with AdWhirl. Click on the link below to validate your email address and activate your account.

$activationLink

We hope that you’ll find AdWhirl’s mediation very helpful in managing your ad inventory, as you can simultaneously run as many or as few ad networks as you’d like, and you’re also able to create and run your own house ads whenever you want to – all for free – and all open source.

We are spending a lot of time perfecting the library and the interface, and giving you, the developer, as many hooks as possible to optimize revenue. We’d love to hear from you with suggestions, feedback, anything! You can also contribute directly to the AdWhirl Open Source community on our project page (http://code.google.com/p/adwhirl/).

If you have any questions, or trouble logging in, please email us at support@adwhirl.com. 

Happy Advertising!

AdWhirl Team";
    mail($to, $subject, $message);

    return true;
  }

  public static function confirmUser($uid) {
    $sdb = SDB::getInstance();
    
    $aaa = array('email', 'firstName','lastName', 'password', 'allowEmail');
    $sdb->select(self::$DOMAIN_USERS_UNVERIFIED, $aaa, "where itemName() = '$uid' limit 1", false);

    if(empty($aaa[0])) {
      return false;
    }
    
    $user = new User();
    $user->id = $uid;
    $user->email = $aaa[0]['email'];
    $user->password = $aaa[0]['password'];
    $user->allowEmail = $aaa[0]['allowEmail'];
    $user->firstName = $aaa[0]['firstName'];
    $user->lastName = $aaa[0]['lastName'];
    
    $user->put();
    
    return true;
  }    
}
