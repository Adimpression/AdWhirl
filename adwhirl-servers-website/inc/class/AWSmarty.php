<?php

require_once 'inc/smarty/Smarty.class.php';

class AWSmarty extends Smarty
{
  public function __construct() {
    $this->template_dir = '.';
    $this->compile_dir = '../inc/smarty/templates_c';
    $this->cache_dir = '../inc/smarty/cache';
    $this->config_dir = '../inc/smarty/configs';

    if(true) {
      $this->force_compile = true;
    } else {
      $this->compile_check = false; 
    }
  }
}

