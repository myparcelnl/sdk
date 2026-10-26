<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint;

class GetShipments extends \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\BaseEndpoint implements \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\Endpoint
{
    /**
     * This operation returns a list of Shipments available to this User.
     * @param array{
     *    "barcode"?: string,
     *    "carrier_id"?: mixed,
     *    "created"?: string, //When set, only resources created after this date will be returned. Inclusive.
     *    "delayed"?: mixed, //Filter on whether the current event code means the shipment has been delayed.
     *    "delivered"?: bool,
     *    "dropoff_today"?: bool, //Use this parameter to only show Shipments that need to be dropped off today.
     *    "external_identifier"?: string,
     *    "filter_hidden_shops"?: bool,
     *    "hidden"?: bool,
     *    "include_secondary_shipments"?: mixed, //Whether to include secondary (multicollo child) Shipments in the results.
     *                                           //Pass `1` to include them; `0` (the default) excludes them.
     *    "link_consumer_portal"?: bool,
     *    "order"?: mixed, //Specify whether the results should be sorted in ascending or descending order.
     *    "package_type"?: mixed, //Filter by Package Type.
     *    "page"?: int, //Request a specific page of the results, used for paginated results.
     *    "q"?: string, //If this parameter is provided results will be filtered by the provided query or keyword.
     *    "reference_identifier"?: string, //Filter by `reference_identifier`, an optional arbitrary identifier to identify the Shipment.
     *    "region"?: string, //The region grouping of a Shipment. The concrete country value corresponds
     *                       //to the home country of the Shipment's platform; other country values
     *                       //yield no results for that platform.
     *    "shipment_type"?: mixed,
     *    "shop_id"?: mixed,
     *    "size"?: int, //Specify the number of resources returned per page, used for paginated results.
     *    "sort"?: string, //Sort Shipment results by a particular resource field.
     *    "status"?: mixed, //Filter by Shipment status. This filter will return only Shipments with the specified status.
     *    "transaction_status"?: string,
     * } $queryParameters
     * @param array{
     *    "User-Agent"?: string, //To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
     * } $headerParameters
     */
    public function __construct(array $queryParameters = [], array $headerParameters = [])
    {
        $this->queryParameters = $queryParameters;
        $this->headerParameters = $headerParameters;
    }
    use \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\EndpointTrait;
    public function getMethod(): string
    {
        return 'GET';
    }
    public function getUri(): string
    {
        return '/shipments';
    }
    public function getBody(\Symfony\Component\Serializer\SerializerInterface $serializer): array
    {
        return [[], null];
    }
    public function getExtraHeaders(): array
    {
        return ['Accept' => ['application/json']];
    }
    protected function getQueryOptionsResolver(): \Symfony\Component\OptionsResolver\OptionsResolver
    {
        $optionsResolver = parent::getQueryOptionsResolver();
        $optionsResolver->setDefined(['barcode', 'carrier_id', 'created', 'delayed', 'delivered', 'dropoff_today', 'external_identifier', 'filter_hidden_shops', 'hidden', 'include_secondary_shipments', 'link_consumer_portal', 'order', 'package_type', 'page', 'q', 'reference_identifier', 'region', 'shipment_type', 'shop_id', 'size', 'sort', 'status', 'transaction_status']);
        $optionsResolver->setRequired([]);
        $optionsResolver->setDefaults([]);
        $optionsResolver->addAllowedTypes('barcode', ['string']);
        $optionsResolver->addAllowedTypes('created', ['string']);
        $optionsResolver->addAllowedTypes('delivered', ['bool', 'null']);
        $optionsResolver->addAllowedTypes('dropoff_today', ['bool']);
        $optionsResolver->addAllowedTypes('external_identifier', ['string', 'null']);
        $optionsResolver->addAllowedTypes('filter_hidden_shops', ['bool']);
        $optionsResolver->addAllowedTypes('hidden', ['bool', 'null']);
        $optionsResolver->addAllowedTypes('link_consumer_portal', ['bool']);
        $optionsResolver->addAllowedTypes('page', ['int']);
        $optionsResolver->addAllowedTypes('q', ['string', 'null']);
        $optionsResolver->addAllowedTypes('reference_identifier', ['string']);
        $optionsResolver->addAllowedTypes('region', ['string']);
        $optionsResolver->addAllowedTypes('size', ['int']);
        $optionsResolver->addAllowedTypes('sort', ['string']);
        $optionsResolver->addAllowedTypes('transaction_status', ['string']);
        return $optionsResolver;
    }
    protected function getHeadersOptionsResolver(): \Symfony\Component\OptionsResolver\OptionsResolver
    {
        $optionsResolver = parent::getHeadersOptionsResolver();
        $optionsResolver->setDefined(['User-Agent']);
        $optionsResolver->setRequired([]);
        $optionsResolver->setDefaults([]);
        $optionsResolver->addAllowedTypes('User-Agent', ['string']);
        return $optionsResolver;
    }
    /**
     * {@inheritdoc}
     *
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetShipmentsClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetShipmentsServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesShipments
     */
    protected function transformResponseBody(\Symfony\Contracts\HttpClient\ResponseInterface $response, \Symfony\Component\Serializer\SerializerInterface $serializer, ?string $contentType = null)
    {
        $status = $response->getStatusCode();
        $body = $response->getContent(false);
        if ($contentType !== null && (200 === $status && stripos(strtolower($contentType), 'application/json') !== false)) {
            return $serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesShipments', 'json');
        }
        if ($contentType !== null && ($status >= 400 && $status <= 499 && stripos(strtolower($contentType), 'application/json') !== false)) {
            throw new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetShipmentsClientErrorException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesUserError', 'json'), $response);
        }
        if ($contentType !== null && ($status >= 500 && $status <= 599 && stripos(strtolower($contentType), 'application/json') !== false)) {
            throw new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetShipmentsServerErrorException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesSystemError', 'json'), $response);
        }
        throw new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\BadResponseException($status, $body, $response);
    }
    public function getAuthenticationScopes(): array
    {
        return ['bearer', 'apiKey'];
    }
    public function getFetchMode(): string
    {
        return \Jane\Component\OpenApiRuntime\Client\FetchMode::Eager->value;
    }
    public function getTargetClass(): ?string
    {
        return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesShipments::class;
    }
}