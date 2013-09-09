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
 */

interface  Amazon_SimpleDB_Interface 
{
    

            
    /**
     * Create Domain 
     * The CreateDomain operation creates a new domain. The domain name must be unique
     * among the domains associated with the Access Key ID provided in the request. The CreateDomain
     * operation may take 10 or more seconds to complete.
     *   
     * @see http://docs.amazonwebservices.com/AmazonSimpleDB/2009-04-15/DeveloperGuide/SDB_API_CreateDomain.html      
     * @param mixed $request array of parameters for Amazon_SimpleDB_Model_CreateDomainRequest request
     * or Amazon_SimpleDB_Model_CreateDomainRequest object itself
     * @see Amazon_SimpleDB_Model_CreateDomainRequest
     * @return Amazon_SimpleDB_Model_CreateDomainResponse Amazon_SimpleDB_Model_CreateDomainResponse
     *
     * @throws Amazon_SimpleDB_Exception
     */
    public function createDomain($request);


            
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
     * @see Amazon_SimpleDB_Model_ListDomainsRequest
     * @return Amazon_SimpleDB_Model_ListDomainsResponse Amazon_SimpleDB_Model_ListDomainsResponse
     *
     * @throws Amazon_SimpleDB_Exception
     */
    public function listDomains($request);


            
    /**
     * Domain Metadata 
     * The DomainMetadata operation returns some domain metadata values, such as the
     * number of items, attribute names and attribute values along with their sizes.
     *   
     * @see http://docs.amazonwebservices.com/AmazonSimpleDB/2009-04-15/DeveloperGuide/SDB_API_DomainMetadata.html      
     * @param mixed $request array of parameters for Amazon_SimpleDB_Model_DomainMetadataRequest request
     * or Amazon_SimpleDB_Model_DomainMetadataRequest object itself
     * @see Amazon_SimpleDB_Model_DomainMetadataRequest
     * @return Amazon_SimpleDB_Model_DomainMetadataResponse Amazon_SimpleDB_Model_DomainMetadataResponse
     *
     * @throws Amazon_SimpleDB_Exception
     */
    public function domainMetadata($request);


            
    /**
     * Delete Domain 
     * The DeleteDomain operation deletes a domain. Any items (and their attributes) in the domain
     * are deleted as well. The DeleteDomain operation may take 10 or more seconds to complete.
     *   
     * @see http://docs.amazonwebservices.com/AmazonSimpleDB/2009-04-15/DeveloperGuide/SDB_API_DeleteDomain.html      
     * @param mixed $request array of parameters for Amazon_SimpleDB_Model_DeleteDomainRequest request
     * or Amazon_SimpleDB_Model_DeleteDomainRequest object itself
     * @see Amazon_SimpleDB_Model_DeleteDomainRequest
     * @return Amazon_SimpleDB_Model_DeleteDomainResponse Amazon_SimpleDB_Model_DeleteDomainResponse
     *
     * @throws Amazon_SimpleDB_Exception
     */
    public function deleteDomain($request);


            
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
     * @see Amazon_SimpleDB_Model_PutAttributesRequest
     * @return Amazon_SimpleDB_Model_PutAttributesResponse Amazon_SimpleDB_Model_PutAttributesResponse
     *
     * @throws Amazon_SimpleDB_Exception
     */
    public function putAttributes($request);


            
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
     * @see Amazon_SimpleDB_Model_BatchPutAttributesRequest
     * @return Amazon_SimpleDB_Model_BatchPutAttributesResponse Amazon_SimpleDB_Model_BatchPutAttributesResponse
     *
     * @throws Amazon_SimpleDB_Exception
     */
    public function batchPutAttributes($request);


            
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
     * @see Amazon_SimpleDB_Model_GetAttributesRequest
     * @return Amazon_SimpleDB_Model_GetAttributesResponse Amazon_SimpleDB_Model_GetAttributesResponse
     *
     * @throws Amazon_SimpleDB_Exception
     */
    public function getAttributes($request);


            
    /**
     * Delete Attributes 
     * Deletes one or more attributes associated with the item. If all attributes of an item are deleted, the item is
     * deleted.
     *   
     * @see http://docs.amazonwebservices.com/AmazonSimpleDB/2009-04-15/DeveloperGuide/SDB_API_DeleteAttributes.html      
     * @param mixed $request array of parameters for Amazon_SimpleDB_Model_DeleteAttributesRequest request
     * or Amazon_SimpleDB_Model_DeleteAttributesRequest object itself
     * @see Amazon_SimpleDB_Model_DeleteAttributesRequest
     * @return Amazon_SimpleDB_Model_DeleteAttributesResponse Amazon_SimpleDB_Model_DeleteAttributesResponse
     *
     * @throws Amazon_SimpleDB_Exception
     */
    public function deleteAttributes($request);


            
    /**
     * Select 
     * The Select operation returns a set of item names and associate attributes that match the
     * query expression. Select operations that run longer than 5 seconds will likely time-out
     * and return a time-out error response.
     *   
     * @see http://docs.amazonwebservices.com/AmazonSimpleDB/2009-04-15/DeveloperGuide/SDB_API_Select.html      
     * @param mixed $request array of parameters for Amazon_SimpleDB_Model_SelectRequest request
     * or Amazon_SimpleDB_Model_SelectRequest object itself
     * @see Amazon_SimpleDB_Model_SelectRequest
     * @return Amazon_SimpleDB_Model_SelectResponse Amazon_SimpleDB_Model_SelectResponse
     *
     * @throws Amazon_SimpleDB_Exception
     */
    public function select($request);

}
