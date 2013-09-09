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

require_once('inc/class/SDB.php');

abstract class SDBObject {
  protected $sdb;

  function __construct($id=null) {
    if($id === null) {
      return;
    }

    $domain = $this->getSDBDomain();
    
    $fields = array();
    foreach($this->getSDBFields() as $field => $meta) {
      $fields[] = $field;
    }
    
    $this->sdb = SDB::getInstance();
    if(!$this->sdb->get($domain, $id, $fields)) {
      return null;
    }
		
    foreach($fields as $key => $value) {
      $this->$key = $value;
    }
  }

  function put() {
		// fb("sdbo - put $this->id",$this);
    $id = $this->id;
    $domain = $this->getSDBDomain();
    
    $fields = array();
    foreach($this->getSDBFields() as $field => $meta) {
      $fields[$field] = $this->$field===NULL?'':$this->$field;
    }

    $this->sdb = SDB::getInstance();
    $this->sdb->put($domain, $id, $fields, true);
  }

  function delete() {
    $id = $this->id;
		// fb("sdbo - delete $this->id");

    $domain = $this->getSDBDomain();

    $this->sdb = SDB::getInstance();
    
    $fields = array('deleted' => date('Y-m-d'));
    
    // Soft delete
    $this->sdb->put($domain, $id, $fields, true);
    //    $this->sdb->delete($domain, $id);
  }
}
