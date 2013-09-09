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
 *  @see Amazon_SimpleDB_Interface
 */
require_once ('Amazon/SimpleDB/Interface.php');

/**
 * Amazon SimpleDB is a web service for running queries on structured
 * data in real time. This service works in close conjunction with Amazon
 * Simple Storage Service (Amazon S3) and Amazon Elastic Compute Cloud
 * (Amazon EC2), collectively providing the ability to store, process
 * and query data sets in the cloud. These services are designed to make
 * web-scale computing easier and more cost-effective for developers.
 * Traditionally, this type of functionality has been accomplished with
 * a clustered relational database that requires a sizable upfront
 * investment, brings more complexity than is typically needed, and often
 * requires a DBA to maintain and administer. In contrast, Amazon SimpleDB
 * is easy to use and provides the core functionality of a database -
 * real-time lookup and simple querying of structured data without the
 * operational complexity.  Amazon SimpleDB requires no schema, automatically
 * indexes your data and provides a simple API for storage and access.
 * This eliminates the administrative burden of data modeling, index
 * maintenance, and performance tuning. Developers gain access to this
 * functionality within Amazon's proven computing environment, are able
 * to scale instantly, and pay only for what they use.
 * 
 * Amazon_SimpleDB_Client is an implementation of Amazon_SimpleDB
 *
 */
class Amazon_SimpleDB_Client implements Amazon_SimpleDB_Interface
{

    const SERVICE_VERSION = '2009-04-15';

    /** @var string */
    private  $_awsAccessKeyId = null;

    /** @var string */
    private  $_awsSecretAccessKey = null;

    /** @var array */
    private  $_config = array ('ServiceURL' => 'https://sdb.amazonaws.com',
                               'UserAgent' => 'Amazon SimpleDB PHP5 Library',
                               'SignatureVersion' => 2,
                               'SignatureMethod' => 'HmacSHA256',
                               'ProxyHost' => null,
                               'ProxyPort' => -1,
                               'MaxErrorRetry' => 3
                               );

    /**
     * Construct new Client
     *
     * @param string $awsAccessKeyId AWS Access Key ID
     * @param string $awsSecretAccessKey AWS Secret Access Key
     * @param array $config configuration options.
     * Valid configuration options are:
     * <ul>
     * <li>ServiceURL</li>
     * <li>UserAgent</li>
     * <li>SignatureVersion</li>
     * <li>TimesRetryOnError</li>
     * <li>ProxyHost</li>
     * <li>ProxyPort</li>
     * <li>MaxErrorRetry</li>
     * </ul>
     */
    public function __construct($awsAccessKeyId, $awsSecretAccessKey, $config = null)
    {
        iconv_set_encoding('output_encoding', 'UTF-8');
        iconv_set_encoding('input_encoding', 'UTF-8');
        iconv_set_encoding('internal_encoding', 'UTF-8');

        $this->_awsAccessKeyId = $awsAccessKeyId;
        $this->_awsSecretAccessKey = $awsSecretAccessKey;
        if (!is_null($config)) $this->_config = array_merge($this->_config, $config);
    }

