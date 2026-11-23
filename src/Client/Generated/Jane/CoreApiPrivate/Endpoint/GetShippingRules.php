<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Endpoint;

class GetShippingRules extends \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Runtime\Client\BaseEndpoint implements \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Runtime\Client\Endpoint
{
    /**
     * Return the shipping rules visible to the authenticated account. When `shop_ids[]` is supplied, the result is filtered to those shops.
     *
     * @param array{
     *    "page"?: int, //Request a specific page of the results, used for paginated results.
     *    "size"?: int, //Number of shipping rules returned per page. Maximum 500.
     *    "shop_ids"?: array, //Filter shipping rules by shop. Repeat the parameter for multiple shops.
     *    "type"?: string, //Filter by shipping rule type.
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
    use \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Runtime\Client\EndpointTrait;
    public function getMethod(): string
    {
        return 'GET';
    }
    public function getUri(): string
    {
        return '/shipping_rules';
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
        $optionsResolver->setDefined(['page', 'size', 'shop_ids', 'type']);
        $optionsResolver->setRequired([]);
        $optionsResolver->setDefaults([]);
        $optionsResolver->addAllowedTypes('page', ['int']);
        $optionsResolver->addAllowedTypes('size', ['int']);
        $optionsResolver->addAllowedTypes('shop_ids', ['array']);
        $optionsResolver->addAllowedTypes('type', ['string']);
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
    protected function getQueryStyles(): array
    {
        return ['shop_ids' => ['style' => 'form', 'explode' => true]];
    }
    /**
     * {@inheritdoc}
     *
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Exception\GetShippingRulesClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Exception\GetShippingRulesServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesResponsesShippingRulesPaginated
     */
    protected function transformResponseBody(\Symfony\Contracts\HttpClient\ResponseInterface $response, \Symfony\Component\Serializer\SerializerInterface $serializer, ?string $contentType = null)
    {
        $status = $response->getStatusCode();
        $body = $response->getContent(false);
        if ($contentType !== null && (200 === $status && stripos(strtolower($contentType), 'application/json') !== false)) {
            return $serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesResponsesShippingRulesPaginated', 'json');
        }
        if ($contentType !== null && ($status >= 400 && $status <= 499 && stripos(strtolower($contentType), 'application/json') !== false)) {
            throw new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Exception\GetShippingRulesClientErrorException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\CommonResponsesUserError', 'json'), $response);
        }
        if ($contentType !== null && ($status >= 500 && $status <= 599 && stripos(strtolower($contentType), 'application/json') !== false)) {
            throw new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Exception\GetShippingRulesServerErrorException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\CommonResponsesSystemError', 'json'), $response);
        }
        throw new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Exception\BadResponseException($status, $body, $response);
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
        return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesResponsesShippingRulesPaginated::class;
    }
}