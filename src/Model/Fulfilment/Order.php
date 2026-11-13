<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Fulfilment;

use DateTime;
use MyParcelNL\Sdk\Concerns\HasApiKey;
use MyParcelNL\Sdk\Model\Recipient;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesPriceEuroCurrency;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemOptions as ShipmentOptionsModel;
use MyParcelNL\Sdk\Client\Jane\CoreApiModels;
use MyParcelNL\Sdk\Services\Mapping\ApiMapperService;
use MyParcelNL\Sdk\Support\Collection;

class Order extends AbstractOrder
{
    /*
     * Allows setting an API key for each order, so you can create multiple
     * orders for different shops at the same time. This way you won't have to provide a shop ID.
     */
    use HasApiKey;

    /**
     * @param  array $data
     *
     * @throws \Exception
     */
    public function __construct(array $data = [])
    {
        $shipment = $data['shipment'] ?? [];

        $this->uuid                          = $data['uuid'] ?? null;
        $this->external_identifier           = $data['external_identifier'] ?? null;
        $this->fulfilment_partner_identifier = $data['fulfilment_partner_identifier'] ?? null;
        $this->language                      = $data['language'] ?? null;
        $this->order_date                    = new DateTime($data['order_date'] ?? 'now');
        $this->order_shipments               = $data['order_shipments'] ?? [];
        $this->status                        = $data['status'] ?? null;
        $this->type                          = $data['type'] ?? null;

        $this->carrier_id       = isset($shipment['carrier_id'])
            ? (int) $shipment['carrier_id']
            : (isset($shipment['carrier']) ? (int) $shipment['carrier'] : null);
        $this->recipient        = new Recipient($shipment['recipient'] ?? []);
        $this->invoice_address  = new Recipient($data['invoice_address'] ?? []);
        $this->order_lines      = (new Collection($data['order_lines'] ?? []))->mapInto(OrderLine::class);
        $this->delivery_options = self::deliveryOptionsFromApi($shipment);
    }

    /**
     * Read the shipment options of an Order v1 response into the generated model.
     *
     * Order v1 nests the options under `shipment_options`, uses `date` for `delivery_date`, and can
     * send the insurance as an amount in euros, such as `2.5`, where the model holds cents.
     *
     * @param array<string, mixed> $shipment The `shipment` of the response.
     */
    private static function deliveryOptionsFromApi(array $shipment): ShipmentOptionsModel
    {
        $options = $shipment['shipment_options'] ?? $shipment['options'] ?? [];
        $flag    = static fn (mixed $value): ?int => null === $value ? null : (int) (bool) $value;

        $insurance = $options['insurance'] ?? null;

        if (is_numeric($insurance)) {
            $insurance = ['amount' => (int) round((float) $insurance * 100), 'currency' => RefTypesPriceEuroCurrency::Eur->value];
        }

        return CoreApiModels::toModel(array_filter([
            'delivery_date'     => $options['delivery_date'] ?? $options['date'] ?? null,
            'delivery_type'     => self::typeId($options['delivery_type'] ?? null, ApiMapperService::forDeliveryType()),
            'package_type'      => self::typeId($options['package_type'] ?? null, ApiMapperService::forPackageType()),
            'signature'         => $flag($options['signature'] ?? null),
            'collect'           => $flag($options['collect'] ?? null),
            'receipt_code'      => $flag($options['receipt_code'] ?? null),
            'only_recipient'    => $flag($options['only_recipient'] ?? null),
            'age_check'         => $flag($options['age_check'] ?? null),
            'large_format'      => $flag($options['large_format'] ?? null),
            'return'            => $flag($options['return'] ?? null),
            'priority_delivery' => $flag($options['priority_delivery'] ?? null),
            'label_description' => $options['label_description'] ?? null,
            'insurance'         => is_array($insurance) || is_object($insurance) ? $insurance : null,
        ], static fn (mixed $value): bool => null !== $value), ShipmentOptionsModel::class);
    }

    /**
     * Turn a delivery type or package type of an order into the id that the model holds.
     *
     * The Order API can send the id, such as `1`, or the v2 name, such as `'PACKAGE'` or `'STANDARD_DELIVERY'`.
     *
     * @param mixed            $value  The value of the order, or null when the order has none.
     * @param ApiMapperService $mapper The mapper of the domain, such as `ApiMapperService::forPackageType()`.
     *
     * @return int|null Null for a name that the mapper does not know.
     */
    private static function typeId(mixed $value, ApiMapperService $mapper): ?int
    {
        if (null === $value || is_int($value)) {
            return $value;
        }

        if (is_numeric($value)) {
            return (int) $value;
        }

        // A type that the API added after this SDK version has no id. It must not stop the read of
        // the other orders, so it gives null and not an exception.
        return $mapper->idFromV2Name((string) $value);
    }
}
