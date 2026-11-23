<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Endpoint;

class PostConnectRefresh extends \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\Client\BaseEndpoint implements \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\Client\Endpoint
{
    protected $accept;
    /**
     * Issues a fresh access token for an established connection. No body, the DPoP proof is the credential.
     * @param array{
     *    "dpop": string, //DPoP proof JWT bound to the identity provider token endpoint.
     * } $headerParameters
     * @param array $accept Accept content header application/json|application/problem+json
     */
    public function __construct(array $headerParameters, array $accept = [])
    {
        $this->headerParameters = $headerParameters;
        $this->accept = $accept;
    }
    use \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\Client\EndpointTrait;
    public function getMethod(): string
    {
        return 'POST';
    }
    public function getUri(): string
    {
        return '/connect/refresh';
    }
    public function getBody(\Symfony\Component\Serializer\SerializerInterface $serializer): array
    {
        return [[], null];
    }
    public function getExtraHeaders(): array
    {
        if (empty($this->accept)) {
            return ['Accept' => ['application/json', 'application/problem+json']];
        }
        return $this->accept;
    }
    protected function getHeadersOptionsResolver(): \Symfony\Component\OptionsResolver\OptionsResolver
    {
        $optionsResolver = parent::getHeadersOptionsResolver();
        $optionsResolver->setDefined(['dpop']);
        $optionsResolver->setRequired(['dpop']);
        $optionsResolver->setDefaults([]);
        $optionsResolver->addAllowedTypes('dpop', ['string']);
        return $optionsResolver;
    }
    /**
     * {@inheritdoc}
     *
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\PostConnectRefreshBadRequestException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\PostConnectRefreshUnauthorizedException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\PostConnectRefreshUnsupportedMediaTypeException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\PostConnectRefreshInternalServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectRefreshPostResponse200
     */
    protected function transformResponseBody(\Symfony\Contracts\HttpClient\ResponseInterface $response, \Symfony\Component\Serializer\SerializerInterface $serializer, ?string $contentType = null)
    {
        $status = $response->getStatusCode();
        $body = $response->getContent(false);
        if ($contentType !== null && (200 === $status && stripos(strtolower($contentType), 'application/json') !== false)) {
            return $serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectRefreshPostResponse200', 'json');
        }
        if ($contentType !== null && (400 === $status && stripos(strtolower($contentType), 'application/json') !== false)) {
            try {
                $decodedBody = json_decode($body, false, 512, JSON_THROW_ON_ERROR);
                throw new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\PostConnectRefreshBadRequestException($response);
            } catch (\JsonException $jsonException) {
                throw new \Jane\Component\JsonSchemaRuntime\Exception\MalformedJsonException('Malformed JSON response body.', 0, $jsonException);
            }
        }
        if ($contentType !== null && (401 === $status && stripos(strtolower($contentType), 'application/json') !== false)) {
            throw new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\PostConnectRefreshUnauthorizedException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectRefreshPostResponse401', 'json'), $response);
        }
        if ($contentType !== null && (415 === $status && stripos(strtolower($contentType), 'application/problem+json') !== false)) {
            throw new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\PostConnectRefreshUnsupportedMediaTypeException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ProblemDetailsUnsupportedMediaType', 'json'), $response);
        }
        if (500 === $status) {
            if (stripos(strtolower((string) $contentType), 'application/json') !== false) {
                throw new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\PostConnectRefreshInternalServerErrorException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectRefreshPostJsonResponse500', 'json'), $response);
            }
            if (stripos(strtolower((string) $contentType), 'application/problem+json') !== false) {
                throw new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\PostConnectRefreshInternalServerErrorException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ProblemDetailsInternalServerError', 'json'), $response);
            }
        }
        throw new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\BadResponseException($status, $body, $response);
    }
    public function getAuthenticationScopes(): array
    {
        return [];
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