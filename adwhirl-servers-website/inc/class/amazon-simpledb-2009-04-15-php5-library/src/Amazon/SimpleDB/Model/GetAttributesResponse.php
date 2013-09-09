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
 * Amazon_SimpleDB_Model_GetAttributesResponse
 * 
 * Properties:
 * <ul>
 * 
 * <li>GetAttributesResult: Amazon_SimpleDB_Model_GetAttributesResult</li>
 * <li>ResponseMetadata: Amazon_SimpleDB_Model_ResponseMetadata</li>
 *
 * </ul>
 */ 
class Amazon_SimpleDB_Model_GetAttributesResponse extends Amazon_SimpleDB_Model
{


    /**
     * Construct new Amazon_SimpleDB_Model_GetAttributesResponse
     * 
     * @param mixed $data DOMElement or Associative Array to construct from. 
     * 
     * Valid properties:
     * <ul>
     * 
     * <li>GetAttributesResult: Amazon_SimpleDB_Model_GetAttributesResult</li>
     * <li>ResponseMetadata: Amazon_SimpleDB_Model_ResponseMetadata</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'GetAttributesResult' => array('FieldValue' => null, 'FieldType' => 'Amazon_SimpleDB_Model_GetAttributesResult'),
        'ResponseMetadata' => array('FieldValue' => null, 'FieldType' => 'Amazon_SimpleDB_Model_ResponseMetadata'),
        );
        parent::__construct($data);
    }

       
    /**
     * Construct Amazon_SimpleDB_Model_GetAttributesResponse from XML string
     * 
     * @param string $xml XML string to construct from
     * @return Amazon_SimpleDB_Model_GetAttributesResponse 
     */
    public static function fromXML($xml)
    {
        $dom = new DOMDocument();
        $dom->loadXML($xml);
        $xpath = new DOMXPath($dom);
    	$xpath->registerNamespace('a', 'http://sdb.amazonaws.com/doc/2009-04-15/');
        $response = $xpath->query('//a:GetAttributesResponse');
        if ($response->length == 1) {
            return new Amazon_SimpleDB_Model_GetAttributesResponse(($response->item(0))); 
        } else {
            throw new Exception ("Unable to construct Amazon_SimpleDB_Model_GetAttributesResponse from provided XML. 
                                  Make sure that GetAttributesResponse is a root element");
        }
          
    }
    
    /**
     * Gets the value of the GetAttributesResult.
     * 
     * @return GetAttributesResult GetAttributesResult
     */
    public function getGetAttributesResult() 
    {
        return $this->_fields['GetAttributesResult']['FieldValue'];
    }

    /**
     * Sets the value of the GetAttributesResult.
     * 
     * @param GetAttributesResult GetAttributesResult
     * @return void
     */
    public function setGetAttributesResult($value) 
    {
        $this->_fields['GetAttributesResult']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the GetAttributesResult  and returns this instance
     * 
     * @param GetAttributesResult $value GetAttributesResult
     * @return Amazon_SimpleDB_Model_GetAttributesResponse instance
     */
    public function withGetAttributesResult($value)
    {
        $this->setGetAttributesResult($value);
        return $this;
    }


    /**
     * Checks if GetAttributesResult  is set
     * 
     * @return bool true if GetAttributesResult property is set
     */
    public function isSetGetAttributesResult()
    {
        return !is_null($this->_fields['GetAttributesResult']['FieldValue']);

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
     * @return Amazon_SimpleDB_Model_GetAttributesResponse instance
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
        $xml .= "<GetAttributesResponse xmlns=\"http://sdb.amazonaws.com/doc/2009-04-15/\">";
        $xml .= $this->_toXMLFragment();
        $xml .= "</GetAttributesResponse>";
        return $xml;
    }

}
