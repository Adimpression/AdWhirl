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
 * Amazon_SimpleDB_Model_DomainMetadataResult
 * 
 * Properties:
 * <ul>
 * 
 * <li>ItemCount: string</li>
 * <li>ItemNamesSizeBytes: string</li>
 * <li>AttributeNameCount: string</li>
 * <li>AttributeNamesSizeBytes: string</li>
 * <li>AttributeValueCount: string</li>
 * <li>AttributeValuesSizeBytes: string</li>
 * <li>Timestamp: string</li>
 *
 * </ul>
 */ 
class Amazon_SimpleDB_Model_DomainMetadataResult extends Amazon_SimpleDB_Model
{


    /**
     * Construct new Amazon_SimpleDB_Model_DomainMetadataResult
     * 
     * @param mixed $data DOMElement or Associative Array to construct from. 
     * 
     * Valid properties:
     * <ul>
     * 
     * <li>ItemCount: string</li>
     * <li>ItemNamesSizeBytes: string</li>
     * <li>AttributeNameCount: string</li>
     * <li>AttributeNamesSizeBytes: string</li>
     * <li>AttributeValueCount: string</li>
     * <li>AttributeValuesSizeBytes: string</li>
     * <li>Timestamp: string</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'ItemCount' => array('FieldValue' => null, 'FieldType' => 'string'),
        'ItemNamesSizeBytes' => array('FieldValue' => null, 'FieldType' => 'string'),
        'AttributeNameCount' => array('FieldValue' => null, 'FieldType' => 'string'),
        'AttributeNamesSizeBytes' => array('FieldValue' => null, 'FieldType' => 'string'),
        'AttributeValueCount' => array('FieldValue' => null, 'FieldType' => 'string'),
        'AttributeValuesSizeBytes' => array('FieldValue' => null, 'FieldType' => 'string'),
        'Timestamp' => array('FieldValue' => null, 'FieldType' => 'string'),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the ItemCount property.
     * 
     * @return string ItemCount
     */
    public function getItemCount() 
    {
        return $this->_fields['ItemCount']['FieldValue'];
    }