    // Public API ------------------------------------------------------------//


            
    /**
     * Create Domain 
     * The CreateDomain operation creates a new domain. The domain name must be unique
     * among the domains associated with the Access Key ID provided in the request. The CreateDomain
     * operation may take 10 or more seconds to complete.
     * 
     * @see http://docs.amazonwebservices.com/AmazonSimpleDB/2009-04-15/DeveloperGuide/SDB_API_CreateDomain.html
     * @param mixed $request array of parameters for Amazon_SimpleDB_Model_CreateDomainRequest request
     * or Amazon_SimpleDB_Model_CreateDomainRequest object itself
     * @see Amazon_SimpleDB_Model_CreateDomain
     * @return Amazon_SimpleDB_Model_CreateDomainResponse Amazon_SimpleDB_Model_CreateDomainResponse
     *
     * @throws Amazon_SimpleDB_Exception
     */
    public function createDomain($request)
    {
        if (!$request instanceof Amazon_SimpleDB_Model_CreateDomainRequest) {
            require_once ('Amazon/SimpleDB/Model/CreateDomainRequest.php');
            $request = new Amazon_SimpleDB_Model_CreateDomainRequest($request);
        }
        require_once ('Amazon/SimpleDB/Model/CreateDomainResponse.php');
        return Amazon_SimpleDB_Model_CreateDomainResponse::fromXML($this->_invoke($this->_convertCreateDomain($request)));
    }


            
    /**
     * List Domains 
     * The ListDomains operation lists all domains associated with the Access Key ID. It returns
     * domain names up to the limit set by MaxNumberOfDomains. A NextToken is returned if there are more
     * than MaxNumberOfDomains domains. Calling ListDomains successive times with the
     * NextToken returns up to MaxNumberOfDomains more domain names each time.
     * 
     * @see http://docs.amazonwebservices.com/AmazonSimpleDB/2009-04-15/DeveloperGuide/SDB_API_ListDomains.html
     * @param mixed $request array of parameters for Amazon_SimpleDB_Model_ListDomainsRequest request
     * or Amazon_SimpleDB_Model_ListDomainsRequest object itself
     * @see Amazon_SimpleDB_Model_ListDomains
     * @return Amazon_SimpleDB_Model_ListDomainsResponse Amazon_SimpleDB_Model_ListDomainsResponse
     *
     * @throws Amazon_SimpleDB_Exception
     */
    public function listDomains($request)
    {
        if (!$request instanceof Amazon_SimpleDB_Model_ListDomainsRequest) {
            require_once ('Amazon/SimpleDB/Model/ListDomainsRequest.php');
            $request = new Amazon_SimpleDB_Model_ListDomainsRequest($request);
        }
        require_once ('Amazon/SimpleDB/Model/ListDomainsResponse.php');
        return Amazon_SimpleDB_Model_ListDomainsResponse::fromXML($this->_invoke($this->_convertListDomains($request)));
    }


            
    /**
     * Domain Metadata 
     * The DomainMetadata operation returns some domain metadata values, such as the
     * number of items, attribute names and attribute values along with their sizes.
     * 
     * @see http://docs.amazonwebservices.com/AmazonSimpleDB/2009-04-15/DeveloperGuide/SDB_API_DomainMetadata.html
     * @param mixed $request array of parameters for Amazon_SimpleDB_Model_DomainMetadataRequest request
     * or Amazon_SimpleDB_Model_DomainMetadataRequest object itself
     * @see Amazon_SimpleDB_Model_DomainMetadata
     * @return Amazon_SimpleDB_Model_DomainMetadataResponse Amazon_SimpleDB_Model_DomainMetadataResponse
     *
     * @throws Amazon_SimpleDB_Exception
     */
    public function domainMetadata($request)
    {
        if (!$request instanceof Amazon_SimpleDB_Model_DomainMetadataRequest) {
            require_once ('Amazon/SimpleDB/Model/DomainMetadataRequest.php');
            $request = new Amazon_SimpleDB_Model_DomainMetadataRequest($request);
        }
        require_once ('Amazon/SimpleDB/Model/DomainMetadataResponse.php');
        return Amazon_SimpleDB_Model_DomainMetadataResponse::fromXML($this->_invoke($this->_convertDomainMetadata($request)));
    }


            
    /**
     * Delete Domain 
     * The DeleteDomain operation deletes a domain. Any items (and their attributes) in the domain
     * are deleted as well. The DeleteDomain operation may take 10 or more seconds to complete.
     * 
     * @see http://docs.amazonwebservices.com/AmazonSimpleDB/2009-04-15/DeveloperGuide/SDB_API_DeleteDomain.html
     * @param mixed $request array of parameters for Amazon_SimpleDB_Model_DeleteDomainRequest request
     * or Amazon_SimpleDB_Model_DeleteDomainRequest object itself
     * @see Amazon_SimpleDB_Model_DeleteDomain
     * @return Amazon_SimpleDB_Model_DeleteDomainResponse Amazon_SimpleDB_Model_DeleteDomainResponse
     *
     * @throws Amazon_SimpleDB_Exception
     */
    public function deleteDomain($request)
    {
        if (!$request instanceof Amazon_SimpleDB_Model_DeleteDomainRequest) {
            require_once ('Amazon/SimpleDB/Model/DeleteDomainRequest.php');
            $request = new Amazon_SimpleDB_Model_DeleteDomainRequest($request);
        }
        require_once ('Amazon/SimpleDB/Model/DeleteDomainResponse.php');
        return Amazon_SimpleDB_Model_DeleteDomainResponse::fromXML($this->_invoke($this->_convertDeleteDomain($request)));
    }


            
    /**
     * Put Attributes 
     * The PutAttributes operation creates or replaces attributes within an item. You specify new attributes
     * using a combination of the Attribute.X.Name and Attribute.X.Value parameters. You specify
     * the first attribute by the parameters Attribute.0.Name and Attribute.0.Value, the second
     * attribute by the parameters Attribute.1.Name and Attribute.1.Value, and so on.
     * Attributes are uniquely identified within an item by their name/value combination. For example, a single
     * item can have the attributes { "first_name", "first_value" } and { "first_name",
     * second_value" }. However, it cannot have two attribute instances where both the Attribute.X.Name and
     * Attribute.X.Value are the same.
     * Optionally, the requestor can supply the Replace parameter for each individual value. Setting this value
     * to true will cause the new attribute value to replace the existing attribute value(s). For example, if an
     * item has the attributes { 'a', '1' }, { 'b', '2'} and { 'b', '3' } and the requestor does a
     * PutAttributes of { 'b', '4' } with the Replace parameter set to true, the final attributes of the
     * item will be { 'a', '1' } and { 'b', '4' }, replacing the previous values of the 'b' attribute
     * with the new value.
     * 
     * @see http://docs.amazonwebservices.com/AmazonSimpleDB/2009-04-15/DeveloperGuide/SDB_API_PutAttributes.html
     * @param mixed $request array of parameters for Amazon_SimpleDB_Model_PutAttributesRequest request
     * or Amazon_SimpleDB_Model_PutAttributesRequest object itself
     * @see Amazon_SimpleDB_Model_PutAttributes
     * @return Amazon_SimpleDB_Model_PutAttributesResponse Amazon_SimpleDB_Model_PutAttributesResponse
     *
     * @throws Amazon_SimpleDB_Exception
     */
    public function putAttributes($request)
    {
        if (!$request instanceof Amazon_SimpleDB_Model_PutAttributesRequest) {
            require_once ('Amazon/SimpleDB/Model/PutAttributesRequest.php');
            $request = new Amazon_SimpleDB_Model_PutAttributesRequest($request);
        }
        require_once ('Amazon/SimpleDB/Model/PutAttributesResponse.php');
        return Amazon_SimpleDB_Model_PutAttributesResponse::fromXML($this->_invoke($this->_convertPutAttributes($request)));
    }


            
    /**
     * Batch Put Attributes 
     * The BatchPutAttributes operation creates or replaces attributes within one or more items.
     * You specify the item name with the Item.X.ItemName parameter.
     * You specify new attributes using a combination of the Item.X.Attribute.Y.Name and Item.X.Attribute.Y.Value parameters.
     * You specify the first attribute for the first item by the parameters Item.0.Attribute.0.Name and Item.0.Attribute.0.Value,
     * the second attribute for the first item by the parameters Item.0.Attribute.1.Name and Item.0.Attribute.1.Value, and so on.
     * Attributes are uniquely identified within an item by their name/value combination. For example, a single
     * item can have the attributes { "first_name", "first_value" } and { "first_name",
     * second_value" }. However, it cannot have two attribute instances where both the Item.X.Attribute.Y.Name and
     * Item.X.Attribute.Y.Value are the same.
     * Optionally, the requestor can supply the Replace parameter for each individual value. Setting this value
     * to true will cause the new attribute value to replace the existing attribute value(s). For example, if an
     * item 'I' has the attributes { 'a', '1' }, { 'b', '2'} and { 'b', '3' } and the requestor does a
     * BacthPutAttributes of {'I', 'b', '4' } with the Replace parameter set to true, the final attributes of the
     * item will be { 'a', '1' } and { 'b', '4' }, replacing the previous values of the 'b' attribute
     * with the new value.
     * 
     * @see http://docs.amazonwebservices.com/AmazonSimpleDB/2009-04-15/DeveloperGuide/SDB_API_BatchPutAttributes.html
     * @param mixed $request array of parameters for Amazon_SimpleDB_Model_BatchPutAttributesRequest request
     * or Amazon_SimpleDB_Model_BatchPutAttributesRequest object itself
     * @see Amazon_SimpleDB_Model_BatchPutAttributes
     * @return Amazon_SimpleDB_Model_BatchPutAttributesResponse Amazon_SimpleDB_Model_BatchPutAttributesResponse
     *
     * @throws Amazon_SimpleDB_Exception
     */
    public function batchPutAttributes($request)
    {
        if (!$request instanceof Amazon_SimpleDB_Model_BatchPutAttributesRequest) {
            require_once ('Amazon/SimpleDB/Model/BatchPutAttributesRequest.php');
            $request = new Amazon_SimpleDB_Model_BatchPutAttributesRequest($request);
        }
        require_once ('Amazon/SimpleDB/Model/BatchPutAttributesResponse.php');
        return Amazon_SimpleDB_Model_BatchPutAttributesResponse::fromXML($this->_invoke($this->_convertBatchPutAttributes($request)));
    }


            
    /**
     * Get Attributes 
     * Returns all of the attributes associated with the item. Optionally, the attributes returned can be limited to
     * the specified AttributeName parameter.
     * If the item does not exist on the replica that was accessed for this operation, an empty attribute is
     * returned. The system does not return an error as it cannot guarantee the item does not exist on other
     * replicas.
     * 
     * @see http://docs.amazonwebservices.com/AmazonSimpleDB/2009-04-15/DeveloperGuide/SDB_API_GetAttributes.html
     * @param mixed $request array of parameters for Amazon_SimpleDB_Model_GetAttributesRequest request
     * or Amazon_SimpleDB_Model_GetAttributesRequest object itself
     * @see Amazon_SimpleDB_Model_GetAttributes
     * @return Amazon_SimpleDB_Model_GetAttributesResponse Amazon_SimpleDB_Model_GetAttributesResponse
     *
     * @throws Amazon_SimpleDB_Exception
     */
    public function getAttributes($request)
    {
        if (!$request instanceof Amazon_SimpleDB_Model_GetAttributesRequest) {
            require_once ('Amazon/SimpleDB/Model/GetAttributesRequest.php');
            $request = new Amazon_SimpleDB_Model_GetAttributesRequest($request);
        }
        require_once ('Amazon/SimpleDB/Model/GetAttributesResponse.php');
        return Amazon_SimpleDB_Model_GetAttributesResponse::fromXML($this->_invoke($this->_convertGetAttributes($request)));
    }


            
    /**
     * Delete Attributes 
     * Deletes one or more attributes associated with the item. If all attributes of an item are deleted, the item is
     * deleted.
     * 
     * @see http://docs.amazonwebservices.com/AmazonSimpleDB/2009-04-15/DeveloperGuide/SDB_API_DeleteAttributes.html
     * @param mixed $request array of parameters for Amazon_SimpleDB_Model_DeleteAttributesRequest request
     * or Amazon_SimpleDB_Model_DeleteAttributesRequest object itself
     * @see Amazon_SimpleDB_Model_DeleteAttributes
     * @return Amazon_SimpleDB_Model_DeleteAttributesResponse Amazon_SimpleDB_Model_DeleteAttributesResponse
     *
     * @throws Amazon_SimpleDB_Exception
     */
    public function deleteAttributes($request)
    {
        if (!$request instanceof Amazon_SimpleDB_Model_DeleteAttributesRequest) {
            require_once ('Amazon/SimpleDB/Model/DeleteAttributesRequest.php');
            $request = new Amazon_SimpleDB_Model_DeleteAttributesRequest($request);
        }
        require_once ('Amazon/SimpleDB/Model/DeleteAttributesResponse.php');
        return Amazon_SimpleDB_Model_DeleteAttributesResponse::fromXML($this->_invoke($this->_convertDeleteAttributes($request)));
    }


            
    /**
     * Select 
     * The Select operation returns a set of item names and associate attributes that match the
     * query expression. Select operations that run longer than 5 seconds will likely time-out
     * and return a time-out error response.
     * 
     * @see http://docs.amazonwebservices.com/AmazonSimpleDB/2009-04-15/DeveloperGuide/SDB_API_Select.html
     * @param mixed $request array of parameters for Amazon_SimpleDB_Model_SelectRequest request
     * or Amazon_SimpleDB_Model_SelectRequest object itself
     * @see Amazon_SimpleDB_Model_Select
     * @return Amazon_SimpleDB_Model_SelectResponse Amazon_SimpleDB_Model_SelectResponse
     *
     * @throws Amazon_SimpleDB_Exception
     */
    public function select($request)
    {
        if (!$request instanceof Amazon_SimpleDB_Model_SelectRequest) {
            require_once ('Amazon/SimpleDB/Model/SelectRequest.php');
            $request = new Amazon_SimpleDB_Model_SelectRequest($request);
        }
        require_once ('Amazon/SimpleDB/Model/SelectResponse.php');
        return Amazon_SimpleDB_Model_SelectResponse::fromXML($this->_invoke($this->_convertSelect($request)));
    }

