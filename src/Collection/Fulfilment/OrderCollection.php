<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Collection\Fulfilment;

use DateTime;
use MyParcelNL\Sdk\Concerns\HasApiKey;
use MyParcelNL\Sdk\Concerns\HasCountry;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesPriceEuroCurrency;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemOptions as ShipmentOptionsModel;
use MyParcelNL\Sdk\Client\Jane\CoreApiModels;
use MyParcelNL\Sdk\Model\Fulfilment\AbstractOrder;
use MyParcelNL\Sdk\Model\Fulfilment\Order;
use MyParcelNL\Sdk\Model\MyParcelRequest;
use MyParcelNL\Sdk\Model\RequestBody;
use MyParcelNL\Sdk\Support\Arr;
use MyParcelNL\Sdk\Support\Collection;

/**
 * @internal Legacy Order v1 (fulfilment) collection.
 *
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
     * @param  array<string, string> $headers sent with each request, such as `x-dmp-*` API feature flags
     *
     * @return self
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     * @throws \Exception
     */
    public function save(array $headers = []): self
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
        foreach ($grouped as $key => $orders) {
            $requestBody = new RequestBody('orders', $orders->createRequestBody());
            $request     = (new MyParcelRequest())
                ->setUserAgents($this->getUserAgent())
                ->setRequestParameters($key, $requestBody, $headers)
                ->sendRequest('POST', MyParcelRequest::REQUEST_TYPE_ORDERS);

            $orders = self::createCollectionFromResponse($request);
            $orders->each(
                static function (Order $order) use ($key) {
                    $order->setApiKey($key);
                }
            );

            $collections[] = $orders->items;
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
                $options = $order->getDeliveryOptions();

                return [
                    'external_identifier'           => $order->getExternalIdentifier(),
                    'fulfilment_partner_identifier' => $order->getFulfilmentPartnerIdentifier(),
                    'order_date'                    => $order->getOrderDateString(AbstractOrder::DATE_FORMAT_FULL),
                    'invoice_address'               => $order->getInvoiceAddress()->toArrayWithoutNull(),
                    'order_lines'                   => $order->getOrderLines()->toArrayWithoutNull(),
                    'shipment'                      => [
                        'carrier'             => $order->getCarrierId(),
                        'recipient'           => $order->getRecipient()->toArrayWithoutNull(),
                        'options'             => $this->getShipmentOptions($options),
                        'pickup'              => $order->getPickupLocation() ? $order->getPickupLocation()->toArrayWithoutNull() : null,
                        'customs_declaration' => $order->getCustomsDeclaration() ? $order->getCustomsDeclaration()->toArrayWithoutNull() : null,
                        'physical_properties' => $order->getPhysicalProperties()->toArray(),
                    ],
                ];
            }
        )->toArrayWithoutNull();
    }

    /**
     * Build the `shipment.options` of the Order v1 request from the generated model.
     *
     * @return array<string, mixed>
     * @throws \Exception
     */
    private function getShipmentOptions(ShipmentOptionsModel $options): array
    {
        // The generated normalizer gives the API field names, and the value of an enum case, such
        // as `1` for `RefTypesIntBoolean::True`.
        $fields       = CoreApiModels::toArray($options);
        $deliveryDate = $fields['delivery_date'] ?? null;

        if ($deliveryDate) {
            $deliveryDate = (new DateTime($deliveryDate))->format(AbstractOrder::DATE_FORMAT_FULL);
        }

        $result = [
            'package_type'      => $fields['package_type'] ?? null,
            'delivery_type'     => $fields['delivery_type'] ?? null,
            'delivery_date'     => $deliveryDate ?: null,
            'signature'         => $fields['signature'] ?? 0,
            'collect'           => $fields['collect'] ?? 0,
            'receipt_code'      => $fields['receipt_code'] ?? 0,
            'only_recipient'    => $fields['only_recipient'] ?? 0,
            'age_check'         => $fields['age_check'] ?? 0,
            'large_format'      => $fields['large_format'] ?? 0,
            'return'            => $fields['return'] ?? 0,
            'priority_delivery' => $fields['priority_delivery'] ?? 0,
            'label_description' => (string) ($fields['label_description'] ?? ''),
        ];

        if (isset($fields['insurance'])) {
            $result['insurance'] = [
                'amount'   => (int) ($fields['insurance']['amount'] ?? 0),
                'currency' => $fields['insurance']['currency'] ?? RefTypesPriceEuroCurrency::Eur->value,
            ];
        }

        return $result;
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
