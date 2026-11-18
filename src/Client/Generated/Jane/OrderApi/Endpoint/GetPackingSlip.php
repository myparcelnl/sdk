<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Endpoint;

class GetPackingSlip extends \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\Client\BaseEndpoint implements \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\Client\Endpoint
{
    protected $accept;
    /**
     * Get packing slips for one or more orders. When packageId is provided, exactly one orderId must be supplied and the packageIds must belong to that order.
     * @param array{
     *    "shipmentLabelsLayout"?: string, //How shipment labels are included alongside the packing slips in the resulting PDF. `INTERLEAVED` outputs each packing slip immediately followed by its shipment label. When the parameter is omitted, no shipment labels are included and only packing slips are rendered.
     *    "orderId": array, //The orderId of the order to include in the packing slips. A maximum of 50 orders can be included in the request. When packageId is provided, exactly one orderId is allowed.
     *    "packageId"?: array, //The packageId of the package to include in the packing slips. The packageIds must belong to the single orderId in the request and a maximum of 20 packageIds can be included.
     * } $queryParameters
     * @param array{
     *    "Accept": string, //Use `application/pdf` to download the packing slip. Use `application/pdf+print; printer-group-id=<uuid>` to send the packing slip directly to a printer group.
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
        return '/packing-slips';
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
        $optionsResolver->setDefined(['shipmentLabelsLayout', 'orderId', 'packageId']);
        $optionsResolver->setRequired(['orderId']);
        $optionsResolver->setDefaults([]);
        $optionsResolver->addAllowedTypes('shipmentLabelsLayout', ['string']);
        $optionsResolver->addAllowedTypes('orderId', ['array']);
        $optionsResolver->addAllowedTypes('packageId', ['array']);
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
        return ['orderId' => ['style' => 'form', 'explode' => true], 'packageId' => ['style' => 'form', 'explode' => true]];
    }
    /**
     * {@inheritdoc}
     *
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\GetPackingSlipBadRequestException
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
            throw new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception\GetPackingSlipBadRequestException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ProblemDetailsInvalidRequestSyntax', 'json'), $response);
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