        // Private API ------------------------------------------------------------//

    /**
     * Invoke request and return response
     */
    private function _invoke(array $parameters)
    {
        $actionName = $parameters["Action"];
        $response = array();
        $responseBody = null;
        $statusCode = 200;

        /* Submit the request and read response body */
        try {

            /* Add required request parameters */
            $parameters = $this->_addRequiredParameters($parameters);

            $shouldRetry = true;
            $retries = 0;
            do {
                try {
                        $response = $this->_httpPost($parameters);
                        if ($response['Status'] === 200) {
                            $shouldRetry = false;
                        } else {
                            if ($response['Status'] === 500 || $response['Status'] === 503) {
                                $shouldRetry = true;
                                $this->_pauseOnRetry(++$retries, $response['Status']);
                            } else {
                                throw $this->_reportAnyErrors($response['ResponseBody'], $response['Status']);
                            }
                       }
                /* Rethrow on deserializer error */
                } catch (Exception $e) {
                    require_once ('Amazon/SimpleDB/Exception.php');
                    if ($e instanceof Amazon_SimpleDB_Exception) {
                        throw $e;
                    } else {
                        require_once ('Amazon/SimpleDB/Exception.php');
                        throw new Amazon_SimpleDB_Exception(array('Exception' => $e, 'Message' => $e->getMessage()));
                    }
                }

            } while ($shouldRetry);

        } catch (Amazon_SimpleDB_Exception $se) {
            throw $se;
        } catch (Exception $t) {
            throw new Amazon_SimpleDB_Exception(array('Exception' => $t, 'Message' => $t->getMessage()));
        }

        return $response['ResponseBody'];
    }

