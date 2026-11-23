<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Endpoint;

class PostConnectToken extends \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\Client\BaseEndpoint implements \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\Client\Endpoint
{
    protected $accept;
    /**
     * Redeems the one-time code from the connect flow for the first access token. The code is single-use.
     * @param \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectTokenPostBody $requestBody
     * @param array{
     *    "dpop": string, //DPoP proof JWT bound to the identity provider token endpoint.
     * } $headerParameters
     * @param array $accept Accept content header application/json|application/problem+json
     */
    public function __construct(\MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectTokenPostBody $requestBody, array $headerParameters, array $accept = [])
    {
        $this->body = $requestBody;
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
        return '/connect/token';
    }
    public function getBody(\Symfony\Component\Serializer\SerializerInterface $serializer): array
    {
        if ($this->body instanceof \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectTokenPostBody) {
            return [['Content-Type' => ['application/json']], \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\Client\JsonPayload::encode($serializer, $this->body)];
        }
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
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\PostConnectTokenBadRequestException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\PostConnectTokenUnauthorizedException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\PostConnectTokenUnsupportedMediaTypeException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\PostConnectTokenInternalServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectTokenPostResponse200
     */
    protected function transformResponseBody(\Symfony\Contracts\HttpClient\ResponseInterface $response, \Symfony\Component\Serializer\SerializerInterface $serializer, ?string $contentType = null)
    {
        $status = $response->getStatusCode();
        $body = $response->getContent(false);
        if ($contentType !== null && (200 === $status && stripos(strtolower($contentType), 'application/json') !== false)) {
            return $serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectTokenPostResponse200', 'json');
        }
        if (400 === $status) {
            if (stripos(strtolower((string) $contentType), 'application/json') !== false) {
                try {
                    $decodedBody = json_decode($body, false, 512, JSON_THROW_ON_ERROR);
                    throw new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\PostConnectTokenBadRequestException($response);
                } catch (\JsonException $jsonException) {
                    throw new \Jane\Component\JsonSchemaRuntime\Exception\MalformedJsonException('Malformed JSON response body.', 0, $jsonException);
                }
            }
            if (stripos(strtolower((string) $contentType), 'application/problem+json') !== false) {
                throw new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\PostConnectTokenBadRequestException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ProblemDetailsInvalidRequestSyntax', 'json'), $response);
            }
        }
        if ($contentType !== null && (401 === $status && stripos(strtolower($contentType), 'application/json') !== false)) {
            throw new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\PostConnectTokenUnauthorizedException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectTokenPostResponse401', 'json'), $response);
        }
        if ($contentType !== null && (415 === $status && stripos(strtolower($contentType), 'application/problem+json') !== false)) {
            throw new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\PostConnectTokenUnsupportedMediaTypeException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ProblemDetailsUnsupportedMediaType', 'json'), $response);
        }
        if (500 === $status) {
            if (stripos(strtolower((string) $contentType), 'application/json') !== false) {
                throw new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\PostConnectTokenInternalServerErrorException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectTokenPostJsonResponse500', 'json'), $response);
            }
            if (stripos(strtolower((string) $contentType), 'application/problem+json') !== false) {
                throw new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\PostConnectTokenInternalServerErrorException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ProblemDetailsInternalServerError', 'json'), $response);
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