<?php
declare(strict_types=1);

namespace MyParcelNL\Sdk\Adapter;

use MyParcelNL\Sdk\Helper\Utils;
use MyParcelNL\Sdk\Model\Consignment\AbstractConsignment;

class ConsignmentAdapter
{
    /**
     * @var AbstractConsignment
     */
    private $consignment;

    private $data;

    /**
     * ConsignmentDecode constructor.
     *
     * @param array                                                     $data
     * @param \MyParcelNL\Sdk\Model\Consignment\AbstractConsignment $consignment
     *
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     */
    public function __construct(array $data, AbstractConsignment $consignment)
    {
        $this->data        = $data;
        $this->consignment = $consignment;

        $this
            ->setBaseOptions()
            ->setPhysicalProperties()
            ->setExtraOptions()
            ->setRecipient()
            ->setPickup();
    }

    /**
     * @return AbstractConsignment
     */
    public function getConsignment(): AbstractConsignment
    {
        return $this->consignment;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    private function setBaseOptions(): self
    {
        Utils::fillObject($this->consignment, [
            'consignment_id'       => $this->data['id'] ?? null,
            'shop_id'              => $this->data['shop_id'] ?? null,
            'reference_identifier' => $this->data['reference_identifier'] ?? null,
            'barcode'              => $this->data['barcode'] ?? null,
            'status'               => $this->data['status'] ?? null,
        ]);

        return $this;
    }

    /**
     * @return self
     * @throws \Exception
     */
    private function setExtraOptions(): self
    {
        $options = $this->data['options'];

        if (array_key_exists('insurance', $options)) {
            $options['insurance']['amount'] /= 100;
        }

        /**
         * Note: when adding shipment options to the SDK, add them here as well. The order matters.
         */
        Utils::fillObject($this->consignment, [
            'package_type'  => $options['package_type'] ?? AbstractConsignment::PACKAGE_TYPE_PACKAGE,
            'delivery_date' => $options['delivery_date'] ?? null,
            'delivery_type' => $options['delivery_type'] ?? AbstractConsignment::DEFAULT_DELIVERY_TYPE,

            AbstractConsignment::SHIPMENT_OPTION_AGE_CHECK         => (bool) ($options['age_check'] ?? false),
            'extra_assurance'                                      => (bool) ($options['extra_assurance'] ?? false),
            AbstractConsignment::SHIPMENT_OPTION_HIDE_SENDER       => (bool) ($options['hide_sender'] ?? false),
            AbstractConsignment::SHIPMENT_OPTION_LARGE_FORMAT      => (bool) ($options['large_format'] ?? false),
            AbstractConsignment::SHIPMENT_OPTION_ONLY_RECIPIENT    => (bool) ($options['only_recipient'] ?? false),
            AbstractConsignment::SHIPMENT_OPTION_RETURN            => (bool) ($options['return'] ?? false),
            'same_day_delivery'                                    => (bool) ($options['same_day_delivery'] ?? false),
            AbstractConsignment::SHIPMENT_OPTION_SIGNATURE         => (bool) ($options['signature'] ?? false),
            AbstractConsignment::SHIPMENT_OPTION_COLLECT           => (bool) ($options['collect'] ?? false),
            AbstractConsignment::SHIPMENT_OPTION_RECEIPT_CODE      => (bool) ($options['receipt_code'] ?? false),
            AbstractConsignment::SHIPMENT_OPTION_PRIORITY_DELIVERY => (bool) ($options['priority_delivery'] ?? false),
            AbstractConsignment::SHIPMENT_OPTION_FRESH_FOOD        => (bool) ($options['fresh_food'] ?? false),
            AbstractConsignment::SHIPMENT_OPTION_FROZEN            => (bool) ($options['frozen'] ?? false),
            'insurance'                                            => $options['insurance']['amount'] ?? 0,
            'label_description'                                    => $options['label_description'] ?? null,
        ]);

        return $this;
    }

    /**
     * @return self
     */
    private function setPhysicalProperties(): self
    {
        if (isset($this->data['physical_properties'])) {
            $this->consignment->setPhysicalProperties($this->data['physical_properties']);
        }

        return $this;
    }

    /**
     * @return self
     */
    private function setPickup(): self
    {
        $pickup = $this->data['pickup'] ?? [];

        if (! empty($pickup)) {
            Utils::fillObject($this->consignment, [
                'pickup_city'          => $pickup['city'] ?? null,
                'pickup_country'       => $pickup['cc'] ?? null,
                'pickup_location_code' => $pickup['location_code'] ?? null,
                'pickup_location_name' => $pickup['location_name'] ?? null,
                'retail_network_id'    => $pickup['retail_network_id'] ?? '',
                'pickup_number'        => $pickup['number'] ?? null,
                'pickup_postal_code'   => $pickup['postal_code'] ?? null,
                'pickup_street'        => $pickup['street'] ?? null,
            ]);
        }

        return $this;
    }

    /**
     * @return self
     */
    private function setRecipient(): self
    {
        $recipient = $this->data['recipient'];

        Utils::fillObject($this->consignment, [
            'country'       => $recipient['cc'] ?? null,
            'city'          => $recipient['city'] ?? null,
            'company'       => $recipient['company'] ?? null,
            'email'         => $recipient['email'] ?? null,
            'number'        => $recipient['number'] ?? null,
            'number_suffix' => $recipient['number_suffix'] ?? null,
            'person'        => $recipient['person'] ?? null,
            'phone'         => $recipient['phone'] ?? null,
            'postal_code'   => $recipient['postal_code'] ?? null,
            'street'        => $recipient['street'] ?? null,
        ]);

        return $this;
    }
}
