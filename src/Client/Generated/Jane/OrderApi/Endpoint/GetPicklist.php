<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Endpoint;

class GetPicklist extends \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\Client\BaseEndpoint implements \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\Client\Endpoint
{
    protected $accept;
    /**
     * Get picklist. Accepts `application/pdf` to download the picklist as a PDF, or `application/pdf+print` with a `printer-group-id` parameter to send the picklist directly to a printer.
     * @param array{
     *    "orderId": array, //The orderId of the order to include in the picklist. A maximum of 50 orders can be included in the request.
     * } $queryParameters
     * @param array{
     *    "Accept": string, //Use `application/pdf` to download the picklist. Use `application/pdf+print; printer-group-id=<uuid>` to send the picklist directly to a printer group.
     * } $headerParameters
     * @param array $accept Accept content header application/pdf|application/json
     */
    public function __construct(array $queryParameters, array $headerParameters, array $accept = [])
    {
        $this->queryParameters = $queryParameters;
        $this->headerParameters = $headerParameters;
        $this->accept = $accept;
    }
    use \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\Client\EndpointTrait;
    public function getMethod(): string
    {
        return 'GET';
    }
    public function getUri(): string
    {
        return '/picklist';
    }
    public function getBody(\Symfony\Component\Serializer\SerializerInterface $serializer): array
    {
        return [[], null];
    }
    public function getExtraHeaders(): array
    {
        if (empty($this->accept)) {
            return ['Accept' => ['application/pdf', 'application/json']];
        }
        return $this->accept;
    }
    protected function getQueryOptionsResolver(): \Symfony\Component\OptionsResolver\OptionsResolver
    {
        $optionsResolver = parent::getQueryOptionsResolver();
        $optionsResolver->setDefined(['orderId']);
        $optionsResolver->setRequired(['orderId']);
        $optionsResolver->setDefaults([]);
        $optionsResolver->addAllowedTypes('orderId', ['array']);
        return $optionsResolver;
    }
    protected function getHeadersOptionsResolver(): \Symfony\Component\OptionsResolver\OptionsResolver
    {
        $optionsResolver = parent::getHeadersOptionsResolver();
        $optionsResolver->setDefined(['Accept']);
        $optionsResolver->setRequired(['Accept']);
        $optionsResolver->setDefaults([]);
        $optionsResolver->addAllowedTypes('Accept', ['string']);
        return $optionsResolver;
    }
    protected function getQueryStyles(): array
    {
        return ['orderId' => ['style' => 'form', 'explode' => true]];
    }
    /**
     * {@inheritdoc}
     *
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\GetPicklistBadRequestException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\BadResponseException
     *
     * @return null
     */
    protected function transformResponseBody(\Symfony\Contracts\HttpClient\ResponseInterface $response, \Symfony\Component\Serializer\SerializerInterface $serializer, ?string $contentType = null)
    {
        $status = $response->getStatusCode();
        $body = $response->getContent(false);
        if (200 === $status) {
        }
        if (204 === $status) {
            return null;
        }
        if ($contentType !== null && (400 === $status && stripos(strtolower($contentType), 'application/json') !== false)) {
            throw new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\GetPicklistBadRequestException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ProblemDetailsInvalidRequestSyntax', 'json'), $response);
        }
        throw new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\BadResponseException($status, $body, $response);
    }
    public function getAuthenticationScopes(): array
    {
        return ['apiKey', 'jwt'];
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