    /**
     * Look for additional error strings in the response and return formatted exception
     */
    private function _reportAnyErrors($responseBody, $status, Exception $e =  null)
    {
        $ex = null;
        if (!is_null($responseBody) && strpos($responseBody, '<') === 0) {
            if (preg_match('@<RequestId>(.*)</RequestId>.*<Error><Code>(.*)</Code><Message>(.*)</Message></Error>.*(<Error>)?@mi',
                $responseBody, $errorMatcherOne)) {

                $requestId = $errorMatcherOne[1];
                $code = $errorMatcherOne[2];
                $message = $errorMatcherOne[3];

                require_once ('Amazon/SimpleDB/Exception.php');
                $ex = new Amazon_SimpleDB_Exception(array ('Message' => $message, 'StatusCode' => $status, 'ErrorCode' => $code,
                                                           'ErrorType' => 'Unknown', 'RequestId' => $requestId, 'XML' => $responseBody));

            } elseif (preg_match('@<Error><Code>(.*)</Code><Message>(.*)</Message></Error>.*(<Error>)?.*<RequestID>(.*)</RequestID>@mi',
                $responseBody, $errorMatcherTwo)) {

                $code = $errorMatcherTwo[1];
                $message = $errorMatcherTwo[2];
                $requestId = $errorMatcherTwo[4];
                require_once ('Amazon/SimpleDB/Exception.php');
                $ex = new Amazon_SimpleDB_Exception(array ('Message' => $message, 'StatusCode' => $status, 'ErrorCode' => $code,
                                                              'ErrorType' => 'Unknown', 'RequestId' => $requestId, 'XML' => $responseBody));
            } elseif (preg_match('@<Error><Code>(.*)</Code><Message>(.*)</Message><BoxUsage>(.*)</BoxUsage></Error>.*(<Error>)?.*<RequestID>(.*)</RequestID>@mi',
                $responseBody, $errorMatcherThree)) {

                $code = $errorMatcherThree[1];
                $message = $errorMatcherThree[2];
                $boxUsage = $errorMatcherThree[3];
                $requestId = $errorMatcherThree[5];
                require_once ('Amazon/SimpleDB/Exception.php');
                $ex = new Amazon_SimpleDB_Exception(array ('Message' => $message, 'StatusCode' => $status, 'ErrorCode' => $code,
                                                              'ErrorType' => 'Unknown', 'BoxUsage' => $boxUsage, 'RequestId' => $requestId, 'XML' => $responseBody));

            } else {
                require_once ('Amazon/SimpleDB/Exception.php');
                $ex = new Amazon_SimpleDB_Exception(array('Message' => 'Internal Error', 'StatusCode' => $status));
            }
        } else {
            require_once ('Amazon/SimpleDB/Exception.php');
            $ex = new Amazon_SimpleDB_Exception(array('Message' => 'Internal Error', 'StatusCode' => $status));
        }
        return $ex;
    }



