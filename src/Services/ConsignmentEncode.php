<?php
/**
 * If you want to add improvements, please create a fork in our GitHub:
 * https://github.com/myparcelnl
 *
 * @author      Reindert Vetter <reindert@myparcel.nl>
 * @copyright   2010-2017 MyParcel
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
 * @link        https://github.com/myparcelnl/sdk
 * @since       File available since Release v1.1.7
 */

namespace MyParcelNL\Sdk\src\Services;

use MyParcelNL\Sdk\src\Model\MyParcelConsignment;
use MyParcelNL\Sdk\src\Model\MyParcelCustomsItem;

class ConsignmentEncode
{
    /**
     * @var array
     */
    private $consignmentEncoded = [];

    /**
     * @var MyParcelConsignment
     */
    private $consignment;

    public function __construct($consignment)
    {
        $this->consignment = $consignment;
    }

    /**
     * Encode all the data before sending it to MyParcel
     *
     * @return array
     * @throws \Exception
     */
    public function apiEncode()
    {
        $this->encodeBaseOptions()
                ->encodeStreet()
                ->encodeExtraOptions()
                ->encodeCdCountry();

        return $this->consignmentEncoded;
    }

    /**
     * @return self
     */
    private function encodeBaseOptions()
    {
        $consignment = $this->consignment;
        $packageType = $consignment->getPackageType();

        if ($packageType == null) {
            $packageType = MyParcelConsignment::DEFAULT_PACKAGE_TYPE;
        }

        $this->consignmentEncoded = [
            'recipient' => [
                'cc' => $consignment->getCountry(),
                'person' => $consignment->getPerson(),
                'postal_code' => $consignment->getPostalCode(),
                'city' => (string) $consignment->getCity(),
                'email' => (string) $consignment->getEmail(),
                'phone' => (string) $consignment->getPhone(),
            ],
            'options' => [
                'package_type' => $packageType,
                'label_description' => $consignment->getLabelDescription(),
            ],
            'carrier' => 1,
        ];

        if ($consignment->getReferenceId()) {
            $this->consignmentEncoded['reference_identifier'] = $consignment->getReferenceId();
        }

        if ($consignment->getCompany()) {
            $this->consignmentEncoded['recipient']['company'] = $consignment->getCompany();
        }

        return $this;
    }

