<?php declare(strict_types=1);
/**
 * If you want to add improvements, please create a fork in our GitHub:
 * https://github.com/myparcelnl
 *
 * @author      Reindert Vetter <reindert@myparcel.nl>
 * @copyright   2010-2020 MyParcel
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
 * @link        https://github.com/myparcelnl/sdk
 * @since       File available since Release v1.1.7
 */

namespace MyParcelNL\Sdk\src\Services;

use InvalidArgumentException;
use MyParcelNL\Sdk\src\Exception\MissingFieldException;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\MyParcelCustomsItem;
use MyParcelNL\Sdk\src\Support\Arr;

class ConsignmentEncode
{
    /**
     * @var array
     */
    private $consignmentEncoded = [];

    /**
     * @var AbstractConsignment[]
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
     * @throws \Exception
     */
    public function apiEncode(): array
    {
        $this->encodeBase()
             ->encodeStreet();

        $this->consignmentEncoded = self::encodeExtraOptions(
            $this->consignmentEncoded,
            Arr::first($this->consignments)
        );
        $this->encodeDeliveryType()
             ->encodePickup()
             ->encodePhysicalProperties()
             ->encodeCdCountry()
             ->encodeMultiCollo();

        return $this->consignmentEncoded;
    }

    /**
     * @param array                                                     $consignmentEncoded
     * @param \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment $consignment
     *
     * @return array
     */
    public static function encodeExtraOptions(array $consignmentEncoded, AbstractConsignment $consignment): array
    {
        $consignmentEncoded = array_merge_recursive(
            $consignmentEncoded,
            [
                'options' => [
                    'package_type'      => $consignment->getPackageType(AbstractConsignment::DEFAULT_PACKAGE_TYPE),
                    'label_description' => $consignment->getLabelDescription(),
                    'only_recipient'    => (int) $consignment->isOnlyRecipient(),
                    'signature'         => (int) $consignment->isSignature(),
                    'return'            => (int) $consignment->isReturn(),
                ],
            ]
        );

        if ($consignment->isEuCountry()) {
            $consignmentEncoded['options']['large_format'] = (int) $consignment->isLargeFormat();
        }

        if ($consignment->getCountry() == AbstractConsignment::CC_NL && $consignment->hasAgeCheck()) {
            $consignmentEncoded['options']['age_check']      = 1;
            $consignmentEncoded['options']['only_recipient'] = 1;
            $consignmentEncoded['options']['signature']      = 1;
        } elseif ($consignment->hasAgeCheck()) {
            throw new InvalidArgumentException('The age check is not possible with an EU shipment or world shipment');
        }

        if ($consignment->getDeliveryDate()) {
            $consignmentEncoded['options']['delivery_date'] = $consignment->getDeliveryDate();
        }

        if ($consignment->getInsurance() > 1) {
            $consignmentEncoded['options']['insurance'] = [
                'amount'   => (int) $consignment->getInsurance() * 100,
                'currency' => 'EUR',
            ];
        }

        return $consignmentEncoded;
    }

    /**
     * @return self
     */
    private function encodeDeliveryType(): self
    {
        /** @var AbstractConsignment $consignment */
        $consignment = Arr::first($this->consignments);
        $this->consignmentEncoded['options']['delivery_type'] = $consignment->getDeliveryType();

        return $this;
    }

