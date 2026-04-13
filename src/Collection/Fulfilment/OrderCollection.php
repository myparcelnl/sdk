<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Collection\Fulfilment;

use DateTime;
use MyParcelNL\Sdk\Concerns\HasApiKey;
use MyParcelNL\Sdk\Concerns\HasCountry;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Model\Fulfilment\AbstractOrder;
use MyParcelNL\Sdk\Model\Fulfilment\Order;
use MyParcelNL\Sdk\Model\Fulfilment\OrderShipmentOptions;
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
        foreach ($grouped as $key => $orders) {
            $requestBody = new RequestBody('orders', $orders->createRequestBody());
            $request     = (new MyParcelRequest())
                ->setUserAgents($this->getUserAgent())
                ->setRequestParameters($key, $requestBody)
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
                        'carrier'             => $options->getCarrierId(),
                        'recipient'           => $order->getRecipient()->toArrayWithoutNull(),
                        'options'             => $this->getShipmentOptions($options),
                        'pickup'              => $order->getPickupLocation() ? $order->getPickupLocation()->toArrayWithoutNull() : null,
                        'customs_declaration' => $order->getCustomsDeclaration(),
                        'physical_properties' => $order->getPhysicalProperties()->toArray(),
                    ],
                ];
            }
        )->toArrayWithoutNull();
    }

    /**
     * @param \MyParcelNL\Sdk\Model\Fulfilment\OrderShipmentOptions $options
     *
     * @return array
     * @throws \Exception
     */
    private function getShipmentOptions(OrderShipmentOptions $options): array
    {
        $deliveryDate = $options->getDate();

        if ($deliveryDate) {
            $date         = new DateTime($deliveryDate);
            $deliveryDate = $date->format(AbstractOrder::DATE_FORMAT_FULL);
        }

        $result = [
            'package_type'      => $options->getPackageTypeId(),
            'delivery_type'     => $options->getDeliveryTypeId(),
            'delivery_date'     => $deliveryDate ?: null,
            'signature'         => (int) $options->hasSignature(),
            'collect'           => (int) $options->hasCollect(),
            'receipt_code'      => (int) $options->hasReceiptCode(),
            'only_recipient'    => (int) $options->hasOnlyRecipient(),
            'age_check'         => (int) $options->hasAgeCheck(),
            'large_format'      => (int) $options->hasLargeFormat(),
            'return'            => (int) $options->isReturn(),
            'priority_delivery' => (int) $options->isPriorityDelivery(),
            'label_description' => (string) $options->getLabelDescription(),
        ];

        if ($options->getInsurance()) {
            $result['insurance'] = [
                'amount'   => (int) $options->getInsurance() * 100,
                'currency' => 'EUR',
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
