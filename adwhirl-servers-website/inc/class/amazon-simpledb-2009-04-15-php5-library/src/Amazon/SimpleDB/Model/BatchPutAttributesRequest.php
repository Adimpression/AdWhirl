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
 * Amazon_SimpleDB_Model_BatchPutAttributesRequest
 * 
 * Properties:
 * <ul>
 * 
 * <li>DomainName: string</li>
 * <li>Item: Amazon_SimpleDB_Model_ReplaceableItem</li>
 *
 * </ul>
 */ 
class Amazon_SimpleDB_Model_BatchPutAttributesRequest extends Amazon_SimpleDB_Model
{


    /**
     * Construct new Amazon_SimpleDB_Model_BatchPutAttributesRequest
     * 
     * @param mixed $data DOMElement or Associative Array to construct from. 
     * 
     * Valid properties:
     * <ul>
     * 
     * <li>DomainName: string</li>
     * <li>Item: Amazon_SimpleDB_Model_ReplaceableItem</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'DomainName' => array('FieldValue' => null, 'FieldType' => 'string'),
        'Item' => array('FieldValue' => array(), 'FieldType' => array('Amazon_SimpleDB_Model_ReplaceableItem')),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the DomainName property.
     * 
     * @return string DomainName
     */
    public function getDomainName() 
    {
        return $this->_fields['DomainName']['FieldValue'];
    }

    /**
     * Sets the value of the DomainName property.
     * 
     * @param string DomainName
     * @return this instance
     */
    public function setDomainName($value) 
    {
        $this->_fields['DomainName']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the DomainName and returns this instance
     * 
     * @param string $value DomainName
     * @return Amazon_SimpleDB_Model_BatchPutAttributesRequest instance
     */
    public function withDomainName($value)
    {
        $this->setDomainName($value);
        return $this;
    }


    /**
     * Checks if DomainName is set
     * 
     * @return bool true if DomainName  is set
     */
    public function isSetDomainName()
    {
        return !is_null($this->_fields['DomainName']['FieldValue']);
    }

    /**
     * Gets the value of the Item.
     * 
     * @return array of ReplaceableItem Item
     */
    public function getItem() 
    {
        return $this->_fields['Item']['FieldValue'];
    }

    /**
     * Sets the value of the Item.
     * 
     * @param mixed ReplaceableItem or an array of ReplaceableItem Item
     * @return this instance
     */
    public function setItem($item) 
    {
        if (!$this->_isNumericArray($item)) {
            $item =  array ($item);    
        }
        $this->_fields['Item']['FieldValue'] = $item;
        return $this;
    }


    /**
     * Sets single or multiple values of Item list via variable number of arguments. 
     * For example, to set the list with two elements, simply pass two values as arguments to this function
     * <code>withItem($item1, $item2)</code>
     * 
     * @param ReplaceableItem  $replaceableItemArgs one or more Item
     * @return Amazon_SimpleDB_Model_BatchPutAttributesRequest  instance
     */
    public function withItem($replaceableItemArgs)
    {
        foreach (func_get_args() as $item) {
            $this->_fields['Item']['FieldValue'][] = $item;
        }
        return $this;
    }   



    /**
     * Checks if Item list is non-empty
     * 
     * @return bool true if Item list is non-empty
     */
    public function isSetItem()
    {
        return count ($this->_fields['Item']['FieldValue']) > 0;
    }




}
