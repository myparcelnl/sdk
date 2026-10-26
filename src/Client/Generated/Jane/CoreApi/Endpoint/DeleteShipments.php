<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint;

class DeleteShipments extends \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\BaseEndpoint implements \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\Endpoint
{
    protected $ids;
    protected $accept;
    /**
     * This operation can be used to delete shipments for which a label has not yet been created.
     * However, outside of housekeeping, this is not really necessary since Shipments not handed over to the distributor will not be billed by MyParcel; thus unused shipments which are not deleted have no financial consequences.
     * @param mixed $ids One or more shipment IDs. Separate multiple shipment IDs using `;`.
     * @param array{
     *    "User-Agent"?: string, //To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
     * } $headerParameters
     * @param array $accept Accept content header application/json|application/problem+json
     */
    public function __construct($ids, array $headerParameters = [], array $accept = [])
    {
        $this->ids = $ids;
        $this->headerParameters = $headerParameters;
        $this->accept = $accept;
    }
    use \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\EndpointTrait;
    public function getMethod(): string
    {
        return 'DELETE';
    }
    public function getUri(): string
    {
        return str_replace(['{ids}'], [rawurlencode((string) $this->ids)], '/shipments/{ids}');
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
        $optionsResolver->setDefined(['User-Agent']);
        $optionsResolver->setRequired([]);
        $optionsResolver->setDefaults([]);
        $optionsResolver->addAllowedTypes('User-Agent', ['string']);
        return $optionsResolver;
    }
    /**
     * {@inheritdoc}
     *
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\DeleteShipmentsClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\DeleteShipmentsServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\BadResponseException
     *
     * @return null
     */
    protected function transformResponseBody(\Symfony\Contracts\HttpClient\ResponseInterface $response, \Symfony\Component\Serializer\SerializerInterface $serializer, ?string $contentType = null)
    {
        $status = $response->getStatusCode();
        $body = $response->getContent(false);
        if (204 === $status) {
            return null;
        }
        if ($status >= 400 && $status <= 499) {
            if (stripos(strtolower((string) $contentType), 'application/json') !== false) {
                throw new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\DeleteShipmentsClientErrorException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesUserError', 'json'), $response);
            }
            if (stripos(strtolower((string) $contentType), 'application/problem+json') !== false) {
                throw new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\DeleteShipmentsClientErrorException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesProblem', 'json'), $response);
            }
        }
        if ($status >= 500 && $status <= 599) {
            if (stripos(strtolower((string) $contentType), 'application/json') !== false) {
                throw new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\DeleteShipmentsServerErrorException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesSystemError', 'json'), $response);
            }
            if (stripos(strtolower((string) $contentType), 'application/problem+json') !== false) {
                throw new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\DeleteShipmentsServerErrorException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesProblem', 'json'), $response);
            }
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