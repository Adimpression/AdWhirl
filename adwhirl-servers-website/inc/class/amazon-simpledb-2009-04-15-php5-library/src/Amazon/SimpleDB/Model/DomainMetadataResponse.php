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
 * Amazon_SimpleDB_Model_DomainMetadataResponse
 * 
 * Properties:
 * <ul>
 * 
 * <li>DomainMetadataResult: Amazon_SimpleDB_Model_DomainMetadataResult</li>
 * <li>ResponseMetadata: Amazon_SimpleDB_Model_ResponseMetadata</li>
 *
 * </ul>
 */ 
class Amazon_SimpleDB_Model_DomainMetadataResponse extends Amazon_SimpleDB_Model
{


    /**
     * Construct new Amazon_SimpleDB_Model_DomainMetadataResponse
     * 
     * @param mixed $data DOMElement or Associative Array to construct from. 
     * 
     * Valid properties:
     * <ul>
     * 
     * <li>DomainMetadataResult: Amazon_SimpleDB_Model_DomainMetadataResult</li>
     * <li>ResponseMetadata: Amazon_SimpleDB_Model_ResponseMetadata</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'DomainMetadataResult' => array('FieldValue' => null, 'FieldType' => 'Amazon_SimpleDB_Model_DomainMetadataResult'),
        'ResponseMetadata' => array('FieldValue' => null, 'FieldType' => 'Amazon_SimpleDB_Model_ResponseMetadata'),
        );
        parent::__construct($data);
    }

       
    /**
     * Construct Amazon_SimpleDB_Model_DomainMetadataResponse from XML string
     * 
     * @param string $xml XML string to construct from
     * @return Amazon_SimpleDB_Model_DomainMetadataResponse 
     */
    public static function fromXML($xml)
    {
        $dom = new DOMDocument();
        $dom->loadXML($xml);
        $xpath = new DOMXPath($dom);
    	$xpath->registerNamespace('a', 'http://sdb.amazonaws.com/doc/2009-04-15/');
        $response = $xpath->query('//a:DomainMetadataResponse');
        if ($response->length == 1) {
            return new Amazon_SimpleDB_Model_DomainMetadataResponse(($response->item(0))); 
        } else {
            throw new Exception ("Unable to construct Amazon_SimpleDB_Model_DomainMetadataResponse from provided XML. 
                                  Make sure that DomainMetadataResponse is a root element");
        }
          
    }
    
    /**
     * Gets the value of the DomainMetadataResult.
     * 
     * @return DomainMetadataResult DomainMetadataResult
     */
    public function getDomainMetadataResult() 
    {
        return $this->_fields['DomainMetadataResult']['FieldValue'];
    }

    /**
     * Sets the value of the DomainMetadataResult.
     * 
     * @param DomainMetadataResult DomainMetadataResult
     * @return void
     */
    public function setDomainMetadataResult($value) 
    {
        $this->_fields['DomainMetadataResult']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the DomainMetadataResult  and returns this instance
     * 
     * @param DomainMetadataResult $value DomainMetadataResult
     * @return Amazon_SimpleDB_Model_DomainMetadataResponse instance
     */
    public function withDomainMetadataResult($value)
    {
        $this->setDomainMetadataResult($value);
        return $this;
    }


    /**
     * Checks if DomainMetadataResult  is set
     * 
     * @return bool true if DomainMetadataResult property is set
     */
    public function isSetDomainMetadataResult()
    {
        return !is_null($this->_fields['DomainMetadataResult']['FieldValue']);

    }

    /**
     * Gets the value of the ResponseMetadata.
     * 
     * @return ResponseMetadata ResponseMetadata
     */
    public function getResponseMetadata() 
    {
        return $this->_fields['ResponseMetadata']['FieldValue'];
    }

    /**
     * Sets the value of the ResponseMetadata.
     * 
     * @param ResponseMetadata ResponseMetadata
     * @return void
     */
    public function setResponseMetadata($value) 
    {
        $this->_fields['ResponseMetadata']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the ResponseMetadata  and returns this instance
     * 
     * @param ResponseMetadata $value ResponseMetadata
     * @return Amazon_SimpleDB_Model_DomainMetadataResponse instance
     */
    public function withResponseMetadata($value)
    {
        $this->setResponseMetadata($value);
        return $this;
    }


    /**
     * Checks if ResponseMetadata  is set
     * 
     * @return bool true if ResponseMetadata property is set
     */
    public function isSetResponseMetadata()
    {
        return !is_null($this->_fields['ResponseMetadata']['FieldValue']);

    }



    /**
     * XML Representation for this object
     * 
     * @return string XML for this object
     */
    public function toXML() 
    {
        $xml = "";
        $xml .= "<DomainMetadataResponse xmlns=\"http://sdb.amazonaws.com/doc/2009-04-15/\">";
        $xml .= $this->_toXMLFragment();
        $xml .= "</DomainMetadataResponse>";
        return $xml;
    }

}
