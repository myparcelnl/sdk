<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint;

class GetShipmentsById extends \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\BaseEndpoint implements \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\Endpoint
{
    protected $ids;
    /**
     * Get shipments by id. A maximum of 100 shipment IDs can be requested at once.
     * @param mixed $ids One or more shipment IDs. Separate multiple shipment IDs using `;`.
     * @param array{
     *    "link_consumer_portal"?: bool,
     * } $queryParameters
     * @param array{
     *    "User-Agent"?: string, //To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
     * } $headerParameters
     */
    public function __construct($ids, array $queryParameters = [], array $headerParameters = [])
    {
        $this->ids = $ids;
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
        return str_replace(['{ids}'], [rawurlencode((string) $this->ids)], '/shipments/{ids}');
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
        $optionsResolver->setDefined(['link_consumer_portal']);
        $optionsResolver->setRequired([]);
        $optionsResolver->setDefaults([]);
        $optionsResolver->addAllowedTypes('link_consumer_portal', ['bool']);
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
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetShipmentsByIdClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetShipmentsByIdServerErrorException
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
            throw new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetShipmentsByIdClientErrorException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesUserError', 'json'), $response);
        }
        if ($contentType !== null && ($status >= 500 && $status <= 599 && stripos(strtolower($contentType), 'application/json') !== false)) {
            throw new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetShipmentsByIdServerErrorException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesSystemError', 'json'), $response);
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