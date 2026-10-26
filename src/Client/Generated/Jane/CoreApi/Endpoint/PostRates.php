<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint;

class PostRates extends \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\BaseEndpoint implements \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\Endpoint
{
    protected $accept;
    /**
     * This endpoint allows you to determine the rates for a shipment configuration.
     * @param \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesPostRatesRequestV2|\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesPostRatesRequestV1 $requestBody
     * @param array{
     *    "User-Agent"?: string, //To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
     * } $headerParameters
     * @param array $accept Accept content header application/json;charset=utf-8;version=2.0|application/json;charset=utf-8|application/*|application/json
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
        return 'POST';
    }
    public function getUri(): string
    {
        return '/shipments/rates';
    }
    public function getBody(\Symfony\Component\Serializer\SerializerInterface $serializer): array
    {
        if ($this->body instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesPostRatesRequestV2) {
            return [['Content-Type' => ['application/json;version=2.0']], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\JsonPayload::encode($serializer, $this->body)];
        }
        if ($this->body instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesPostRatesRequestV1) {
            return [['Content-Type' => ['application/json']], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\JsonPayload::encode($serializer, $this->body)];
        }
        return [[], null];
    }
    public function getExtraHeaders(): array
    {
        if (empty($this->accept)) {
            return ['Accept' => ['application/json;charset=utf-8;version=2.0', 'application/json;charset=utf-8', 'application/*', 'application/json']];
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
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostRatesClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostRatesServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefRatesResponseRateV2|\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesResponsesRatesV1|null
     */
    protected function transformResponseBody(\Symfony\Contracts\HttpClient\ResponseInterface $response, \Symfony\Component\Serializer\SerializerInterface $serializer, ?string $contentType = null)
    {
        $status = $response->getStatusCode();
        $body = $response->getContent(false);
        if (200 === $status) {
            if (stripos(strtolower((string) $contentType), 'application/json') !== false) {
                return $serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefRatesResponseRateV2', 'json');
            }
            if (stripos(strtolower((string) $contentType), 'application/json') !== false) {
                return $serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesResponsesRatesV1', 'json');
            }
        }
        if (204 === $status) {
            return null;
        }
        if (415 === $status) {
        }
        if ($contentType !== null && ($status >= 400 && $status <= 499 && stripos(strtolower($contentType), 'application/json') !== false)) {
            throw new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostRatesClientErrorException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesUserError', 'json'), $response);
        }
        if ($contentType !== null && ($status >= 500 && $status <= 599 && stripos(strtolower($contentType), 'application/json') !== false)) {
            throw new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostRatesServerErrorException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesSystemError', 'json'), $response);
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