    /**
     * Perform HTTP post with exponential retries on error 500 and 503
     *
     */
    private function _httpPost(array $parameters)
    {
        $query = $this->_getParametersAsString($parameters);
        $url = parse_url ($this->_config['ServiceURL']);
        $post  = "POST / HTTP/1.0\r\n";
        $post .= "Host: " . $url['host'] . "\r\n";
        $post .= "Content-Type: application/x-www-form-urlencoded; charset=utf-8\r\n";
        $post .= "Content-Length: " . strlen($query) . "\r\n";
        $post .= "User-Agent: " . $this->_config['UserAgent'] . "\r\n";
        $post .= "\r\n";
        $post .= $query;

        $port = array_key_exists('port',$url) ? $url['port'] : null;
        $scheme = '';

        switch ($url['scheme']) {
            case 'https':
                $scheme = 'ssl://';
                $port = $port === null ? 443 : $port;
                break;
            default:
                $scheme = '';
                $port = $port === null ? 80 : $port;
        }

        $response = '';
        if ($socket = @fsockopen($scheme . $url['host'], $port, $errno, $errstr, 10)) {

            fwrite($socket, $post);

            while (!feof($socket)) {
                $response .= fgets($socket, 1160);
            }
            fclose($socket);

            list($other, $responseBody) = explode("\r\n\r\n", $response, 2);
            $other = preg_split("/\r\n|\n|\r/", $other);
            list($protocol, $code, $text) = explode(' ', trim(array_shift($other)), 3);
        } else {
            throw new Exception ("Unable to establish connection to host " . $url['host'] . " $errstr");
        }
        return array ('Status' => (int)$code, 'ResponseBody' => $responseBody);
    }

