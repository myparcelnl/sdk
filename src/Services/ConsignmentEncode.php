<?php declare(strict_types=1);
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

use InvalidArgumentException;
use MyParcelNL\Sdk\src\Model\MyParcelConsignment;
use MyParcelNL\Sdk\src\Model\MyParcelCustomsItem;
use MyParcelNL\Sdk\src\Support\Arr;
use MyParcelNL\Sdk\src\Exception\MissingFieldException;

class ConsignmentEncode
{
    /**
     * @var array
     */
    private $consignmentEncoded = [];

    /**
     * @var MyParcelConsignment[] grouped by multi collo
     */
    private $consignments;

    public function __construct($consignments)
    {
        $this->consignments = $consignments;
    }

    /**
     * Encode all the data before sending it to MyParcel
     *
     * @return array
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function apiEncode()
    {
        $this->encodeBaseOptions()
             ->encodeStreet()
             ->encodeExtraOptions()
             ->encodeCdCountry()
             ->encodeMultiCollo();

        return $this->consignmentEncoded;
    }

    /**
     * @return self
     */
    private function encodeBaseOptions()
    {
        $consignment = Arr::first($this->consignments);
        $packageType = $consignment->getPackageType();

        if ($packageType == null) {
            $packageType = MyParcelConsignment::DEFAULT_PACKAGE_TYPE;
        }

        $this->consignmentEncoded = [
            'recipient' => [
                'cc'          => $consignment->getCountry(),
                'person'      => $consignment->getPerson(),
                'postal_code' => $consignment->getPostalCode(),
                'city'        => (string) $consignment->getCity(),
                'email'       => (string) $consignment->getEmail(),
                'phone'       => (string) $consignment->getPhone(),
            ],
            'options'   => [
                'package_type'      => $packageType,
                'label_description' => $consignment->getLabelDescription(),
            ],
            'carrier'   => 1,
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
        $consignment = Arr::first($this->consignments);
        if ($consignment->getCountry() == MyParcelConsignment::CC_NL) {
            $this->consignmentEncoded = array_merge_recursive(
                $this->consignmentEncoded,
                [
                    'recipient' => [
                        'street'                 => $consignment->getStreet(true),
                        'street_additional_info' => $consignment->getStreetAdditionalInfo(),
                        'number'                 => $consignment->getNumber(),
                        'number_suffix'          => $consignment->getNumberSuffix(),
                    ],
                ]
            );
        } else {
            $this->consignmentEncoded['recipient']['street']                 = $consignment->getFullStreet(true);
            $this->consignmentEncoded['recipient']['street_additional_info'] = $consignment->getStreetAdditionalInfo();
        }

        return $this;
    }

    /**
     * @return $this
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    private function encodeExtraOptions() {
        $consignment = Arr::first($this->consignments);
        $hasOptions = $this->hasOptions();
        if ($hasOptions) {
            $this->consignmentEncoded = array_merge_recursive(
                $this->consignmentEncoded,
                [
                    'options' => [
                        'only_recipient' => $consignment->isOnlyRecipient() ? 1 : 0,
                        'signature'      => $consignment->isSignature() ? 1 : 0,
                        'return'         => $consignment->isReturn() ? 1 : 0,
                        'delivery_type'  => $consignment->getDeliveryType(),
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

        if ($consignment->getCountry() == MyParcelConsignment::CC_NL && $consignment->hasAgeCheck()) {
            $this->consignmentEncoded['options']['age_check']      = 1;
            $this->consignmentEncoded['options']['only_recipient'] = 1;
            $this->consignmentEncoded['options']['signature']      = 1;
        } elseif ($consignment->hasAgeCheck()) {
            throw new InvalidArgumentException('The age check is not possible with an EU shipment or world shipment');
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
        $consignment = Arr::first($this->consignments);
        if (
            $this->hasOptions() !== false &&
            $consignment->getPickupPostalCode() !== null &&
            $consignment->getPickupStreet() !== null &&
            $consignment->getPickupCity() !== null &&
            $consignment->getPickupNumber() !== null &&
            $consignment->getPickupLocationName() !== null
        ) {
            $this->consignmentEncoded['pickup'] = [
                'postal_code'       => $consignment->getPickupPostalCode(),
                'street'            => $consignment->getPickupStreet(),
                'city'              => $consignment->getPickupCity(),
                'number'            => $consignment->getPickupNumber(),
                'location_name'     => $consignment->getPickupLocationName(),
                'location_code'     => $consignment->getPickupLocationCode(),
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
        $consignment = Arr::first($this->consignments);

        // Set insurance
        if ($consignment->getInsurance() > 1) {
            $this->consignmentEncoded['options']['insurance'] = [
                'amount' => (int) $consignment->getInsurance() * 100,
                'currency' => 'EUR',
            ];
        }

        return $this;
    }

    /**
     * @return $this
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    private function encodePhysicalProperties()
    {
        $consignment = Arr::first($this->consignments);
        if (empty($consignment->getPhysicalProperties()) && $consignment->getPackageType() != MyParcelConsignment::PACKAGE_TYPE_DIGITAL_STAMP) {
            return $this;
        }
        if ($consignment->getPackageType() == MyParcelConsignment::PACKAGE_TYPE_DIGITAL_STAMP && ! isset($consignment->getPhysicalProperties()['weight'])) {
            throw new MissingFieldException('Weight in physical properties must be set for digital stamp shipments.');
        }

        $this->consignmentEncoded['physical_properties'] = $consignment->getPhysicalProperties();

        return $this;
    }

    /**
     * @return $this
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    private function encodeCdCountry()
    {
        $consignment = Arr::first($this->consignments);
        if ($consignment->isEuCountry()) {
            return $this;
        }

        $this->validateCdConsignment($consignment);

        $items = [];
        foreach ($consignment->getItems() as $item) {
            $items[] = $this->encodeCdCountryItem($item);
        }

        if (empty($consignment->getPhysicalProperties())) {
            $consignment->setPhysicalProperties(['weight' => $consignment->getTotalWeight()]);
        }

        $this->consignmentEncoded = array_merge_recursive(
            $this->consignmentEncoded, [
                'customs_declaration' => [
                    'contents' => 1,
                    'weight'   => $consignment->getTotalWeight(),
                    'items'    => $items,
                    'invoice'  => $consignment->getLabelDescription(),
                ],
                'physical_properties' => $consignment->getPhysicalProperties(),
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
            'description'    => $customsItem->getDescription(),
            'amount'         => $customsItem->getAmount(),
            'weight'         => $customsItem->getWeight(),
            'classification' => $customsItem->getClassification(),
            'country'        => $customsItem->getCountry(),
            'item_value'     =>
                [
                    'amount'   => $customsItem->getItemValue(),
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
        $consignment = Arr::first($this->consignments);

        if (in_array($consignment->getCountry(), [MyParcelConsignment::CC_NL, MyParcelConsignment::CC_BE])) {
            return true;
        }

        return false;
    }

    /**
     * @param MyParcelConsignment $consignment
     *
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    private function validateCdConsignment(MyParcelConsignment $consignment)
    {
        if (empty($consignment->getItems())) {
            throw new MissingFieldException('Product data must be set for international MyParcel shipments. Use addItem().');
        }

        if ($consignment->getPackageType() !== MyParcelConsignment::PACKAGE_TYPE_PACKAGE) {
            throw new MissingFieldException('For international shipments, package_type must be 1 (normal package).');
        }

        if (empty($consignment->getLabelDescription())) {
            throw new MissingFieldException('Label description/invoice id is required for international shipments. Use getLabelDescription().');
        }
    }

    /**
     * @return ConsignmentEncode
     * @throws \Exception
     */
    private function encodeMultiCollo()
    {
        /** @var MyParcelConsignment $first */
        $first = Arr::first($this->consignments);
        if (count($this->consignments) > 1 && ! $first->isPartOfMultiCollo()) {
            throw new \Exception("Can not encode multi collo with this consignment.");
        }

        $secondaryShipments = $this->consignments;
        Arr::forget($secondaryShipments, 0);
        foreach ($secondaryShipments as $secondaryShipment) {
            $this->consignmentEncoded['secondary_shipments'][] = (object)['reference_identifier' => $secondaryShipment->getReferenceId()];
        }

        return $this;
    }
}
