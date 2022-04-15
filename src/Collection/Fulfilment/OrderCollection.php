<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Collection\Fulfilment;

use DateTime;
use MyParcelNL\Sdk\src\Adapter\DeliveryOptions\AbstractDeliveryOptionsAdapter;
use MyParcelNL\Sdk\src\Concerns\HasApiKey;
use MyParcelNL\Sdk\src\Concerns\HasCountry;
use MyParcelNL\Sdk\src\Concerns\HasUserAgent;
use MyParcelNL\Sdk\src\Model\Consignment\DropOffPoint;
use MyParcelNL\Sdk\src\Model\Fulfilment\AbstractOrder;
use MyParcelNL\Sdk\src\Model\Fulfilment\Order;
use MyParcelNL\Sdk\src\Model\MyParcelRequest;
use MyParcelNL\Sdk\src\Model\RequestBody;
use MyParcelNL\Sdk\src\Support\Arr;
use MyParcelNL\Sdk\src\Support\Collection;

/**
 * @property \MyParcelNL\Sdk\src\Model\Fulfilment\Order[] $items
 */
class OrderCollection extends Collection
{
    use HasUserAgent;
    use HasApiKey;
    use HasCountry;

    /**
     * @param  string $apiKey
     * @param  array  $parameters
     *
     * @return self
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
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
     * @return self
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     */
    public function save(): self
    {
        $requestBody = new RequestBody('orders', $this->createRequestBody());
        $request     = (new MyParcelRequest())
            ->setUserAgents($this->getUserAgent())
            ->setRequestParameters(
            $this->ensureHasApiKey(),
            $requestBody
            )
            ->sendRequest('POST', MyParcelRequest::REQUEST_TYPE_ORDERS);

        return self::createCollectionFromResponse($request);
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
                        'physical_properties' => ['weight' => $order->getWeight()],
                    ],
                ];
            }
        )->toArrayWithoutNull();
    }

    /**
     * @param \MyParcelNL\Sdk\src\Adapter\DeliveryOptions\AbstractDeliveryOptionsAdapter $deliveryOptions
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
     * @param  \MyParcelNL\Sdk\src\Model\Consignment\DropOffPoint $dropOffPoint
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
     * @param  \MyParcelNL\Sdk\src\Model\MyParcelRequest $request
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