    /**
     * Exponential sleep on failed request
     * @param retries current retry
     * @throws Amazon_SimpleDB_Exception if maximum number of retries has been reached
     */
    private function _pauseOnRetry($retries, $status)
    {
        if ($retries <= $this->_config['MaxErrorRetry']) {
            $delay = (int) (pow(4, $retries) * 100000) ;
            usleep($delay);
        } else {
            require_once ('Amazon/SimpleDB/Exception.php');
            throw new Amazon_SimpleDB_Exception (array ('Message' => "Maximum number of retry attempts reached :  $retries", 'StatusCode' => $status));
        }
    }

    /**
     * Add authentication related and version parameters
     */
    private function _addRequiredParameters(array $parameters)
    {
        $parameters['AWSAccessKeyId'] = $this->_awsAccessKeyId;
        $parameters['Timestamp'] = $this->_getFormattedTimestamp();
        $parameters['Version'] = self::SERVICE_VERSION;
        $parameters['SignatureVersion'] = $this->_config['SignatureVersion'];
        if ($parameters['SignatureVersion'] > 1) {
            $parameters['SignatureMethod'] = $this->_config['SignatureMethod'];
        }
        $parameters['Signature'] = $this->_signParameters($parameters, $this->_awsSecretAccessKey);

        return $parameters;
    }

    /**
     * Convert paremeters to Url encoded query string
     */
    private function _getParametersAsString(array $parameters)
    {
        $queryParameters = array();
        foreach ($parameters as $key => $value) {
            $queryParameters[] = $key . '=' . $this->_urlencode($value);
        }
        return implode('&', $queryParameters);
    }


    /**
     * Computes RFC 2104-compliant HMAC signature for request parameters
     * Implements AWS Signature, as per following spec:
     *
     * If Signature Version is 0, it signs concatenated Action and Timestamp
     *
     * If Signature Version is 1, it performs the following:
     *
     * Sorts all  parameters (including SignatureVersion and excluding Signature,
     * the value of which is being created), ignoring case.
     *
     * Iterate over the sorted list and append the parameter name (in original case)
     * and then its value. It will not URL-encode the parameter values before
     * constructing this string. There are no separators.
     *
     * If Signature Version is 2, string to sign is based on following:
     *
     *    1. The HTTP Request Method followed by an ASCII newline (%0A)
     *    2. The HTTP Host header in the form of lowercase host, followed by an ASCII newline.
     *    3. The URL encoded HTTP absolute path component of the URI
     *       (up to but not including the query string parameters);
     *       if this is empty use a forward '/'. This parameter is followed by an ASCII newline.
     *    4. The concatenation of all query string components (names and values)
     *       as UTF-8 characters which are URL encoded as per RFC 3986
     *       (hex characters MUST be uppercase), sorted using lexicographic byte ordering.
     *       Parameter names are separated from their values by the '=' character
     *       (ASCII character 61), even if the value is empty.
     *       Pairs of parameter and values are separated by the '&' character (ASCII code 38).
     *
     */
    private function _signParameters(array $parameters, $key) {
        $signatureVersion = $parameters['SignatureVersion'];
        $algorithm = "HmacSHA1";
        $stringToSign = null;
        if (0 === $signatureVersion) {
            $stringToSign = $this->_calculateStringToSignV0($parameters);
        } else if (1 === $signatureVersion) {
            $stringToSign = $this->_calculateStringToSignV1($parameters);
        } else if (2 === $signatureVersion) {
            $algorithm = $this->_config['SignatureMethod'];
            $parameters['SignatureMethod'] = $algorithm;
            $stringToSign = $this->_calculateStringToSignV2($parameters);
        } else {
            throw new Exception("Invalid Signature Version specified");
        }
        return $this->_sign($stringToSign, $key, $algorithm);
    }

