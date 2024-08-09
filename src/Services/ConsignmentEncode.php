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
use MyParcelNL\Sdk\src\Model\Carrier\CarrierDHLEuroplus;
use MyParcelNL\Sdk\src\Model\Carrier\CarrierDHLForYou;
use MyParcelNL\Sdk\src\Model\Carrier\CarrierDHLParcelConnect;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\MyParcelCustomsItem;
use MyParcelNL\Sdk\src\Support\Arr;
use MyParcelNL\Sdk\src\Support\Collection;
use MyParcelNL\Sdk\src\Support\Helpers;

class ConsignmentEncode
{
    public const DEFAULT_CURRENCY           = 'EUR';
    private const MAX_INSURANCE_PACKETS_ROW = 5000;

    /**
     * @var array
     */
    private $consignmentEncoded = [];

    /**
     * @var AbstractConsignment[]
     */
    private $consignments;

    /**
     * @param AbstractConsignment[] $consignments
     */
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
            ->encodeMultiCollo()
            ->encodeDropOffPoint();

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
                'options' => Helpers::toArrayWithoutNull([
                    'package_type'           => $consignment->getPackageType(AbstractConsignment::DEFAULT_PACKAGE_TYPE),
                    'label_description'      => $consignment->getLabelDescription(),
                    'only_recipient'         => Helpers::intOrNull($consignment->isOnlyRecipient()),
                    'signature'              => Helpers::intOrNull($consignment->isSignature()),
                    'return'                 => Helpers::intOrNull($consignment->isReturn()),
                    'same_day_delivery'      => Helpers::intOrNull($consignment->isSameDayDelivery()),
                    'hide_sender'            => Helpers::intOrNull($consignment->hasHideSender()),
                    'extra_assurance'        => Helpers::intOrNull($consignment->hasExtraAssurance()),
                ]),
            ]
        );

        if ($consignment->isToEuCountry()) {
            $consignmentEncoded['options']['large_format'] = (int) $consignment->isLargeFormat();
        }

        if ($consignment->getCountry() === AbstractConsignment::CC_NL && $consignment->hasAgeCheck()) {
            $consignmentEncoded['options']['age_check'] = 1;
        } elseif ($consignment->hasAgeCheck()) {
            throw new InvalidArgumentException('The age check is not possible with an EU shipment or world shipment');
        }

        if ($consignment->hasExtraAssurance()) {
            $consignmentEncoded['options']['hide_sender'] = 0;
        }

        if (in_array($consignment->getCarrierName(), [CarrierDHLEuroplus::NAME, CarrierDHLParcelConnect::NAME])) {
            $consignmentEncoded['options']['signature'] = 1;
        }

        if ($consignment->getDeliveryDate()) {
            $consignmentEncoded['options']['delivery_date'] = $consignment->getDeliveryDate();
        }

        if ($consignment->getInsurance() > 1) {
            $consignmentEncoded['options']['insurance'] = [
                'amount'   => (int) $consignment->getInsurance() * 100,
                'currency' => self::DEFAULT_CURRENCY,
            ];
        }

        if (AbstractConsignment::PACKAGE_TYPE_PACKAGE_SMALL === $consignment->getPackageType()) {
            $consignmentEncoded['options']['large_format'] = 0;

            if (AbstractConsignment::CC_NL !== $consignment->getCountry()
                && self::MAX_INSURANCE_PACKETS_ROW < $consignment->getInsurance() * 100) {
                $consignmentEncoded['options']['insurance']['amount'] = self::MAX_INSURANCE_PACKETS_ROW;
            }

            if (in_array($consignment->getCountry(), [AbstractConsignment::CC_NL, AbstractConsignment::CC_BE])) {
                $consignmentEncoded['options']['tracked'] = 0;
            } else {
                $consignmentEncoded['options']['tracked'] = 1;
            }
        }

        foreach ($consignment->getMandatoryShipmentOptions() as $option) {
            $key   = "options.$option";
            $value = Arr::get($consignmentEncoded, $key);

            if (null === $value || 0 === $value) {
                Arr::set($consignmentEncoded, $key, 1);
            }
        }

        foreach (AbstractConsignment::SHIPMENT_OPTIONS_TO_CHECK as $option) {
            $key   = "options.$option";
            $value = Arr::get($consignmentEncoded, $key);

            if (1 === $value && ! $consignment->canHaveShipmentOption($option)) {
                Arr::forget($consignmentEncoded, $key);
            }
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
                'region'      => (string) $consignment->getRegion(),
                'state'       => (string) $consignment->getState(),
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
     * @return self
     */
    private function encodeStreet(): self
    {
        $consignment              = Arr::first($this->consignments);
        $this->consignmentEncoded = $consignment->encodeStreet($this->consignmentEncoded);

        return $this;
    }

    /**
     * Set pickup address
     * @return self
     */
    private function encodePickup(): self
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
     * @return self
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    private function encodePhysicalProperties(): self
    {
        $consignment = Arr::first($this->consignments);
        if (empty($consignment->getPhysicalProperties()) && $consignment->getPackageType() != AbstractConsignment::PACKAGE_TYPE_DIGITAL_STAMP) {
            return $this;
        }

        if ($consignment->getPackageType() == AbstractConsignment::PACKAGE_TYPE_DIGITAL_STAMP && ! isset($consignment->getPhysicalProperties()['weight'])) {
            throw new MissingFieldException('Weight in physical properties must be set for digital stamp shipments.');
        }

        if (CarrierDHLForYou::NAME === $consignment->getCarrier()->getName()) {
            $consignment->setPhysicalProperties([
                'weight' => $consignment->getTotalWeight(),
                'volume' => 1,
            ]);
        }

        $this->consignmentEncoded['physical_properties'] = $consignment->getPhysicalProperties();

        return $this;
    }

    /**
     * @return self
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    private function encodeCdCountry(): self
    {
        /**
         * @var \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment $consignment
         */
        $consignment = Arr::first($this->consignments);

        if ($consignment->isToEuCountry()) {
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
     * @param  MyParcelCustomsItem $customsItem
     *
     * @return array
     */
    private function encodeCdCountryItem(MyParcelCustomsItem $customsItem): array
    {
        return [
            'description'    => $customsItem->getDescription(),
            'amount'         => $customsItem->getAmount(),
            'weight'         => $customsItem->getWeight(),
            'classification' => $customsItem->getClassification(),
            'country'        => $customsItem->getCountry(),
            'item_value'     => $customsItem->getItemValue(),
        ];
    }

    /**
     * @param AbstractConsignment $consignment
     *
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    private function validateCdConsignment(AbstractConsignment $consignment): void
    {
        if (empty($consignment->getItems())) {
            throw new MissingFieldException('Product data must be set for international MyParcel shipments. Use addItem().');
        }

        if (! in_array(
            $consignment->getPackageType(),
            [
                AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                AbstractConsignment::PACKAGE_TYPE_LETTER,
                AbstractConsignment::PACKAGE_TYPE_PACKAGE_SMALL,
            ],
            true
        )) {
            throw new MissingFieldException(
                'For international shipments, package_type must be 1 (normal package), 3 (letter) or 6 (small package).'
            );
        }

        if (empty($consignment->getInvoice())) {
            throw new MissingFieldException('Invoice id is required for international shipments. Use setInvoice().');
        }
    }

    /**
     * @return ConsignmentEncode
     * @throws \Exception
     */
    private function encodeMultiCollo(): self
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

    /**
     * @return self
     */
    private function encodeDropOffPoint(): self
    {
        $consignment  = Arr::first($this->consignments);
        $dropOffPoint = $consignment->getDropOffPoint();

        if (! $dropOffPoint) {
            return $this;
        }

        $options = new Collection([
            'box_number'        => $dropOffPoint->getBoxNumber(),
            'cc'                => $dropOffPoint->getCc(),
            'city'              => $dropOffPoint->getCity(),
            'location_code'     => $dropOffPoint->getLocationCode(),
            'location_name'     => $dropOffPoint->getLocationName(),
            'number'            => $dropOffPoint->getNumber(),
            'number_suffix'     => $dropOffPoint->getNumberSuffix(),
            'postal_code'       => $dropOffPoint->getPostalCode(),
            'region'            => $dropOffPoint->getRegion(),
            'retail_network_id' => $dropOffPoint->getRetailNetworkId(),
            'state'             => $dropOffPoint->getState(),
            'street'            => $dropOffPoint->getStreet(),
        ]);

        $this->consignmentEncoded['drop_off_point'] = $options->toArrayWithoutNull();
        return $this;
    }
}
