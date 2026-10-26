<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class RefCapabilitiesResponseCapabilityV1
{
    /**
     * Carrier ID definition for capabilities endpoints (v1)
     *
     * @var RefCapabilitiesSharedCarrier|int
     */
    public RefCapabilitiesSharedCarrier|int $carrierId;
    /**
     * Contract definition for capabilities endpoints (v1)
     *
     * @var RefCapabilitiesSharedContractV1
     */
    public RefCapabilitiesSharedContractV1 $contract;
    /**
     * @var list<RefShipmentPackageType|int>
     */
    public array $packageTypes;
    /**
     * @var RefCapabilitiesResponseOptionsOptionsV1
     */
    public RefCapabilitiesResponseOptionsOptionsV1 $options;
    /**
     * @var RefCapabilitiesResponsePhysicalPropertiesPhysicalPropertiesV1
     */
    public RefCapabilitiesResponsePhysicalPropertiesPhysicalPropertiesV1 $physicalProperties;
    /**
     * @var list<RefTypesDeliveryType|int>
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