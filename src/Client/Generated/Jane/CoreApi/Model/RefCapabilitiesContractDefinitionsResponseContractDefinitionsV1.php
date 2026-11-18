<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class RefCapabilitiesContractDefinitionsResponseContractDefinitionsV1
{
    /**
     * Carrier ID definition for capabilities endpoints (v1)
     *
     * @var RefCapabilitiesSharedCarrier|int
     */
    public RefCapabilitiesSharedCarrier|int $carrierId;
    /**
     * @var list<RefShipmentPackageType|int>
     */
    public array $packageTypes;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionsV1
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionsV1 $options;
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