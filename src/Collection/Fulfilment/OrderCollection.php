<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Collection\Fulfilment;

use DateTime;
use MyParcelNL\Sdk\Adapter\DeliveryOptions\AbstractDeliveryOptionsAdapter;
use MyParcelNL\Sdk\Concerns\HasApiKey;
use MyParcelNL\Sdk\Concerns\HasCountry;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Model\Consignment\DropOffPoint;
use MyParcelNL\Sdk\Model\Fulfilment\AbstractOrder;
use MyParcelNL\Sdk\Model\Fulfilment\Order;
use MyParcelNL\Sdk\Model\MyParcelRequest;
use MyParcelNL\Sdk\Model\RequestBody;
use MyParcelNL\Sdk\Support\Arr;
use MyParcelNL\Sdk\Support\Collection;

/**
 * @property \MyParcelNL\Sdk\Model\Fulfilment\Order[] $items
 */
class OrderCollection extends Collection
{
    use HasUserAgent;
    use HasApiKey;
    use HasCountry;

    /**
     * @param string $apiKey
     * @param array  $parameters
     *
     * @return self
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     * @throws \Exception
     */
    public static function query(string $apiKey, array $parameters = []): self
    {
        $request = (new MyParcelRequest())
            ->setRequestParameters($apiKey)
            ->setQuery($parameters)
            ->sendRequest(
                'GET',
                MyParcelRequest::REQUEST_TYPE_ORDERS
            );

        return self::createCollectionFromResponse($request);
    }

    /**
     * This saves the order collection to the MyParcel API.
     * For this to work correctly you should set the correct api key on each fulfilment order in the collection.
     * For backwards compatibility it remains possible to set the api key on the collection itself, when set that
     * is the api key that will be used for all orders in the collection, to stay consistent with how it was.
     *
     * @return self
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     * @throws \Exception
     */
    public function save(): self
    {
        $collections = [];
        // for now we default to the common api key of the collection if set, but you should set it on each order
        if (null !== ($apiKey = $this->getApiKey())) {
            $grouped = $this->groupBy(
                function (Order $order) use ($apiKey) {
                    $order->setApiKey($apiKey);
                    return $apiKey;
                }
            );
        } else {
            $grouped = $this->groupBy(
                static function (Order $order) {
                    return $order->getApiKey();
                }
            );
        }

        /* @var OrderCollection $orders */
        foreach ($grouped as $orders) {
            $requestBody = new RequestBody('orders', $orders->createRequestBody());
            $request     = (new MyParcelRequest())
                ->setUserAgents($this->getUserAgent())
                ->setRequestParameters(
                    $orders->first()->getApiKey(),
                    $requestBody
                )
                ->sendRequest('POST', MyParcelRequest::REQUEST_TYPE_ORDERS);

            $collections[] = (self::createCollectionFromResponse($request))->items;
        }

        return new self(array_merge(...$collections));
    }

    /**
     * @return array[]
     * @throws \Exception
     */
    private function createRequestBody(): array
    {
        return $this->map(
            function (Order $order) {
                $order->validate();
                $deliveryOptions     = $order->getDeliveryOptions();
                $dropOffPoint        = $order->getDropOffPoint();
                $dropOffPointAsArray = $dropOffPoint ? $this->getDropOffPointAsArray($dropOffPoint) : null;

                return [
                    'external_identifier'           => $order->getExternalIdentifier(),
                    'fulfilment_partner_identifier' => $order->getFulfilmentPartnerIdentifier(),
                    'order_date'                    => $order->getOrderDateString(AbstractOrder::DATE_FORMAT_FULL),
                    'invoice_address'               => $order->getInvoiceAddress()->toArrayWithoutNull(),
                    'order_lines'                   => $order->getOrderLines()->toArrayWithoutNull(),
                    'shipment'                      => [
                        'carrier'             => $deliveryOptions->getCarrierId(),
                        'recipient'           => $order->getRecipient()->toArrayWithoutNull(),
                        'options'             => $this->getShipmentOptions($deliveryOptions),
                        'pickup'              => $order->getPickupLocation() ? $order->getPickupLocation()->toArrayWithoutNull() : null,
                        'drop_off_point'      => $dropOffPointAsArray,
                        'customs_declaration' => $order->getCustomsDeclaration(),
                        'physical_properties' => $order->getPhysicalProperties()->toArray(),
                    ],
                ];
            }
        )->toArrayWithoutNull();
    }

    /**
     * @param \MyParcelNL\Sdk\Adapter\DeliveryOptions\AbstractDeliveryOptionsAdapter $deliveryOptions
     *
     * @return array
     * @throws \Exception
     */
    private function getShipmentOptions(AbstractDeliveryOptionsAdapter $deliveryOptions): array
    {
        $deliveryDate = $deliveryOptions->getDate();

        if ($deliveryDate) {
            $date         = new DateTime($deliveryDate);
            $deliveryDate = $date->format(AbstractOrder::DATE_FORMAT_FULL);
        }

        $shipmentOptions = $deliveryOptions->getShipmentOptions();

        $options = [
            'package_type'      => $deliveryOptions->getPackageTypeId(),
            'delivery_type'     => $deliveryOptions->getDeliveryTypeId(),
            'delivery_date'     => $deliveryDate ?: null,
            'signature'         => (int) $shipmentOptions->hasSignature(),
            'collect'           => (int) $shipmentOptions->hasCollect(),
            'receipt_code'      => (int) $shipmentOptions->hasReceiptCode(),
            'only_recipient'    => (int) $shipmentOptions->hasOnlyRecipient(),
            'age_check'         => (int) $shipmentOptions->hasAgeCheck(),
            'large_format'      => (int) $shipmentOptions->hasLargeFormat(),
            'return'            => (int) $shipmentOptions->isReturn(),
            'label_description' => (string) $shipmentOptions->getLabelDescription(),
        ];

        if ($shipmentOptions->getInsurance()) {
            $options['insurance'] = [
                'amount'   => (int) $shipmentOptions->getInsurance() * 100,
                'currency' => 'EUR',
            ];
        }

        return $options;
    }

    /**
     * @param \MyParcelNL\Sdk\Model\Consignment\DropOffPoint $dropOffPoint
     *
     * @return array
     */
    private function getDropOffPointAsArray(DropOffPoint $dropOffPoint): array
    {
        return [
            'location_code' => $dropOffPoint->getLocationCode(),
            'location_name' => $dropOffPoint->getLocationName(),
            'postal_code'   => $dropOffPoint->getPostalCode(),
            'street'        => $dropOffPoint->getStreet(),
            'number'        => $dropOffPoint->getNumber(),
            'number_suffix' => $dropOffPoint->getNumberSuffix() ?? '',
            'city'          => $dropOffPoint->getCity(),
            'cc'            => $dropOffPoint->getCc(),
        ];
    }

    /**
     * @param \MyParcelNL\Sdk\Model\MyParcelRequest $request
     *
     * @return self
     */
    private static function createCollectionFromResponse(MyParcelRequest $request): self
    {
        $orders     = Arr::get($request->getResult(), 'data.orders');
        $collection = new self($orders);

        return $collection->mapInto(Order::class);
    }
}
