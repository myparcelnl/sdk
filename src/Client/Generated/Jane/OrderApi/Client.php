<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi;

class Client extends \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\Client\Client
{
    /**
     * Add an order note. Only possible if the order has `status=OPEN` and the maximum number of 100 notes is not reached.
     * @param \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\AddNotePostBodyItem[] $requestBody
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\PostAddNoteBadRequestException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\BadResponseException
     *
     * @return array
     */
    public function postAddNote(array $requestBody)
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Endpoint\PostAddNote($requestBody));
    }
    /**
     * Assign orders to an user. Only possible if the order has `status=OPEN`.
     * @param \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\AssignToUserPostBodyItem[] $requestBody
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\PostAssignToUserBadRequestException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\BadResponseException
     *
     * @return array
     */
    public function postAssignToUser(array $requestBody)
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Endpoint\PostAssignToUser($requestBody));
    }
    /**
     * Cancel orders. Only possible if the order has `status=OPEN`.
     * @param \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\CancelPostBodyItem[] $requestBody
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\PostCancelBadRequestException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\BadResponseException
     *
     * @return array
     */
    public function postCancel(array $requestBody)
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Endpoint\PostCancel($requestBody));
    }
    /**
     * Create orders for a shop. Accepted orders are processed asynchronously.
     * @param \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrderToCreate[] $requestBody
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\PostCreateBadRequestException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\CreatePostResponse202
     */
    public function postCreate(array $requestBody)
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Endpoint\PostCreate($requestBody));
    }
    /**
     * Create an order with packages that contain shipment properties.
     * @param array[] $requestBody
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\PostCreateFromShippablePackagesBadRequestException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\BadResponseException
     *
     * @return array
     */
    public function postCreateFromShippablePackages(array $requestBody)
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Endpoint\PostCreateFromShippablePackages($requestBody));
    }
    /**
     * Add packages to orders
     * @param \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\AddPackages[] $requestBody
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\PostAddPackagesBadRequestException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\BadResponseException
     *
     * @return array
     */
    public function postAddPackages(array $requestBody)
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Endpoint\PostAddPackages($requestBody));
    }
    /**
     * Prepares packages for shipment
     * @param array[] $requestBody
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\PostPreparePackagesForShipmentBadRequestException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\BadResponseException
     *
     * @return array
     */
    public function postPreparePackagesForShipment(array $requestBody)
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Endpoint\PostPreparePackagesForShipment($requestBody));
    }
    /**
     * Unprepares packages for shipment. Only possible if the order has `status=OPEN`.
     * @param \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\UnpreparePackagesForShipmentPostBodyItem[] $requestBody
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\PostUnpreparePackagesForShipmentBadRequestException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\BadResponseException
     *
     * @return array
     */
    public function postUnpreparePackagesForShipment(array $requestBody)
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Endpoint\PostUnpreparePackagesForShipment($requestBody));
    }
    /**
     * Edit order note. Only possible if the order has `status=OPEN`.
     * @param \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\EditNotePostBodyItem[] $requestBody
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\PostEditNoteBadRequestException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\BadResponseException
     *
     * @return array
     */
    public function postEditNote(array $requestBody)
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Endpoint\PostEditNote($requestBody));
    }
    /**
     * Import a discovered order. Only possible if the order has `status=DISCOVERED`.
     * @param \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ImportPostBodyItem[] $requestBody
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\PostImportBadRequestException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\BadResponseException
     *
     * @return array
     */
    public function postImport(array $requestBody)
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Endpoint\PostImport($requestBody));
    }
    /**
     * Get orders.
     * @param array{
     *    "aggregations"?: array, //The statuses to retrieve counts for.
     *    "filter"?: array,
     *    "limit"?: int, //The maximum number of orders to retrieve.
     *    "pageToken"?: string, //The page token to retrieve the next page of orders.
     *    "query"?: string, //The following operators are supported:
     *                      //'+ signifies AND operation'
     *                      //'| signifies OR operation'
     *                      //'- negates a single token'
     *                      //'" wraps a number of tokens to signify a phrase for searching'
     *                      //'* at the end of a term signifies a prefix query'
     *                      //'( and ) signify precedence'
     *                      //'~N after a word signifies edit distance (fuzziness)'
     *                      //'~N after a phrase signifies slop amount'
     *                      //To use one of these characters literally, escape it with a preceding backslash (\).
     *    "sort"?: array, //When no `query` is provided the default sort is by `orderedAt` descending. When a `query` is provided the default sort is by relevance.
     * } $queryParameters
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\GetOrderBadRequestException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrdersGetResponse200
     */
    public function getOrder(array $queryParameters = [])
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Endpoint\GetOrder($queryParameters));
    }
    /**
     * Get packing slips for one or more orders. When packageId is provided, exactly one orderId must be supplied and the packageIds must belong to that order.
     * @param array{
     *    "shipmentLabelsLayout"?: string, //How shipment labels are included alongside the packing slips in the resulting PDF. `INTERLEAVED` outputs each packing slip immediately followed by its shipment label. When the parameter is omitted, no shipment labels are included and only packing slips are rendered.
     *    "orderId": array, //The orderId of the order to include in the packing slips. A maximum of 50 orders can be included in the request. When packageId is provided, exactly one orderId is allowed.
     *    "packageId"?: array, //The packageId of the package to include in the packing slips. The packageIds must belong to the single orderId in the request and a maximum of 20 packageIds can be included.
     * } $queryParameters
     * @param array{
     *    "Accept": string, //Use `application/pdf` to download the packing slip. Use `application/pdf+print; printer-group-id=<uuid>` to send the packing slip directly to a printer group.
     * } $headerParameters
     * @param array $accept Accept content header application/pdf|application/json
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\GetPackingSlipBadRequestException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\BadResponseException
     *
     * @return null
     */
    public function getPackingSlip(array $queryParameters, array $headerParameters, array $accept = [])
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Endpoint\GetPackingSlip($queryParameters, $headerParameters, $accept));
    }
    /**
     * Get picklist. Accepts `application/pdf` to download the picklist as a PDF, or `application/pdf+print` with a `printer-group-id` parameter to send the picklist directly to a printer.
     * @param array{
     *    "orderId": array, //The orderId of the order to include in the picklist. A maximum of 50 orders can be included in the request.
     * } $queryParameters
     * @param array{
     *    "Accept": string, //Use `application/pdf` to download the picklist. Use `application/pdf+print; printer-group-id=<uuid>` to send the picklist directly to a printer group.
     * } $headerParameters
     * @param array $accept Accept content header application/pdf|application/json
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\GetPicklistBadRequestException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\BadResponseException
     *
     * @return null
     */
    public function getPicklist(array $queryParameters, array $headerParameters, array $accept = [])
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Endpoint\GetPicklist($queryParameters, $headerParameters, $accept));
    }
    /**
     * Remove order notes. Only possible if the order has `status=OPEN`.
     * @param \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\RemoveNotesPostBodyItem[] $requestBody
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\PostRemoveNotesBadRequestException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\BadResponseException
     *
     * @return array
     */
    public function postRemoveNotes(array $requestBody)
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Endpoint\PostRemoveNotes($requestBody));
    }
    /**
     * Unassign orders from an user. Only possible if the order has `status=OPEN`
     * @param \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\UnassignFromUserPostBodyItem[] $requestBody
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\PostUnassignFromUserBadRequestException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\BadResponseException
     *
     * @return array
     */
    public function postUnassignFromUser(array $requestBody)
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Endpoint\PostUnassignFromUser($requestBody));
    }
    /**
     * Evaluate order rules.
     * @param \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\EvaluateRulesPostBodyItem[] $requestBody
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\PostEvaluateRulesBadRequestException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\BadResponseException
     *
     * @return array
     */
    public function postEvaluateRules(array $requestBody)
    {
        return $this->executeEndpoint(new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Endpoint\PostEvaluateRules($requestBody));
    }
    /**
     * @param list<callable(\Symfony\Contracts\HttpClient\HttpClientInterface): \Symfony\Contracts\HttpClient\HttpClientInterface> $additionalPlugins HttpClientInterface decorator factories, applied left-to-right after the server URL decorator
     * @param list<\Symfony\Component\Serializer\Normalizer\NormalizerInterface|\Symfony\Component\Serializer\Normalizer\DenormalizerInterface> $additionalNormalizers
     */
    public static function create(?\Symfony\Contracts\HttpClient\HttpClientInterface $httpClient = null, array $additionalPlugins = [], array $additionalNormalizers = [], bool $applyServerPlugins = true)
    {
        $plugins = [];
        if (null === $httpClient) {
            $httpClient = \Symfony\Component\HttpClient\HttpClient::create();
        }
        if ($applyServerPlugins) {
            $plugins[] = new \Jane\Component\OpenApiRuntime\Client\Plugin\ServerUrlHttpClient('https://order.api.myparcel.nl');
        }
        if (count($additionalPlugins) > 0) {
            $plugins = array_merge($plugins, $additionalPlugins);
        }
        foreach ($plugins as $plugin) {
            $httpClient = $plugin($httpClient);
        }
        $normalizers = [new \Symfony\Component\Serializer\Normalizer\ArrayDenormalizer(), new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\JaneObjectNormalizer()];
        if (count($additionalNormalizers) > 0) {
            $normalizers = array_merge($normalizers, $additionalNormalizers);
        }
        $serializer = new \Symfony\Component\Serializer\Serializer($normalizers, [new \Symfony\Component\Serializer\Encoder\JsonEncoder(new \Symfony\Component\Serializer\Encoder\JsonEncode(), new \Symfony\Component\Serializer\Encoder\JsonDecode(['json_decode_associative' => true])), new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\Client\FormEncoder()]);
        return new static($httpClient, $serializer);
    }
}