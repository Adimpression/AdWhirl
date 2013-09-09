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
/** 
 *  PHP Version 5
 *
 *  @category    Amazon
 *  @package     Amazon_SimpleDB
 *  @copyright   Copyright 2008 Amazon Technologies, Inc.
 *  @link        http://aws.amazon.com
 *  @license     http://aws.amazon.com/apache2.0  Apache License, Version 2.0
 *  @version     2009-04-15
 */
/******************************************************************************* 
 *    __  _    _  ___ 
 *   (  )( \/\/ )/ __)
 *   /__\ \    / \__ \
 *  (_)(_) \/\/  (___/
 * 
 *  Amazon Simple DB PHP5 Library
 *  Generated: Mon May 11 15:25:08 PDT 2009
 * 
 */

/**
 *  @see Amazon_SimpleDB_Model
 */
require_once ('Amazon/SimpleDB/Model.php');  

    

/**
 * Amazon_SimpleDB_Model_ListDomainsResult
 * 
 * Properties:
 * <ul>
 * 
 * <li>DomainName: string</li>
 * <li>NextToken: string</li>
 *
 * </ul>
 */ 
class Amazon_SimpleDB_Model_ListDomainsResult extends Amazon_SimpleDB_Model
{


    /**
     * Construct new Amazon_SimpleDB_Model_ListDomainsResult
     * 
     * @param mixed $data DOMElement or Associative Array to construct from. 
     * 
     * Valid properties:
     * <ul>
     * 
     * <li>DomainName: string</li>
     * <li>NextToken: string</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'DomainName' => array('FieldValue' => array(), 'FieldType' => array('string')),
        'NextToken' => array('FieldValue' => null, 'FieldType' => 'string'),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the DomainName .
     * 
     * @return array of string DomainName
     */
    public function getDomainName() 
    {
        return $this->_fields['DomainName']['FieldValue'];
    }

    /**
     * Sets the value of the DomainName.
     * 
     * @param string or an array of string DomainName
     * @return this instance
     */
    public function setDomainName($domainName) 
    {
        if (!$this->_isNumericArray($domainName)) {
            $domainName =  array ($domainName);    
        }
        $this->_fields['DomainName']['FieldValue'] = $domainName;
        return $this;
    }
  

    /**
     * Sets single or multiple values of DomainName list via variable number of arguments. 
     * For example, to set the list with two elements, simply pass two values as arguments to this function
     * <code>withDomainName($domainName1, $domainName2)</code>
     * 
     * @param string  $stringArgs one or more DomainName
     * @return Amazon_SimpleDB_Model_ListDomainsResult  instance
     */
    public function withDomainName($stringArgs)
    {
        foreach (func_get_args() as $domainName) {
            $this->_fields['DomainName']['FieldValue'][] = $domainName;
        }
        return $this;
    }  
      

    /**
     * Checks if DomainName list is non-empty
     * 
     * @return bool true if DomainName list is non-empty
     */
    public function isSetDomainName()
    {
        return count ($this->_fields['DomainName']['FieldValue']) > 0;
    }

    /**
     * Gets the value of the NextToken property.
     * 
     * @return string NextToken
     */
    public function getNextToken() 
    {
        return $this->_fields['NextToken']['FieldValue'];
    }

    /**
     * Sets the value of the NextToken property.
     * 
     * @param string NextToken
     * @return this instance
     */
    public function setNextToken($value) 
    {
        $this->_fields['NextToken']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the NextToken and returns this instance
     * 
     * @param string $value NextToken
     * @return Amazon_SimpleDB_Model_ListDomainsResult instance
     */
    public function withNextToken($value)
    {
        $this->setNextToken($value);
        return $this;
    }


    /**
     * Checks if NextToken is set
     * 
     * @return bool true if NextToken  is set
     */
    public function isSetNextToken()
    {
        return !is_null($this->_fields['NextToken']['FieldValue']);
    }




}
