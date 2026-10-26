<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint;

class GetShipmentsLabels extends \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\BaseEndpoint implements \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\Endpoint
{
    protected $ids;
    protected $accept;
    /**
     * #### Get Shipment labels
     * You can specify label format and positions of labels on the first page with the
     * __format__ and __positions__ query parameters. The __positions__ query only works when you specify the A4 format
     * and is only applied on the first page with labels. Accounts with __Post-payment__ payment methods can fetch
     * multiple labels in one call. For accounts with __Pre-payment__ payment method an `HTTP 402 Payment Required`
     * with a PaymentInstructions object is returned if the label has not been paid for yet.
     *
     * ##### Multi-collo shipments
     * When a label for a multi collo shipment is requested, labels for all shipments parts of the multi collo shipment
     * will be generated. Each shipment within a multi collo shipment MUST be labeled with a specific label containing
     * a unique barcode.
     *
     * @param mixed $ids One or more shipment IDs. Separate multiple shipment IDs using `;`.
     * @param array{
     *    "format"?: mixed, //The paper size of the PDF as specified in ISO216. Currently, A4 and A6 are supported. When A4 is chosen you
     *                      //can specify the label positions. When requesting the label for a shipment that contains a custom form, you can
     *                      //only request an A4 format.
     *    "positions"?: mixed, //The positions of the label on an A4 sheet. A position is identified by a digit from `1` to `4`. You can specify
     *                         //up to four positions separated by semicolons (`;`).
     *                         //
     *                         //__Note__: This parameter only works when you specify the `A4` __format__ and only applies to the first page
     *                         //when requesting multiple pages worth of labels. Subsequent pages use the default positioning `1;2;3;4`.
     *    "collect_date"?: string,
     *    "delivery_options_identifier"?: string,
     * } $queryParameters
     * @param array{
     *    "User-Agent"?: string, //To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.
     * } $headerParameters
     * @param array $accept Accept content header application/*|application/json|application/vnd.shipment_label_link+json|application/vnd.shipment_label_link+json+print|application/json+print|application/pdf+print|application/vnd.shipment_label+json+print|application/vnd.shipment_label+pdf+print|application/vnd.shipment_label+zpl+print|application/vnd.zpl+print|*\/*
     */
    public function __construct($ids, array $queryParameters = [], array $headerParameters = [], array $accept = [])
    {
        $this->ids = $ids;
        $this->queryParameters = $queryParameters;
        $this->headerParameters = $headerParameters;
        $this->accept = $accept;
    }
    use \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\EndpointTrait;
    public function getMethod(): string
    {
        return 'GET';
    }
    public function getUri(): string
    {
        return str_replace(['{ids}'], [rawurlencode((string) $this->ids)], '/shipment_labels/{ids}');
    }
    public function getBody(\Symfony\Component\Serializer\SerializerInterface $serializer): array
    {
        return [[], null];
    }
    public function getExtraHeaders(): array
    {
        if (empty($this->accept)) {
            return ['Accept' => ['application/*', 'application/json', 'application/vnd.shipment_label_link+json', 'application/vnd.shipment_label_link+json+print', 'application/json+print', 'application/pdf+print', 'application/vnd.shipment_label+json+print', 'application/vnd.shipment_label+pdf+print', 'application/vnd.shipment_label+zpl+print', 'application/vnd.zpl+print', '*/*']];
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
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetShipmentsLabelsPaymentRequiredException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetShipmentsLabelsNotFoundException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetShipmentsLabelsClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetShipmentsLabelsServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\BadResponseException
     *
     * @return null|\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesDownloadLink
     */
    protected function transformResponseBody(\Symfony\Contracts\HttpClient\ResponseInterface $response, \Symfony\Component\Serializer\SerializerInterface $serializer, ?string $contentType = null)
    {
        $status = $response->getStatusCode();
        $body = $response->getContent(false);
        if (204 === $status) {
            return null;
        }
        if (400 === $status) {
        }
        if (401 === $status) {
        }
        if ($contentType !== null && (402 === $status && stripos(strtolower($contentType), 'application/vnd.shipment_label_link+json') !== false)) {
            throw new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetShipmentsLabelsPaymentRequiredException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesBulkShipmentLabelsPaymentRequired', 'json'), $response);
        }
        if ($contentType !== null && (404 === $status && stripos(strtolower($contentType), 'application/json') !== false)) {
            throw new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetShipmentsLabelsNotFoundException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesUserErrorNotFound', 'json'), $response);
        }
        if (406 === $status) {
        }
        if ($status >= 200 && $status <= 299) {
            if (stripos(strtolower((string) $contentType), 'application/json') !== false) {
                return $serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesDownloadLink', 'json');
            }
            if (stripos(strtolower((string) $contentType), 'application/vnd.shipment_label_link+json') !== false) {
                return $serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesDownloadLink', 'json');
            }
        }
        if ($contentType !== null && ($status >= 400 && $status <= 499 && stripos(strtolower($contentType), 'application/json') !== false)) {
            throw new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetShipmentsLabelsClientErrorException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesUserError', 'json'), $response);
        }
        if ($contentType !== null && ($status >= 500 && $status <= 599 && stripos(strtolower($contentType), 'application/json') !== false)) {
            throw new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetShipmentsLabelsServerErrorException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesSystemError', 'json'), $response);
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
        return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesDownloadLink::class;
    }
}