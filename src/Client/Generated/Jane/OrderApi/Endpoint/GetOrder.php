<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Endpoint;

class GetOrder extends \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\Client\BaseEndpoint implements \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\Client\Endpoint
{
    /**
     * Get orders.
     * @param array{
     *    "aggregations"?: array, //The statuses to retrieve counts for.
     *    "filter"?: array,
     *    "limit"?: int, //The maximum number of orders to retrieve.
     *    "pageToken"?: string, //The page token to retrieve the next page of orders.
     *    "query"?: string, //The following operators are supported:
     *                      //'+ signifies AND operation'
     *                      //'| signifies OR operation'
     *                      //'- negates a single token'
     *                      //'" wraps a number of tokens to signify a phrase for searching'
     *                      //'* at the end of a term signifies a prefix query'
     *                      //'( and ) signify precedence'
     *                      //'~N after a word signifies edit distance (fuzziness)'
     *                      //'~N after a phrase signifies slop amount'
     *                      //To use one of these characters literally, escape it with a preceding backslash (\).
     *    "sort"?: array, //When no `query` is provided the default sort is by `orderedAt` descending. When a `query` is provided the default sort is by relevance.
     * } $queryParameters
     */
    public function __construct(array $queryParameters = [])
    {
        $this->queryParameters = $queryParameters;
    }
    use \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\Client\EndpointTrait;
    public function getMethod(): string
    {
        return 'GET';
    }
    public function getUri(): string
    {
        return '/orders';
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
        $optionsResolver->setDefined(['aggregations', 'filter', 'limit', 'pageToken', 'query', 'sort']);
        $optionsResolver->setRequired([]);
        $optionsResolver->setDefaults(['limit' => 50]);
        $optionsResolver->addAllowedTypes('aggregations', ['array']);
        $optionsResolver->addAllowedTypes('filter', ['array']);
        $optionsResolver->addAllowedTypes('limit', ['int', 'null']);
        $optionsResolver->addAllowedTypes('pageToken', ['string']);
        $optionsResolver->addAllowedTypes('query', ['string']);
        $optionsResolver->addAllowedTypes('sort', ['array']);
        return $optionsResolver;
    }
    protected function getQueryStyles(): array
    {
        return ['aggregations' => ['style' => 'form', 'explode' => true], 'filter' => ['style' => 'deepObject', 'explode' => false], 'sort' => ['style' => 'deepObject', 'explode' => false]];
    }
    /**
     * {@inheritdoc}
     *
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\GetOrderBadRequestException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrdersGetResponse200
     */
    protected function transformResponseBody(\Symfony\Contracts\HttpClient\ResponseInterface $response, \Symfony\Component\Serializer\SerializerInterface $serializer, ?string $contentType = null)
    {
        $status = $response->getStatusCode();
        $body = $response->getContent(false);
        if ($contentType !== null && (200 === $status && stripos(strtolower($contentType), 'application/json') !== false)) {
            return $serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrdersGetResponse200', 'json');
        }
        if ($contentType !== null && (400 === $status && stripos(strtolower($contentType), 'application/json') !== false)) {
            throw new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\GetOrderBadRequestException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ProblemDetailsInvalidRequestSyntax', 'json'), $response);
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
        return \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrdersGetResponse200::class;
    }
}