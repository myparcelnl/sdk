<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItemGeneralSettings implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItemGeneralSettingsSaveRecipientAddress|int
     */
    public ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItemGeneralSettingsSaveRecipientAddress|int $saveRecipientAddress;
    public function definedProperties(): array
    {
        return ['saveRecipientAddress' => 'save_recipient_address'];
    }
}