<?php
// strip www.adwhirl.com from the current path to get to the root directory of adwhirl
$r_path = str_replace("www.adwhirl.com", "", getcwd());

$aws_path = $r_path . 'inc/class/amazon-simpledb-2009-04-15-php5-library/src';

set_include_path($r_path. PATH_SEPARATOR . $aws_path . PATH_SEPARATOR . get_include_path());

require_once('inc/class/Webpage.php');

$request_path = '';
if(isset($_SERVER['PATH_INFO']) && strlen($_SERVER['PATH_INFO']) > 1) {
  $request_path = substr($_SERVER['PATH_INFO'], 1);
}

$path_components = $path_components_saved = explode('/', $request_path);

if(!empty($path_components[0])) {
  $module = $path_components[0];
 }
if(!empty($path_components[1])) {
  $class = $path_components[1];
 }
if(!empty($path_components[2])) {
  $event = $path_components[2];
 }
$params = array_slice($path_components, 3);

if(empty($module)) {
  $module = 'home';
  $class = 'home';
}

if(empty($class)) { // redirect 404's to home
  header('Location: /');
  die();
}

if(empty($event)) { 
  $event = '__default'; 
}

$class_file = '../modules/' .$module .'/' . $class . '.php';

if(!file_exists($class_file)) {
  header('Location: /');
  die();
}

include_once($class_file);

try {
  $instance = new $class($path_components_saved);
  if ($instance->authenticate()) {
    $result = $instance->$event(); 

    echo $instance->pageHeader();
    echo $result;
    echo $instance->pageFooter();
  }
  else { // no user
    header('Location: /');
    die();
  }
} catch (Exception $error) {
  header('Location: /');
  die();
}
