<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Services;

use Exception;
use InvalidArgumentException;
use MyParcelNL\Sdk\src\Exception\MissingFieldException;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\MyParcelCustomsItem;
use MyParcelNL\Sdk\src\Support\Arr;
use MyParcelNL\Sdk\src\Support\Collection;

class ConsignmentEncode
{
    private const CURRENCY_EUR = 'EUR';

    /**
     * @var array
     */
    private $consignmentEncoded = [];

    /**
     * @var \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment[]
     */
    private $consignments;

    /**
     * @param \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment[] $consignments
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
                'options' => [
                    'package_type'      => $consignment->getPackageType(AbstractConsignment::DEFAULT_PACKAGE_TYPE),
                    'label_description' => $consignment->getLabelDescription(),
                    'only_recipient'    => (int) $consignment->isOnlyRecipient(),
                    'signature'         => (int) $consignment->isSignature(),
                    'return'            => (int) $consignment->isReturn(),
                ],
            ]
        );

        if ($consignment->isToEuCountry()) {
            $consignmentEncoded['options']['large_format'] = (int) $consignment->isLargeFormat();
        }

        if (AbstractConsignment::CC_NL === $consignment->getCountry() && $consignment->hasAgeCheck()) {
            $consignmentEncoded['options']['age_check']      = 1;
            $consignmentEncoded['options']['only_recipient'] = $consignment->canHaveShipmentOption('only_recipient') ? 1 : 0;
            $consignmentEncoded['options']['signature']      = $consignment->canHaveShipmentOption('signature') ? 1 : 0;
        } elseif ($consignment->hasAgeCheck()) {
            throw new InvalidArgumentException('The age check is not possible with an EU shipment or world shipment');
        }

        if ($consignment->getDeliveryDate()) {
            $consignmentEncoded['options']['delivery_date'] = $consignment->getDeliveryDate();
        }

        if ($consignment->getInsurance() > 1) {
            $consignmentEncoded['options']['insurance'] = [
                'amount'   => (int) $consignment->getInsurance() * 100,
                'currency' => self::CURRENCY_EUR,
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
                'region'      => (string) $consignment->getRegion(),
                'email'       => $consignment->getEmail(),
                'phone'       => (string) $consignment->getPhone(),
            ],
            'carrier'   => $consignment->getCarrierId(),
        ];

        if ($consignment->getReferenceIdentifier()) {
            $this->consignmentEncoded['reference_identifier'] = $consignment->getReferenceIdentifier();
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
            null !== $consignment->getPickupPostalCode()
            && null !== $consignment->getPickupStreet()
            && null !== $consignment->getPickupCity()
            && null !== $consignment->getPickupNumber()
            && null !== $consignment->getPickupLocationName()
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
        $consignment    = Arr::first($this->consignments);
        $isDigitalStamp = AbstractConsignment::PACKAGE_TYPE_DIGITAL_STAMP === $consignment->getPackageType();

        if (! $isDigitalStamp && empty($consignment->getPhysicalProperties())) {
            return $this;
        }

        if ($isDigitalStamp && ! isset($consignment->getPhysicalProperties()['weight'])) {
            throw new MissingFieldException('Weight in physical properties must be set for digital stamp shipments.');
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

        $encodedItems = array_map(static function (MyParcelCustomsItem $item) {
            return $item->encode(self::CURRENCY_EUR);
        }, $consignment->getItems());

        if (empty($consignment->getPhysicalProperties())) {
            $consignment->setPhysicalProperties(['weight' => $consignment->getTotalWeight()]);
        }

        $customsDeclaration = [
            'customs_declaration' => [
                'contents' => $consignment->getContents() ?? 1,
                'weight'   => $consignment->getTotalWeight(),
                'items'    => $encodedItems,
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
     * @param AbstractConsignment $consignment
     *
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    private function validateCdConsignment(AbstractConsignment $consignment): void
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
    private function encodeMultiCollo(): self
    {
        /** @var AbstractConsignment $first */
        $first = Arr::first($this->consignments);
        if (count($this->consignments) > 1 && ! $first->isPartOfMultiCollo()) {
            throw new Exception('Can not encode multi collo with this consignment.');
        }

        $secondaryShipments = $this->consignments;
        Arr::forget($secondaryShipments, 0);
        foreach ($secondaryShipments as $secondaryShipment) {
            $this->consignmentEncoded['secondary_shipments'][] = (object) ['reference_identifier' => $secondaryShipment->getReferenceIdentifier()];
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
