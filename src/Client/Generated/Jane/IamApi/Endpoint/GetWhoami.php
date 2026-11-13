<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Endpoint;

class GetWhoami extends \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Runtime\Client\BaseEndpoint implements \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Runtime\Client\Endpoint
{
    protected $accept;
    /**
     * Who am I?
     * @param array $accept Accept content header application/json|application/problem+json
     */
    public function __construct(array $accept = [])
    {
        $this->accept = $accept;
    }
    use \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Runtime\Client\EndpointTrait;
    public function getMethod(): string
    {
        return 'GET';
    }
    public function getUri(): string
    {
        return '/whoami';
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
    /**
     * {@inheritdoc}
     *
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Exception\GetWhoamiUnauthorizedException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Exception\GetWhoamiInternalServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\PrincipalShop|\MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\PrincipalUser
     */
    protected function transformResponseBody(\Symfony\Contracts\HttpClient\ResponseInterface $response, \Symfony\Component\Serializer\SerializerInterface $serializer, ?string $contentType = null)
    {
        $status = $response->getStatusCode();
        $body = $response->getContent(false);
        if ($contentType !== null && (200 === $status && stripos(strtolower($contentType), 'application/json') !== false)) {
            try {
                $decodedBody = json_decode($body, false, 512, JSON_THROW_ON_ERROR);
                if (is_object($decodedBody)) {
                    switch ($decodedBody->type ?? null) {
                        case 'SHOP':
                            return $serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\PrincipalShop', 'json');
                        case 'USER':
                            return $serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\PrincipalUser', 'json');
                    }
                }
                return $decodedBody;
            } catch (\JsonException $jsonException) {
                throw new \Jane\Component\JsonSchemaRuntime\Exception\MalformedJsonException('Malformed JSON response body.', 0, $jsonException);
            }
        }
        if ($contentType !== null && (401 === $status && stripos(strtolower($contentType), 'application/json') !== false)) {
            throw new \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Exception\GetWhoamiUnauthorizedException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\UnauthorizedResponse', 'json'), $response);
        }
        if ($contentType !== null && (500 === $status && stripos(strtolower($contentType), 'application/problem+json') !== false)) {
            throw new \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Exception\GetWhoamiInternalServerErrorException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\ProblemDetailsInternalServerError', 'json'), $response);
        }
        throw new \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Exception\BadResponseException($status, $body, $response);
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