<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Endpoint;

class PostWebhookOrder extends \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\Client\BaseEndpoint implements \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\Client\Endpoint
{
    protected $accept;
    /**
     * Receives orders from a connected sales channel plugin. The plugin calls this endpoint whenever new orders are available in the shop.
     * @param \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\Order[] $requestBody
     * @param array $accept Accept content header application/json|application/problem+json
     */
    public function __construct(array $requestBody, array $accept = [])
    {
        $this->body = $requestBody;
        $this->accept = $accept;
    }
    use \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\Client\EndpointTrait;
    public function getMethod(): string
    {
        return 'POST';
    }
    public function getUri(): string
    {
        return '/webhook/orders';
    }
    public function getBody(\Symfony\Component\Serializer\SerializerInterface $serializer): array
    {
        if (is_array($this->body) and isset($this->body[0]) and $this->body[0] instanceof \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\Order) {
            return [['Content-Type' => ['application/json']], $serializer->serialize($this->body, 'json')];
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
    /**
     * {@inheritdoc}
     *
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\PostWebhookOrderBadRequestException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\PostWebhookOrderUnauthorizedException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\PostWebhookOrderNotFoundException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\PostWebhookOrderUnsupportedMediaTypeException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\PostWebhookOrderInternalServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\BadResponseException
     *
     * @return array
     */
    protected function transformResponseBody(\Symfony\Contracts\HttpClient\ResponseInterface $response, \Symfony\Component\Serializer\SerializerInterface $serializer, ?string $contentType = null)
    {
        $status = $response->getStatusCode();
        $body = $response->getContent(false);
        if ($contentType !== null && (202 === $status && stripos(strtolower($contentType), 'application/json') !== false)) {
            try {
                $decodedBody = json_decode($body, false, 512, JSON_THROW_ON_ERROR);
                return $decodedBody;
            } catch (\JsonException $jsonException) {
                throw new \Jane\Component\JsonSchemaRuntime\Exception\MalformedJsonException('Malformed JSON response body.', 0, $jsonException);
            }
        }
        if ($contentType !== null && (200 === $status && stripos(strtolower($contentType), 'application/json') !== false)) {
            try {
                $decodedBody = json_decode($body, false, 512, JSON_THROW_ON_ERROR);
                return $decodedBody;
            } catch (\JsonException $jsonException) {
                throw new \Jane\Component\JsonSchemaRuntime\Exception\MalformedJsonException('Malformed JSON response body.', 0, $jsonException);
            }
        }
        if ($contentType !== null && (400 === $status && stripos(strtolower($contentType), 'application/problem+json') !== false)) {
            throw new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\PostWebhookOrderBadRequestException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ProblemDetailsInvalidRequestSyntax', 'json'), $response);
        }
        if ($contentType !== null && (401 === $status && stripos(strtolower($contentType), 'application/json') !== false)) {
            throw new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\PostWebhookOrderUnauthorizedException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\UnauthorizedResponse', 'json'), $response);
        }
        if ($contentType !== null && (404 === $status && stripos(strtolower($contentType), 'application/problem+json') !== false)) {
            throw new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\PostWebhookOrderNotFoundException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ProblemDetailsNotFound', 'json'), $response);
        }
        if ($contentType !== null && (415 === $status && stripos(strtolower($contentType), 'application/problem+json') !== false)) {
            throw new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\PostWebhookOrderUnsupportedMediaTypeException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ProblemDetailsUnsupportedMediaType', 'json'), $response);
        }
        if ($contentType !== null && (500 === $status && stripos(strtolower($contentType), 'application/problem+json') !== false)) {
            throw new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\PostWebhookOrderInternalServerErrorException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ProblemDetailsInternalServerError', 'json'), $response);
        }
        throw new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception\BadResponseException($status, $body, $response);
    }
    public function getAuthenticationScopes(): array
    {
        return ['dpop'];
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