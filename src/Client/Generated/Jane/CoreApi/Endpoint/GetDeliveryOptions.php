<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint;

class GetDeliveryOptions extends \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\BaseEndpoint implements \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\Endpoint
{
    protected $accept;
    /**
     * Get the delivery options for a given location and carrier.
     *
     * If none of the optional parameters are specified then the following
     * default will be used: If a request is made for the delivery options
     * between Friday after the default cutoff_time (15:30) and Monday before
     * the default cutoff_time (15:30) then Tuesday will be shown as the next
     * possible delivery date.
     *
     * @param array{
     *    "cc"?: string, //The country code for which to fetch the delivery options.
     *    "postal_code"?: string, //The postal code for which to fetch the resources.
     *    "number"?: string, //The street number for which to fetch the resources.
     *    "city"?: string, //Only available for carriers Bpost and DPD. This can be used to narrow the search results for locations outside NL.
     *    "street"?: string, //This can be used to narrow the search results for locations outside NL.
     *    "platform"?: string, //The platform where you want the data from.
     *    "shop_id"?: mixed,
     *    "carrier"?: string,
     *    "delivery_date"?: string, //The date on which the package has to be delivered.
     *    "delivery_time"?: string, //The time on which a package has to be delivered.
     *                              //> __Note__: This is only an indication of time the package will be
     *                              //>           delivered on the selected date.
     *    "cutoff_time"?: string, //This option allows the **Merchant** to indicate the latest cutoff time before which a consumer order will still be picked, packed and dispatched on the same/first set drop-off day, taking into account the drop-off delay. Default time is 15:30. For example, if cutoff time is 15:30, Monday is a delivery day and there's no delivery delay; all orders placed Monday before 15:30 will be dropped of at PostNL on that same Monday in time for the Monday collection.
     *    "dropoff_days"?: string, //This options allows the **Merchant** to set the days she normally goes to PostNL to hand in her parcels. By default Saturday and Sunday are excluded.
     *    "monday_delivery"?: mixed, //Monday delivery is only possible when the package is delivered before
     *                               //15.00 on Saturday at the designated PostNL locations.
     *                               //> __Note__: To activate Monday delivery, value 6 must be given with
     *                               //> [dropoff_days], value 1 must be given by [monday_delivery]. And on
     *                               //> Saturday the [cutoff_time] must be before 15:00 (14:30 recommended)
     *                               //> so that Monday will be shown.
     *    "dropoff_delay"?: int, //This options allows the **Merchant** to set the number of days it takes them to pick, pack and hand in their parcels at the carrier when ordered before the cutoff time. The default value is 0.
     *    "deliverydays_window"?: int, //This options allows the Merchant to set the number of days into the future for which they want to show their consumers delivery options. For example, if set to 3 in their check-out, a consumer ordering on Monday will see possible delivery options for Tuesday, Wednesday and Thursday (provided there is no drop-off delay, it's before the cutoff time, and they go to PostNL on Mondays).
     *    "exclude_delivery_type"?: string, //Exclude shipments with a specific delivery type. This parameter can be used multiple times to exclude multiple delivery types.
     *    "exclude_parcel_lockers"?: mixed, //This option allows to filter out pickup locations that are parcel lockers.
     *    "latitude"?: int, //This provides the ability to search locations through the coordinates. If only latitude is provided without longitude, it will be ignored.
     *    "longitude"?: int, //This provides the ability to search locations through the coordinates. If only longitude is provided without latitude, it will be ignored.
     *    "package_type"?: string, //Package type to get the delivery options for. When omitted a
     *                             //carrier-specific default is used.
     *    "weight"?: int, //Weight in grams. Used to determine which package types and delivery
     *                    //options are available. Defaults to a carrier-specific weight when omitted.
     *    "delivery_type"?: string, //Delivery type to get the delivery options for.
     *    "same_day_delivery"?: mixed, //Include same day delivery options. Only available for carriers that
     *                                 //support it.
     *    "saturday_delivery"?: mixed, //Include Saturday delivery options. Only available for carriers that
     *                                 //support it.
     *    "include"?: string, //Semicolon-separated list of related resources to include in the response.
     *                        //Currently only `price` is supported.
     *    "shipment_id"?: int, //Identifier of an existing shipment to base the delivery options on.
     *                         //Used by carriers such as UPS and bol.com.
     * } $queryParameters
     * @param array $accept Accept content header application/json;version=2.0|application/json
     */
    public function __construct(array $queryParameters = [], array $accept = [])
    {
        $this->queryParameters = $queryParameters;
        $this->accept = $accept;
    }
    use \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\EndpointTrait;
    public function getMethod(): string
    {
        return 'GET';
    }
    public function getUri(): string
    {
        return '/delivery_options';
    }
    public function getBody(\Symfony\Component\Serializer\SerializerInterface $serializer): array
    {
        return [[], null];
    }
    public function getExtraHeaders(): array
    {
        if (empty($this->accept)) {
            return ['Accept' => ['application/json;version=2.0', 'application/json']];
        }
        return $this->accept;
    }
    protected function getQueryOptionsResolver(): \Symfony\Component\OptionsResolver\OptionsResolver
    {
        $optionsResolver = parent::getQueryOptionsResolver();
        $optionsResolver->setDefined(['cc', 'postal_code', 'number', 'city', 'street', 'platform', 'shop_id', 'carrier', 'delivery_date', 'delivery_time', 'cutoff_time', 'dropoff_days', 'monday_delivery', 'dropoff_delay', 'deliverydays_window', 'exclude_delivery_type', 'exclude_parcel_lockers', 'latitude', 'longitude', 'package_type', 'weight', 'delivery_type', 'same_day_delivery', 'saturday_delivery', 'include', 'shipment_id']);
        $optionsResolver->setRequired([]);
        $optionsResolver->setDefaults([]);
        $optionsResolver->addAllowedTypes('cc', ['string', 'null']);
        $optionsResolver->addAllowedTypes('postal_code', ['string', 'null']);
        $optionsResolver->addAllowedTypes('number', ['string', 'null']);
        $optionsResolver->addAllowedTypes('city', ['string', 'null']);
        $optionsResolver->addAllowedTypes('street', ['string', 'null']);
        $optionsResolver->addAllowedTypes('platform', ['string', 'null']);
        $optionsResolver->addAllowedTypes('carrier', ['string', 'null']);
        $optionsResolver->addAllowedTypes('delivery_date', ['string', 'null']);
        $optionsResolver->addAllowedTypes('delivery_time', ['string', 'null']);
        $optionsResolver->addAllowedTypes('cutoff_time', ['string', 'null']);
        $optionsResolver->addAllowedTypes('dropoff_days', ['string', 'null']);
        $optionsResolver->addAllowedTypes('dropoff_delay', ['int', 'null']);
        $optionsResolver->addAllowedTypes('deliverydays_window', ['int', 'null']);
        $optionsResolver->addAllowedTypes('exclude_delivery_type', ['string']);
        $optionsResolver->addAllowedTypes('latitude', ['int', 'null']);
        $optionsResolver->addAllowedTypes('longitude', ['int', 'null']);
        $optionsResolver->addAllowedTypes('package_type', ['string']);
        $optionsResolver->addAllowedTypes('weight', ['int', 'null']);
        $optionsResolver->addAllowedTypes('delivery_type', ['string']);
        $optionsResolver->addAllowedTypes('include', ['string', 'null']);
        $optionsResolver->addAllowedTypes('shipment_id', ['int']);
        return $optionsResolver;
    }
    /**
     * {@inheritdoc}
     *
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetDeliveryOptionsClientErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetDeliveryOptionsServerErrorException
     * @throws \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\BadResponseException
     *
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDeliveryOptionsV2|\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDeliveryOptionsV1
     */
    protected function transformResponseBody(\Symfony\Contracts\HttpClient\ResponseInterface $response, \Symfony\Component\Serializer\SerializerInterface $serializer, ?string $contentType = null)
    {
        $status = $response->getStatusCode();
        $body = $response->getContent(false);
        if (200 === $status) {
            if (stripos(strtolower((string) $contentType), 'application/json') !== false) {
                return $serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDeliveryOptionsV2', 'json');
            }
            if (stripos(strtolower((string) $contentType), 'application/json') !== false) {
                return $serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDeliveryOptionsV1', 'json');
            }
        }
        if ($contentType !== null && ($status >= 400 && $status <= 499 && stripos(strtolower($contentType), 'application/json') !== false)) {
            throw new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetDeliveryOptionsClientErrorException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesUserError', 'json'), $response);
        }
        if ($contentType !== null && ($status >= 500 && $status <= 599 && stripos(strtolower($contentType), 'application/json') !== false)) {
            throw new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetDeliveryOptionsServerErrorException($serializer->deserialize($body, 'MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesSystemError', 'json'), $response);
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