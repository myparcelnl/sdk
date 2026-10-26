<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Endpoint;

class PostEditNote extends \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\Client\BaseEndpoint implements \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\Client\Endpoint
{
    /**
     * Edit order note. Only possible if the order has `status=OPEN`.
     * @param \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\EditNotePostBodyItem[] $requestBody
     */
    public function __construct(array $requestBody)
    {
        $this->body = $requestBody;
    }
    use \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\Client\EndpointTrait;
    public function getMethod(): string
    {
        return 'POST';
    }
    public function getUri(): string
    {
        return '/edit-note';
    }
    public function getBody(\Symfony\Component\Serializer\SerializerInterface $serializer): array
    {
        if (is_array($this->body) and isset($this->body[0]) and $this->body[0] instanceof \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\EditNotePostBodyItem) {
            return [['Content-Type' => ['application/json']], $serializer->serialize($this->body, 'json')];
        }
        return [[], null];
    }
    public function getExtraHeaders(): array
    {
        return ['Accept' => ['application/json']];
    }
    /**
     * {@inheritdoc}
     *
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\PostEditNoteBadRequestException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\BadResponseException
     *
     * @return array
     */
    protected function transformResponseBody(\Symfony\Contracts\HttpClient\ResponseInterface $response, \Symfony\Component\Serializer\SerializerInterface $serializer, ?string $contentType = null)
    {
        $status = $response->getStatusCode();
        $body = $response->getContent(false);
        if ($contentType !== null && (200 === $status && stripos(strtolower($contentType), 'application/json') !== false)) {
            try {
                $decodedBody = json_decode($body, false, 512, JSON_THROW_ON_ERROR);
                return $decodedBody;
            } catch (\JsonException $jsonException) {
                throw new \Jane\Component\JsonSchemaRuntime\Exception\MalformedJsonException('Malformed JSON response body.', 0, $jsonException);
            }
        }
        if ($contentType !== null && (400 === $status && stripos(strtolower($contentType), 'application/json') !== false)) {
            throw new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\PostEditNoteBadRequestException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ProblemDetailsInvalidRequestSyntax', 'json'), $response);
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