    /**
     * Sets the value of the ItemCount property.
     * 
     * @param string ItemCount
     * @return this instance
     */
    public function setItemCount($value) 
    {
        $this->_fields['ItemCount']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the ItemCount and returns this instance
     * 
     * @param string $value ItemCount
     * @return Amazon_SimpleDB_Model_DomainMetadataResult instance
     */
    public function withItemCount($value)
    {
        $this->setItemCount($value);
        return $this;
    }


    /**
     * Checks if ItemCount is set
     * 
     * @return bool true if ItemCount  is set
     */
    public function isSetItemCount()
    {
        return !is_null($this->_fields['ItemCount']['FieldValue']);
    }

    /**
     * Gets the value of the ItemNamesSizeBytes property.
     * 
     * @return string ItemNamesSizeBytes
     */
    public function getItemNamesSizeBytes() 
    {
        return $this->_fields['ItemNamesSizeBytes']['FieldValue'];
    }

    /**
     * Sets the value of the ItemNamesSizeBytes property.
     * 
     * @param string ItemNamesSizeBytes
     * @return this instance
     */
    public function setItemNamesSizeBytes($value) 
    {
        $this->_fields['ItemNamesSizeBytes']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the ItemNamesSizeBytes and returns this instance
     * 
     * @param string $value ItemNamesSizeBytes
     * @return Amazon_SimpleDB_Model_DomainMetadataResult instance
     */
    public function withItemNamesSizeBytes($value)
    {
        $this->setItemNamesSizeBytes($value);
        return $this;
    }


    /**
     * Checks if ItemNamesSizeBytes is set
     * 
     * @return bool true if ItemNamesSizeBytes  is set
     */
    public function isSetItemNamesSizeBytes()
    {
        return !is_null($this->_fields['ItemNamesSizeBytes']['FieldValue']);
    }

    /**
     * Gets the value of the AttributeNameCount property.
     * 
     * @return string AttributeNameCount
     */
    public function getAttributeNameCount() 
    {
        return $this->_fields['AttributeNameCount']['FieldValue'];
    }

    /**
     * Sets the value of the AttributeNameCount property.
     * 
     * @param string AttributeNameCount
     * @return this instance
     */
    public function setAttributeNameCount($value) 
    {
        $this->_fields['AttributeNameCount']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the AttributeNameCount and returns this instance
     * 
     * @param string $value AttributeNameCount
     * @return Amazon_SimpleDB_Model_DomainMetadataResult instance
     */
    public function withAttributeNameCount($value)
    {
        $this->setAttributeNameCount($value);
        return $this;
    }


    /**
     * Checks if AttributeNameCount is set
     * 
     * @return bool true if AttributeNameCount  is set
     */
    public function isSetAttributeNameCount()
    {
        return !is_null($this->_fields['AttributeNameCount']['FieldValue']);
    }

    /**
     * Gets the value of the AttributeNamesSizeBytes property.
     * 
     * @return string AttributeNamesSizeBytes
     */
    public function getAttributeNamesSizeBytes() 
    {
        return $this->_fields['AttributeNamesSizeBytes']['FieldValue'];
    }

    /**
     * Sets the value of the AttributeNamesSizeBytes property.
     * 
     * @param string AttributeNamesSizeBytes
     * @return this instance
     */
    public function setAttributeNamesSizeBytes($value) 
    {
        $this->_fields['AttributeNamesSizeBytes']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the AttributeNamesSizeBytes and returns this instance
     * 
     * @param string $value AttributeNamesSizeBytes
     * @return Amazon_SimpleDB_Model_DomainMetadataResult instance
     */
    public function withAttributeNamesSizeBytes($value)
    {
        $this->setAttributeNamesSizeBytes($value);
        return $this;
    }


    /**
     * Checks if AttributeNamesSizeBytes is set
     * 
     * @return bool true if AttributeNamesSizeBytes  is set
     */
    public function isSetAttributeNamesSizeBytes()
    {
        return !is_null($this->_fields['AttributeNamesSizeBytes']['FieldValue']);
    }

    /**
     * Gets the value of the AttributeValueCount property.
     * 
     * @return string AttributeValueCount
     */
    public function getAttributeValueCount() 
    {
        return $this->_fields['AttributeValueCount']['FieldValue'];
    }

    /**
     * Sets the value of the AttributeValueCount property.
     * 
     * @param string AttributeValueCount
     * @return this instance
     */
    public function setAttributeValueCount($value) 
    {
        $this->_fields['AttributeValueCount']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the AttributeValueCount and returns this instance
     * 
     * @param string $value AttributeValueCount
     * @return Amazon_SimpleDB_Model_DomainMetadataResult instance
     */
    public function withAttributeValueCount($value)
    {
        $this->setAttributeValueCount($value);
        return $this;
    }


    /**
     * Checks if AttributeValueCount is set
     * 
     * @return bool true if AttributeValueCount  is set
     */
    public function isSetAttributeValueCount()
    {
        return !is_null($this->_fields['AttributeValueCount']['FieldValue']);
    }

    /**
     * Gets the value of the AttributeValuesSizeBytes property.
     * 
     * @return string AttributeValuesSizeBytes
     */
    public function getAttributeValuesSizeBytes() 
    {
        return $this->_fields['AttributeValuesSizeBytes']['FieldValue'];
    }

    /**
     * Sets the value of the AttributeValuesSizeBytes property.
     * 
     * @param string AttributeValuesSizeBytes
     * @return this instance
     */
    public function setAttributeValuesSizeBytes($value) 
    {
        $this->_fields['AttributeValuesSizeBytes']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the AttributeValuesSizeBytes and returns this instance
     * 
     * @param string $value AttributeValuesSizeBytes
     * @return Amazon_SimpleDB_Model_DomainMetadataResult instance
     */
    public function withAttributeValuesSizeBytes($value)
    {
        $this->setAttributeValuesSizeBytes($value);
        return $this;
    }


    /**
     * Checks if AttributeValuesSizeBytes is set
     * 
     * @return bool true if AttributeValuesSizeBytes  is set
     */
    public function isSetAttributeValuesSizeBytes()
    {
        return !is_null($this->_fields['AttributeValuesSizeBytes']['FieldValue']);
    }

    /**
     * Gets the value of the Timestamp property.
     * 
     * @return string Timestamp
     */
    public function getTimestamp() 
    {
        return $this->_fields['Timestamp']['FieldValue'];
    }

    /**
     * Sets the value of the Timestamp property.
     * 
     * @param string Timestamp
     * @return this instance
     */
    public function setTimestamp($value) 
    {
        $this->_fields['Timestamp']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the Timestamp and returns this instance
     * 
     * @param string $value Timestamp
     * @return Amazon_SimpleDB_Model_DomainMetadataResult instance
     */
    public function withTimestamp($value)
    {
        $this->setTimestamp($value);
        return $this;
    }


    /**
     * Checks if Timestamp is set
     * 
     * @return bool true if Timestamp  is set
     */
    public function isSetTimestamp()
    {
        return !is_null($this->_fields['Timestamp']['FieldValue']);
    }




}
