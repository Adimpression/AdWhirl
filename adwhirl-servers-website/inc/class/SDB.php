<?php
/*
 -----------------------------------------------------------------------
Copyright 2009 AdMob, Inc.

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

require_once (".config.inc.php");
require_once ("amazon-simpledb-2009-04-15-php5-library/src/Amazon/SimpleDB/Client.php");
require_once ("amazon-simpledb-2009-04-15-php5-library/src/Amazon/SimpleDB/Model/SelectRequest.php");
require_once ("amazon-simpledb-2009-04-15-php5-library/src/Amazon/SimpleDB/Model/PutAttributesRequest.php");
require_once ("amazon-simpledb-2009-04-15-php5-library/src/Amazon/SimpleDB/Model/ReplaceableAttribute.php");
require_once ("amazon-simpledb-2009-04-15-php5-library/src/Amazon/SimpleDB/Model/DeleteAttributesRequest.php");
require_once ("amazon-simpledb-2009-04-15-php5-library/src/Amazon/SimpleDB/Model/Attribute.php");

class SDB {
  public $service;

  private static $instance = null;

  private static $SDB_ACCESS_KEY_ID = 
  	  'CHANGEME';


  private static $SDB_SECRET_ACCESS_KEY = 
  	  'CHANGEME';


  public static function uuid() {
    return sprintf( '%04x%04x%04x%04x%04x%04x%04x%04x',
		    mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
		    mt_rand( 0, 0x0fff ) | 0x4000,
		    mt_rand( 0, 0x3fff ) | 0x8000,
		    mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ) );
  }

  public function getInstance() {
    if(self::$instance == null) {
      self::$instance = new SDB();
    }
     
    return self::$instance;
  }
  
  private function __construct() {
    $this->service = new Amazon_SimpleDB_Client(self::$SDB_ACCESS_KEY_ID, self::$SDB_SECRET_ACCESS_KEY);
  }
  
  public function select($domain, &$aaa, $where, $showAliveOnly=true, $attempts=2) {
    if ($attempts<=0) return;
    if ($showAliveOnly) {
      if(empty($where)) {
	$where = "where deleted is null";
      }
      else {
	$where .= " intersection deleted is null";
      }
    }
    if(is_array($aaa)) {
      $select = "select `".implode("`, `", $aaa)."` from `".$domain."` ".$where;
    }
    else {
      $select = "select $aaa from `$domain` $where";
    }
    fb($select,"");
    $aaa = array();
        
    $selectRequest = new Amazon_SimpleDB_Model_SelectRequest();
    $selectRequest->setSelectExpression($select);
        
    try {
      do {
	$response = $this->service->select($selectRequest);
            
	if ($response->isSetSelectResult()) {
            
	  $selectResult = $response->getSelectResult();
	  $itemList = $selectResult->getItem();
	  foreach ($itemList as $item) {
	    $aa = array();
                    
	    if ($item->isSetName()) {
	      $aa['id'] = $item->getName();
	    }
                    
	    $attributeList = $item->getAttribute();
	    foreach ($attributeList as $attribute) {
	      if ($attribute->isSetName()) {
		if ($attribute->isSetValue()) {
		  $aa[$attribute->getName()] = $attribute->getValue();
		}
	      }
	    }
                    
	    array_push($aaa, $aa);
	    
	    $selectRequest->setNextToken($selectResult->getNextToken());                    
	  }
	}
      }
      while($selectResult->isSetNextToken());

      return true;
    }
    catch(Amazon_SimpleDB_Exception $ex) {
      select($domain, $aaa, $where, $showAliveOnly, $attempts-1);
      // echo("Caught Exception: ".$ex->getMessage()."<br />\n");
      // echo("Response Status Code: ".$ex->getStatusCode()."<br />\n");
      // echo("Error Code: ".$ex->getErrorCode()."<br />\n");
      // echo("Error Type: ".$ex->getErrorType()."<br />\n");
      // echo("Request ID: ".$ex->getRequestId()."<br />\n");
      // echo("XML: ".$ex->getXML()."<br />\n");
      // echo("Select: $select<br />\n");
    }

    return false;
  }

  public function get($domain, $id, &$aaa) {
    $where = "where itemName() = '$id' limit 1";
    
    if(is_array($aaa)) {
      $select = "select `".implode("`, `", $aaa)."` from `".$domain."` ".$where;
    }
    else {
      $select = "select $aaa from `$domain` $where";
    }
    
    $aaa = array();
    
    $selectRequest = new Amazon_SimpleDB_Model_SelectRequest();
    $selectRequest->setSelectExpression($select);
        
    try {
      $response = $this->service->select($selectRequest);
      
      if ($response->isSetSelectResult()) {
	$selectResult = $response->getSelectResult();

	$itemList = $selectResult->getItem();
	if(empty($itemList)) {
	  return null;
	}

	$item = $itemList[0];
	if ($item->isSetName()) {
	  $aa['id'] = $item->getName();
	}
	
	$attributeList = $item->getAttribute();
	foreach ($attributeList as $attribute) {
	  if ($attribute->isSetName()) {
	    if ($attribute->isSetValue()) {
	      $aa[$attribute->getName()] = $attribute->getValue();
	    }
	  }
	}
      }

      $aaa = $aa;

      return true;
    }
    catch(Amazon_SimpleDB_Exception $ex) {
      echo("Caught Exception: ".$ex->getMessage()."<br />\n");
      echo("Response Status Code: ".$ex->getStatusCode()."<br />\n");
      echo("Error Code: ".$ex->getErrorCode()."<br />\n");
      echo("Error Type: ".$ex->getErrorType()."<br />\n");
      echo("Request ID: ".$ex->getRequestId()."<br />\n");
      echo("XML: ".$ex->getXML()."<br />\n");
      echo("Select: $select<br />\n");
    }

    return false;
  }

  public function put($domain, $itemName, $aa, $replace) 
  {
    $request = new Amazon_SimpleDB_Model_PutAttributesRequest();

    $attributes = array();

    foreach($aa as $key => $value) {
      $attribute = new Amazon_SimpleDB_Model_ReplaceableAttribute();
      $attribute->setName($key);
      $attribute->setValue($value);
      $attribute->setReplace($replace);
      array_push($attributes, $attribute);
    }

    $request->setAttribute($attributes);
    $request->setDomainName($domain);
    $request->setItemName($itemName);

    try {
      $this->service->putAttributes($request);
      return true;
    } 
    catch (Amazon_SimpleDB_Exception $ex) {
      echo("Caught Exception: " . $ex->getMessage() . "<br />\n");
      echo("Response Status Code: " . $ex->getStatusCode() . "<br />\n");
      echo("Error Code: " . $ex->getErrorCode() . "<br />\n");
      echo("Error Type: " . $ex->getErrorType() . "<br />\n");
      echo("Request ID: " . $ex->getRequestId() . "<br />\n");
      echo("XML: " . $ex->getXML() . "<br />\n");
    }

    return false;
  }

  public function delete($domain, $itemName)
  {
    $request = new Amazon_SimpleDB_Model_DeleteAttributesRequest();
    $request->withDomainName($domain);
    $request->withItemName($itemName);

    try {
      $response = $this->service->deleteAttributes($request);
      return true;
    } 
    catch (Amazon_SimpleDB_Exception $ex) {
      echo("Caught Exception: " . $ex->getMessage() . "<br />\n");
      echo("Response Status Code: " . $ex->getStatusCode() . "<br />\n");
      echo("Error Code: " . $ex->getErrorCode() . "<br />\n");
      echo("Error Type: " . $ex->getErrorType() . "<br />\n");
      echo("Request ID: " . $ex->getRequestId() . "<br />\n");
      echo("XML: " . $ex->getXML() . "<br />\n");
    }

    return false;
  }
}
