<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint;

class GetDropOffPoints extends \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\BaseEndpoint implements \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\Endpoint
{
    /**
     * Use this endpoint to receive a list of nearby drop off points, where shipments can be dropped off upon shipping. Results are ordered by distance from the provided postal code or coordinates.
     * @param array{
     *    "postal_code"?: string, //The postal code for which to fetch the resources.
     *    "number"?: string, //The street number for which to fetch the resources.
     *    "distance"?: int, //Provide the radius in kilometers for which you want to find drop off points. The default distance differs by carrier.
     *    "cc"?: string, //The country code for which to fetch the delivery options.
     *    "limit"?: int, //Limit the number of resources returned.
     *    "carrier_id"?: string,
     *    "shop_id"?: mixed,
     *    "reference"?: string, //Filter by `reference`.
     *    "location_name"?: string, //Filter by location name.
     *    "external_identifier"?: string,
     *    "city"?: string, //Only available for carriers Bpost and DPD. This can be used to narrow the search results for locations outside NL.
     *    "cut_off_time"?: string,
     *    "min_cut_off_time"?: string,
     *    "max_cut_off_time"?: string,
     *    "latitude"?: int, //This provides the ability to search locations through the coordinates. If only latitude is provided without longitude, it will be ignored.
     *    "longitude"?: int, //This provides the ability to search locations through the coordinates. If only longitude is provided without latitude, it will be ignored.
     *    "exclude_parcel_lockers"?: mixed, //This option allows to filter out pickup locations that are parcel lockers.
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
        return '/drop_off_points';
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
        $optionsResolver->setDefined(['postal_code', 'number', 'distance', 'cc', 'limit', 'carrier_id', 'shop_id', 'reference', 'location_name', 'external_identifier', 'city', 'cut_off_time', 'min_cut_off_time', 'max_cut_off_time', 'latitude', 'longitude', 'exclude_parcel_lockers']);
        $optionsResolver->setRequired([]);
        $optionsResolver->setDefaults([]);
        $optionsResolver->addAllowedTypes('postal_code', ['string', 'null']);
        $optionsResolver->addAllowedTypes('number', ['string', 'null']);
        $optionsResolver->addAllowedTypes('distance', ['int', 'null']);
        $optionsResolver->addAllowedTypes('cc', ['string', 'null']);
        $optionsResolver->addAllowedTypes('limit', ['int', 'null']);
        $optionsResolver->addAllowedTypes('carrier_id', ['string', 'null']);
        $optionsResolver->addAllowedTypes('reference', ['string', 'null']);
        $optionsResolver->addAllowedTypes('location_name', ['string', 'null']);
        $optionsResolver->addAllowedTypes('external_identifier', ['string', 'null']);
        $optionsResolver->addAllowedTypes('city', ['string', 'null']);
        $optionsResolver->addAllowedTypes('cut_off_time', ['string', 'null']);
        $optionsResolver->addAllowedTypes('min_cut_off_time', ['string', 'null']);
        $optionsResolver->addAllowedTypes('max_cut_off_time', ['string', 'null']);
        $optionsResolver->addAllowedTypes('latitude', ['int', 'null']);
        $optionsResolver->addAllowedTypes('longitude', ['int', 'null']);
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
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetDropOffPointsClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetDropOffPointsServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDropOffPoints
     */
    protected function transformResponseBody(\Symfony\Contracts\HttpClient\ResponseInterface $response, \Symfony\Component\Serializer\SerializerInterface $serializer, ?string $contentType = null)
    {
        $status = $response->getStatusCode();
        $body = $response->getContent(false);
        if ($contentType !== null && (200 === $status && stripos(strtolower($contentType), 'application/json') !== false)) {
            return $serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDropOffPoints', 'json');
        }
        if ($contentType !== null && ($status >= 400 && $status <= 499 && stripos(strtolower($contentType), 'application/json') !== false)) {
            throw new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetDropOffPointsClientErrorException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesUserError', 'json'), $response);
        }
        if ($contentType !== null && ($status >= 500 && $status <= 599 && stripos(strtolower($contentType), 'application/json') !== false)) {
            throw new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetDropOffPointsServerErrorException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesSystemError', 'json'), $response);
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
        return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDropOffPoints::class;
    }
}