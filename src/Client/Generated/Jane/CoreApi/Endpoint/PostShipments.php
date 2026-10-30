<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint;

class PostShipments extends \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\BaseEndpoint implements \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\Endpoint
{
    protected $accept;
    /**
     * Add shipments allows you to create standard and related return shipments.
     * @param \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11|\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequest|\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostReturnShipmentsRequest|\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostUnrelatedReturnShipmentsRequest $requestBody
     * @param array{
     *    "format"?: mixed, //The paper size of the PDF as specified in ISO216. Currently, A4 and A6 are supported. When A4 is chosen you
     *                      //can specify the label positions. When requesting the label for a shipment that contains a custom form, you can
     *                      //only request an A4 format.
     *    "positions"?: mixed, //The positions of the label on an A4 sheet. A position is identified by a digit from `1` to `4`. You can specify
     *                         //up to four positions separated by semicolons (`;`) or commas (`,`).
     *                         //
     *                         //__Note__: This parameter only works when you specify the `A4` __format__ and only applies to the first page
     *                         //when requesting multiple pages worth of labels. Subsequent pages use the default positioning `1;2;3;4`.
     *    "collect_date"?: string,
     *    "delivery_options_identifier"?: string,
     * } $queryParameters
     * @param array{
     *    "User-Agent"?: string, //To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
     * } $headerParameters
     * @param array $accept Accept content header application/json;charset=utf-8;version=1.2|application/json|application/vnd.shipment_label+json|application/*|application/problem+json
     */
    public function __construct($requestBody, array $queryParameters = [], array $headerParameters = [], array $accept = [])
    {
        $this->body = $requestBody;
        $this->queryParameters = $queryParameters;
        $this->headerParameters = $headerParameters;
        $this->accept = $accept;
    }
    use \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\EndpointTrait;
    public function getMethod(): string
    {
        return 'POST';
    }
    public function getUri(): string
    {
        return '/shipments';
    }
    public function getBody(\Symfony\Component\Serializer\SerializerInterface $serializer): array
    {
        if ($this->body instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11) {
            return [['Content-Type' => ['application/vnd.shipment+json;version=1.1']], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\JsonPayload::encode($serializer, $this->body)];
        }
        if ($this->body instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequest) {
            return [['Content-Type' => ['application/vnd.shipment+json']], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\JsonPayload::encode($serializer, $this->body)];
        }
        if ($this->body instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostReturnShipmentsRequest) {
            return [['Content-Type' => ['application/vnd.return_shipment+json']], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\JsonPayload::encode($serializer, $this->body)];
        }
        if ($this->body instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostUnrelatedReturnShipmentsRequest) {
            return [['Content-Type' => ['application/vnd.unrelated_return_shipment+json']], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\JsonPayload::encode($serializer, $this->body)];
        }
        return [[], null];
    }
    public function getExtraHeaders(): array
    {
        if (empty($this->accept)) {
            return ['Accept' => ['application/json;charset=utf-8;version=1.2', 'application/json', 'application/vnd.shipment_label+json', 'application/*', 'application/problem+json']];
        }
        return $this->accept;
    }
    protected function getQueryOptionsResolver(): \Symfony\Component\OptionsResolver\OptionsResolver
    {
        $optionsResolver = parent::getQueryOptionsResolver();
        $optionsResolver->setDefined(['format', 'positions', 'collect_date', 'delivery_options_identifier']);
        $optionsResolver->setRequired([]);
        $optionsResolver->setDefaults([]);
        $optionsResolver->addAllowedTypes('collect_date', ['string', 'null']);
        $optionsResolver->addAllowedTypes('delivery_options_identifier', ['string', 'null']);
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
    /**
     * {@inheritdoc}
     *
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostShipmentsUnsupportedMediaTypeException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostShipmentsClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostShipmentsServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPostShipmentsV12|null|\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesShipmentLabels
     */
    protected function transformResponseBody(\Symfony\Contracts\HttpClient\ResponseInterface $response, \Symfony\Component\Serializer\SerializerInterface $serializer, ?string $contentType = null)
    {
        $status = $response->getStatusCode();
        $body = $response->getContent(false);
        if (200 === $status) {
            if (stripos(strtolower((string) $contentType), 'application/json') !== false) {
                return $serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPostShipmentsV12', 'json');
            }
            if (stripos(strtolower((string) $contentType), 'application/json') !== false) {
                try {
                    $decodedBody = json_decode($body, false, 512, JSON_THROW_ON_ERROR);
                    return $decodedBody;
                } catch (\JsonException $jsonException) {
                    throw new \Jane\Component\JsonSchemaRuntime\Exception\MalformedJsonException('Malformed JSON response body.', 0, $jsonException);
                }
            }
            if (stripos(strtolower((string) $contentType), 'application/vnd.shipment_label+json') !== false) {
                return $serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesShipmentLabels', 'json');
            }
        }
        if ($contentType !== null && (415 === $status && stripos(strtolower($contentType), 'application/problem+json') !== false)) {
            throw new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostShipmentsUnsupportedMediaTypeException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesProblem', 'json'), $response);
        }
        if ($status >= 400 && $status <= 499) {
            if (stripos(strtolower((string) $contentType), 'application/json') !== false) {
                throw new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostShipmentsClientErrorException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesUserError', 'json'), $response);
            }
            if (stripos(strtolower((string) $contentType), 'application/problem+json') !== false) {
                throw new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostShipmentsClientErrorException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesProblem', 'json'), $response);
            }
        }
        if ($status >= 500 && $status <= 599) {
            if (stripos(strtolower((string) $contentType), 'application/json') !== false) {
                throw new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostShipmentsServerErrorException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesSystemError', 'json'), $response);
            }
            if (stripos(strtolower((string) $contentType), 'application/problem+json') !== false) {
                throw new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostShipmentsServerErrorException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesProblem', 'json'), $response);
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