    /**
     * @return $this
     */
    private function encodeStreet()
    {
        $consignment = $this->consignment;
        if ($consignment->getCountry() == MyParcelConsignment::CC_NL) {
            $this->consignmentEncoded = array_merge_recursive(
                $this->consignmentEncoded,
                [
                    'recipient' => [
                        'street' => $consignment->getStreet(true),
                        'street_additional_info' => $consignment->getStreetAdditionalInfo(),
                        'number' => $consignment->getNumber(),
                        'number_suffix' => $consignment->getNumberSuffix(),
                    ],
                ]
            );
        } else {
            $this->consignmentEncoded['recipient']['street'] = $consignment->getFullStreet(true);
            $this->consignmentEncoded['recipient']['street_additional_info'] = $consignment->getStreetAdditionalInfo();
        }

        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    private function encodeExtraOptions() {
        $consignment = $this->consignment;
        $hasOptions = $this->hasOptions();
        if ($hasOptions) {
            $this->consignmentEncoded = array_merge_recursive(
                $this->consignmentEncoded,
                [
                    'options' => [
                        'only_recipient' => $consignment->isOnlyRecipient() ? 1 : 0,
                        'signature' => $consignment->isSignature() ? 1 : 0,
                        'return' => $consignment->isReturn() ? 1 : 0,
                        'delivery_type' => $consignment->getDeliveryType(),
                    ],
                ]
            );
            $this
                ->encodePickup()
                ->encodeInsurance()
                ->encodePhysicalProperties();
        } else {
            $this->consignmentEncoded['options']['delivery_type'] = MyParcelConsignment::DEFAULT_DELIVERY_TYPE;
        }

        if ($consignment->isEuCountry()) {
            $this->consignmentEncoded['options']['large_format'] = $consignment->isLargeFormat() ? 1 : 0;
        }

        if ($consignment->getDeliveryDate()) {
            $this->consignmentEncoded['options']['delivery_date'] = $consignment->getDeliveryDate();
        }

        return $this;
    }

    /**
     * Set pickup address
     * @return $this
     */
    private function encodePickup()
    {
        $consignment = $this->consignment;
        if (
            $this->hasOptions() !== false &&
            $consignment->getPickupPostalCode() !== null &&
            $consignment->getPickupStreet() !== null &&
            $consignment->getPickupCity() !== null &&
            $consignment->getPickupNumber() !== null &&
            $consignment->getPickupLocationName() !== null
        ) {
            $this->consignmentEncoded['pickup'] = [
                'postal_code' => $consignment->getPickupPostalCode(),
                'street' => $consignment->getPickupStreet(),
                'city' => $consignment->getPickupCity(),
                'number' => $consignment->getPickupNumber(),
                'location_name' => $consignment->getPickupLocationName(),
                'location_code' => $consignment->getPickupLocationCode(),
                'retail_network_id' => $consignment->getPickupNetworkId(),
            ];
        }

        return $this;
    }

    /**
     * @return $this
     */
    private function encodeInsurance()
    {
        // Set insurance
        if ($this->consignment->getInsurance() > 1) {
            $this->consignmentEncoded['options']['insurance'] = [
                'amount' => (int) $this->consignment->getInsurance() * 100,
                'currency' => 'EUR',
            ];
        }

        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    private function encodePhysicalProperties()
    {
        $consignment = $this->consignment;
        if (empty($consignment->getPhysicalProperties()) && $consignment->getPackageType() != MyParcelConsignment::PACKAGE_TYPE_DIGITAL_STAMP) {
            return $this;
        }
        if ($consignment->getPackageType() == MyParcelConsignment::PACKAGE_TYPE_DIGITAL_STAMP && !isset($consignment->getPhysicalProperties()['weight'])) {
            throw new \Exception('Weight in physical properties must be set for digital stamp shipments.');
        }

        $this->consignmentEncoded['physical_properties'] = $consignment->getPhysicalProperties();
    }

    /**
     * @return $this
     * @throws \Exception
     */
    private function encodeCdCountry()
    {
        $consignment = $this->consignment;
        if ($consignment->isEuCountry()) {
            return $this;
        }

        if (empty($consignment->getItems())) {
            throw new \Exception('Product data must be set for international MyParcel shipments. Use addItem().');
        }

        if ($consignment->getPackageType() !== MyParcelConsignment::PACKAGE_TYPE_NORMAL) {
            throw new \Exception('For international shipments, package_type must be 1 (normal package).');
        }

        if (empty($consignment->getLabelDescription())) {
            throw new \Exception('Label description/invoice id is required for international shipments. Use getLabelDescription().');
        }

        $items = [];
        foreach ($consignment->getItems() as $item) {
            $items[] = $this->encodeCdCountryItem($item);
        }

        $this->consignmentEncoded = array_merge_recursive(
            $this->consignmentEncoded, [
                'customs_declaration' => [
                    'contents' => 1,
                    'weight' => $consignment->getTotalWeight(),
                    'items' => $items,
                    'invoice' => $consignment->getLabelDescription(),
                ],
                'physical_properties' => $consignment->getPhysicalProperties() + ['weight' => $consignment->getTotalWeight()],
            ]
        );

        return $this;
    }

    /**
     * Encode product for the request
     *
     * @var MyParcelCustomsItem $customsItem
     * @var string $currency
     * @return array
     */
    private function encodeCdCountryItem($customsItem, $currency = 'EUR')
    {
        $item = [
            'description' => $customsItem->getDescription(),
            'amount' => $customsItem->getAmount(),
            'weight' => $customsItem->getWeight(),
            'classification' => $customsItem->getClassification(),
            'country' => $customsItem->getCountry(),
            'item_value' =>
                [
                    'amount' => $customsItem->getItemValue(),
                    'currency' => $currency,
                ],
        ];

        return $item;
    }

    /**
     * @return bool
     */
    private function hasOptions()
    {
        if (in_array($this->consignment->getCountry(), [MyParcelConsignment::CC_NL, MyParcelConsignment::CC_BE])) {
            return true;
        }

        return false;
    }
}