    /**
     * Calculate String to Sign for SignatureVersion 0
     * @param array $parameters request parameters
     * @return String to Sign
     */
    private function _calculateStringToSignV0(array $parameters) {
        return $parameters['Action'] .  $parameters['Timestamp'];
    }

    /**
     * Calculate String to Sign for SignatureVersion 1
     * @param array $parameters request parameters
     * @return String to Sign
     */
    private function _calculateStringToSignV1(array $parameters) {
        $data = '';
        uksort($parameters, 'strcasecmp');
        foreach ($parameters as $parameterName => $parameterValue) {
            $data .= $parameterName . $parameterValue;
        }
        return $data;
    }

    /**
     * Calculate String to Sign for SignatureVersion 2
     * @param array $parameters request parameters
     * @return String to Sign
     */
    private function _calculateStringToSignV2(array $parameters) {
        $data = 'POST';
        $data .= "\n";
        $endpoint = parse_url ($this->_config['ServiceURL']);
        $data .= $endpoint['host'];
        $data .= "\n";
        $uri = array_key_exists('path', $endpoint) ? $endpoint['path'] : null;
        if (!isset ($uri)) {
            $uri = "/";
        }
	$uriencoded = implode("/", array_map(array($this, "_urlencode"), explode("/", $uri)));
        $data .= $uriencoded;
        $data .= "\n";
        uksort($parameters, 'strcmp');
        $data .= $this->_getParametersAsString($parameters);
        return $data;
    }

    private function _urlencode($value) {
		return str_replace('%7E', '~', rawurlencode($value));
    }


    /**
     * Computes RFC 2104-compliant HMAC signature.
     */
    private function _sign($data, $key, $algorithm)
    {
        if ($algorithm === 'HmacSHA1') {
            $hash = 'sha1';
        } else if ($algorithm === 'HmacSHA256') {
            $hash = 'sha256';
        } else {
            throw new Exception ("Non-supported signing method specified");
        }
        return base64_encode(
            hash_hmac($hash, $data, $key, true)
        );
    }


