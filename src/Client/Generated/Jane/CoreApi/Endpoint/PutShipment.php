<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint;

class PutShipment extends \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\BaseEndpoint implements \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\Endpoint
{
    protected $accept;
    /**
     * This operation can be used to update certain fields of a shipment. The fields that can be modified depend on the status of the shipment.
     * @param \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequestV11|\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequest $requestBody
     * @param array{
     *    "User-Agent"?: string, //To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
     * } $headerParameters
     * @param array $accept Accept content header application/json;charset=utf-8;version=1.2|application/json|application/*|application/problem+json
     */
    public function __construct($requestBody, array $headerParameters = [], array $accept = [])
    {
        $this->body = $requestBody;
        $this->headerParameters = $headerParameters;
        $this->accept = $accept;
    }
    use \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\EndpointTrait;
    public function getMethod(): string
    {
        return 'PUT';
    }
    public function getUri(): string
    {
        return '/shipments';
    }
    public function getBody(\Symfony\Component\Serializer\SerializerInterface $serializer): array
    {
        if ($this->body instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequestV11) {
            return [['Content-Type' => ['application/vnd.shipment+json;version=1.1']], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\JsonPayload::encode($serializer, $this->body)];
        }
        if ($this->body instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequest) {
            return [['Content-Type' => ['application/vnd.shipment+json']], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\JsonPayload::encode($serializer, $this->body)];
        }
        return [[], null];
    }
    public function getExtraHeaders(): array
    {
        if (empty($this->accept)) {
            return ['Accept' => ['application/json;charset=utf-8;version=1.2', 'application/json', 'application/*', 'application/problem+json']];
        }
        return $this->accept;
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
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PutShipmentUnsupportedMediaTypeException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PutShipmentClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PutShipmentServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPutShipmentsV12|null
     */
    protected function transformResponseBody(\Symfony\Contracts\HttpClient\ResponseInterface $response, \Symfony\Component\Serializer\SerializerInterface $serializer, ?string $contentType = null)
    {
        $status = $response->getStatusCode();
        $body = $response->getContent(false);
        if (200 === $status) {
            if (stripos(strtolower((string) $contentType), 'application/json') !== false) {
                return $serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPutShipmentsV12', 'json');
            }
            if (stripos(strtolower((string) $contentType), 'application/json') !== false) {
                return $serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPutShipmentsV12', 'json');
            }
        }
        if (204 === $status) {
            return null;
        }
        if ($contentType !== null && (415 === $status && stripos(strtolower($contentType), 'application/problem+json') !== false)) {
            throw new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PutShipmentUnsupportedMediaTypeException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesProblem', 'json'), $response);
        }
        if ($status >= 400 && $status <= 499) {
            if (stripos(strtolower((string) $contentType), 'application/json') !== false) {
                throw new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PutShipmentClientErrorException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesUserError', 'json'), $response);
            }
            if (stripos(strtolower((string) $contentType), 'application/problem+json') !== false) {
                throw new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PutShipmentClientErrorException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesProblem', 'json'), $response);
            }
        }
        if ($status >= 500 && $status <= 599) {
            if (stripos(strtolower((string) $contentType), 'application/json') !== false) {
                throw new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PutShipmentServerErrorException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesSystemError', 'json'), $response);
            }
            if (stripos(strtolower((string) $contentType), 'application/problem+json') !== false) {
                throw new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PutShipmentServerErrorException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesProblem', 'json'), $response);
            }
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
        return null;
    }
}