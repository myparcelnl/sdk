<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class RefCapabilitiesResponseCapabilityV2
{
    /**
     * Carrier string definition for capabilities endpoints (v2)
     *
     * @var RefCapabilitiesSharedCarrierV2|string
     */
    public RefCapabilitiesSharedCarrierV2|string $carrier;
    /**
     * Contract definition for capabilities endpoints (v2)
     *
     * @var RefCapabilitiesSharedContractV2
     */
    public RefCapabilitiesSharedContractV2 $contract;
    /**
     * @var list<RefShipmentPackageTypeV2|string>
     */
    public array $packageTypes;
    /**
     * @var RefCapabilitiesResponseOptionsOptionsV2
     */
    public RefCapabilitiesResponseOptionsOptionsV2 $options;
    /**
     * @var RefCapabilitiesResponsePhysicalPropertiesPhysicalPropertiesV2
     */
    public RefCapabilitiesResponsePhysicalPropertiesPhysicalPropertiesV2 $physicalProperties;
    /**
     * @var list<RefTypesDeliveryTypeV2|string>
     */
    public array $deliveryTypes;
    /**
     * @var list<RefTypesTransactionTypes|string>
     */
    public array $transactionTypes;
    /**
     * @var RefCapabilitiesResponseCollo
     */
    public RefCapabilitiesResponseCollo $collo;
}