    /**
     * @return self
     */
    private function encodeBase(): self
    {
        /** @var AbstractConsignment $consignment */
        $consignment = Arr::first($this->consignments);

        $this->consignmentEncoded = [
            'recipient' => [
                'cc'          => $consignment->getCountry(),
                'person'      => $consignment->getPerson(),
                'postal_code' => $consignment->getPostalCode(),
                'city'        => (string) $consignment->getCity(),
                'email'       => (string) $consignment->getEmail(),
                'phone'       => (string) $consignment->getPhone(),
            ],
            'carrier'   => $consignment->getCarrierId(),
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
        $consignment              = Arr::first($this->consignments);
        $this->consignmentEncoded = $consignment->encodeStreet($this->consignmentEncoded);

        return $this;
    }

    /**
     * Set pickup address
     * @return $this
     */
    private function encodePickup()
    {
        /** @var AbstractConsignment $consignment */
        $consignment = Arr::first($this->consignments);
        if (
            $consignment->getPickupPostalCode() !== null &&
            $consignment->getPickupStreet() !== null &&
            $consignment->getPickupCity() !== null &&
            $consignment->getPickupNumber() !== null &&
            $consignment->getPickupLocationName() !== null
        ) {
            $this->consignmentEncoded['pickup'] = [
                'cc'                => $consignment->getPickupCountry(),
                'postal_code'       => $consignment->getPickupPostalCode(),
                'street'            => $consignment->getPickupStreet(),
                'city'              => $consignment->getPickupCity(),
                'number'            => $consignment->getPickupNumber(),
                'location_name'     => $consignment->getPickupLocationName(),
                'location_code'     => $consignment->getPickupLocationCode(),
                'retail_network_id' => $consignment->getRetailNetworkId(),
            ];
        }

        $this->consignmentEncoded['general_settings']['save_recipient_address']     = $this->normalizeAutoSaveRecipientAddress($consignment);
        $this->consignmentEncoded['general_settings']['disable_auto_detect_pickup'] = $this->normalizeAutoDetectPickup($consignment);

        return $this;
    }

    /**
     * @return $this
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    private function encodePhysicalProperties()
    {
        $consignment = Arr::first($this->consignments);
        if (empty($consignment->getPhysicalProperties()) && $consignment->getPackageType() != AbstractConsignment::PACKAGE_TYPE_DIGITAL_STAMP) {
            return $this;
        }
        if ($consignment->getPackageType() == AbstractConsignment::PACKAGE_TYPE_DIGITAL_STAMP && ! isset($consignment->getPhysicalProperties()['weight'])) {
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
        /**
         * @var \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment $consignment
         */
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

        $customsDeclaration = [
            'customs_declaration' => [
                'contents' => $consignment->getContents() ?? 1,
                'weight'   => $consignment->getTotalWeight(),
                'items'    => $items,
                'invoice'  => $consignment->getInvoice() ?? '',
            ],
            'physical_properties' => $consignment->getPhysicalProperties(),
        ];

        $this->consignmentEncoded = Arr::arrayMergeRecursiveDistinct(
            $this->consignmentEncoded,
            $customsDeclaration
        );

        return $this;
    }

    /**
     * Encode product for the request
     *
     * @return array
     * @var string              $currency
     * @var MyParcelCustomsItem $customsItem
     */
    private function encodeCdCountryItem($customsItem, $currency = 'EUR')
    {
        $item = [
            'description'    => $customsItem->getDescription(),
            'amount'         => $customsItem->getAmount(),
            'weight'         => $customsItem->getWeight(),
            'classification' => $customsItem->getClassification(),
            'country'        => $customsItem->getCountry(),
            'item_value'     => [
                'amount'   => $customsItem->getItemValue(),
                'currency' => $currency,
            ],
        ];

        return $item;
    }

    /**
     * @param AbstractConsignment $consignment
     *
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    private function validateCdConsignment(AbstractConsignment $consignment)
    {
        if (empty($consignment->getItems())) {
            throw new MissingFieldException('Product data must be set for international MyParcel shipments. Use addItem().');
        }

        if ($consignment->getPackageType() !== AbstractConsignment::PACKAGE_TYPE_PACKAGE && $consignment->getPackageType() !== AbstractConsignment::PACKAGE_TYPE_LETTER) {
            throw new MissingFieldException('For international shipments, package_type must be 1 (normal package) or 3 (letter).');
        }

        if (empty($consignment->getInvoice())) {
            throw new MissingFieldException('Invoice id is required for international shipments. Use setInvoice().');
        }
    }

    /**
     * @return ConsignmentEncode
     * @throws \Exception
     */
    private function encodeMultiCollo()
    {
        /** @var AbstractConsignment $first */
        $first = Arr::first($this->consignments);
        if (count($this->consignments) > 1 && ! $first->isPartOfMultiCollo()) {
            throw new \Exception("Can not encode multi collo with this consignment.");
        }

        $secondaryShipments = $this->consignments;
        Arr::forget($secondaryShipments, 0);
        foreach ($secondaryShipments as $secondaryShipment) {
            $this->consignmentEncoded['secondary_shipments'][] = (object) ['reference_identifier' => $secondaryShipment->getReferenceId()];
        }

        return $this;
    }

    /**
     * @param \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment $consignment
     *
     * @return int
     */
    private function normalizeAutoDetectPickup(AbstractConsignment $consignment): int
    {
        return $consignment->isAutoDetectPickup() ? 0 : 1;
    }

    /**
     * @param \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment $consignment
     *
     * @return int
     */
    private function normalizeAutoSaveRecipientAddress(AbstractConsignment $consignment): int
    {
        return (int) $consignment->isSaveRecipientAddress();
    }
}