    /**
     * Formats date as ISO 8601 timestamp
     */
    private function _getFormattedTimestamp()
    {
        return gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time());
    }


                                                                                
    /**
     * Convert CreateDomainRequest to name value pairs
     */
    private function _convertCreateDomain($request) {
        
        $parameters = array();
        $parameters['Action'] = 'CreateDomain';
        if ($request->isSetDomainName()) {
            $parameters['DomainName'] =  $request->getDomainName();
        }

        return $parameters;
    }
        
                                        
    /**
     * Convert ListDomainsRequest to name value pairs
     */
    private function _convertListDomains($request) {
        
        $parameters = array();
        $parameters['Action'] = 'ListDomains';
        if ($request->isSetMaxNumberOfDomains()) {
            $parameters['MaxNumberOfDomains'] =  $request->getMaxNumberOfDomains();
        }
        if ($request->isSetNextToken()) {
            $parameters['NextToken'] =  $request->getNextToken();
        }

        return $parameters;
    }
        
                                                
    /**
     * Convert DomainMetadataRequest to name value pairs
     */
    private function _convertDomainMetadata($request) {
        
        $parameters = array();
        $parameters['Action'] = 'DomainMetadata';
        if ($request->isSetDomainName()) {
            $parameters['DomainName'] =  $request->getDomainName();
        }

        return $parameters;
    }
        
                                                
    /**
     * Convert DeleteDomainRequest to name value pairs
     */
    private function _convertDeleteDomain($request) {
        
        $parameters = array();
        $parameters['Action'] = 'DeleteDomain';
        if ($request->isSetDomainName()) {
            $parameters['DomainName'] =  $request->getDomainName();
        }

        return $parameters;
    }
        
                                        
    /**
     * Convert PutAttributesRequest to name value pairs
     */
    private function _convertPutAttributes($request) {
        
        $parameters = array();
        $parameters['Action'] = 'PutAttributes';
        if ($request->isSetDomainName()) {
            $parameters['DomainName'] =  $request->getDomainName();
        }
        if ($request->isSetItemName()) {
            $parameters['ItemName'] =  $request->getItemName();
        }
        foreach ($request->getAttribute() as $attributeputAttributesRequestIndex => $attributeputAttributesRequest) {
            if ($attributeputAttributesRequest->isSetName()) {
                $parameters['Attribute' . '.'  . ($attributeputAttributesRequestIndex + 1) . '.' . 'Name'] =  $attributeputAttributesRequest->getName();
            }
            if ($attributeputAttributesRequest->isSetValue()) {
                $parameters['Attribute' . '.'  . ($attributeputAttributesRequestIndex + 1) . '.' . 'Value'] =  $attributeputAttributesRequest->getValue();
            }
            if ($attributeputAttributesRequest->isSetReplace()) {
                $parameters['Attribute' . '.'  . ($attributeputAttributesRequestIndex + 1) . '.' . 'Replace'] =  $attributeputAttributesRequest->getReplace() ? "true" : "false";
            }

        }

        return $parameters;
    }
        
                                        
    /**
     * Convert BatchPutAttributesRequest to name value pairs
     */
    private function _convertBatchPutAttributes($request) {
        
        $parameters = array();
        $parameters['Action'] = 'BatchPutAttributes';
        if ($request->isSetDomainName()) {
            $parameters['DomainName'] =  $request->getDomainName();
        }
        foreach ($request->getItem() as $itembatchPutAttributesRequestIndex => $itembatchPutAttributesRequest) {
            if ($itembatchPutAttributesRequest->isSetItemName()) {
                $parameters['Item' . '.'  . ($itembatchPutAttributesRequestIndex + 1) . '.' . 'ItemName'] =  $itembatchPutAttributesRequest->getItemName();
            }
            foreach ($itembatchPutAttributesRequest->getAttribute() as $attributeitemIndex => $attributeitem) {
                if ($attributeitem->isSetName()) {
                    $parameters['Item' . '.'  . ($itembatchPutAttributesRequestIndex + 1) . '.' . 'Attribute' . '.'  . ($attributeitemIndex + 1) . '.' . 'Name'] =  $attributeitem->getName();
                }
                if ($attributeitem->isSetValue()) {
                    $parameters['Item' . '.'  . ($itembatchPutAttributesRequestIndex + 1) . '.' . 'Attribute' . '.'  . ($attributeitemIndex + 1) . '.' . 'Value'] =  $attributeitem->getValue();
                }
                if ($attributeitem->isSetReplace()) {
                    $parameters['Item' . '.'  . ($itembatchPutAttributesRequestIndex + 1) . '.' . 'Attribute' . '.'  . ($attributeitemIndex + 1) . '.' . 'Replace'] =  $attributeitem->getReplace() ? "true" : "false";
                }

            }

        }

        return $parameters;
    }
        
                                        
    /**
     * Convert GetAttributesRequest to name value pairs
     */
    private function _convertGetAttributes($request) {
        
        $parameters = array();
        $parameters['Action'] = 'GetAttributes';
        if ($request->isSetDomainName()) {
            $parameters['DomainName'] =  $request->getDomainName();
        }
        if ($request->isSetItemName()) {
            $parameters['ItemName'] =  $request->getItemName();
        }
        foreach  ($request->getAttributeName() as $attributeNamegetAttributesRequestIndex => $attributeNamegetAttributesRequest) {
            $parameters['AttributeName' . '.'  . ($attributeNamegetAttributesRequestIndex + 1)] =  $attributeNamegetAttributesRequest;
        }

        return $parameters;
    }
        
                                                
    /**
     * Convert DeleteAttributesRequest to name value pairs
     */
    private function _convertDeleteAttributes($request) {
        
        $parameters = array();
        $parameters['Action'] = 'DeleteAttributes';
        if ($request->isSetDomainName()) {
            $parameters['DomainName'] =  $request->getDomainName();
        }
        if ($request->isSetItemName()) {
            $parameters['ItemName'] =  $request->getItemName();
        }
        foreach ($request->getAttribute() as $attributedeleteAttributesRequestIndex => $attributedeleteAttributesRequest) {
            if ($attributedeleteAttributesRequest->isSetName()) {
                $parameters['Attribute' . '.'  . ($attributedeleteAttributesRequestIndex + 1) . '.' . 'Name'] =  $attributedeleteAttributesRequest->getName();
            }
            if ($attributedeleteAttributesRequest->isSetValue()) {
                $parameters['Attribute' . '.'  . ($attributedeleteAttributesRequestIndex + 1) . '.' . 'Value'] =  $attributedeleteAttributesRequest->getValue();
            }

        }

        return $parameters;
    }
        
                                        
    /**
     * Convert SelectRequest to name value pairs
     */
    private function _convertSelect($request) {
        
        $parameters = array();
        $parameters['Action'] = 'Select';
        if ($request->isSetSelectExpression()) {
            $parameters['SelectExpression'] =  $request->getSelectExpression();
        }
        if ($request->isSetNextToken()) {
            $parameters['NextToken'] =  $request->getNextToken();
        }

        return $parameters;
    }
        
                        
}
