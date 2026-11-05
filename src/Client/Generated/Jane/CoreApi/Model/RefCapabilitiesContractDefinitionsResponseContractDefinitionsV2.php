<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2
{
    /**
     * Carrier string definition for capabilities endpoints (v2)
     *
     * @var RefCapabilitiesSharedCarrierV2|string
     */
    public RefCapabilitiesSharedCarrierV2|string $carrier;
    /**
     * @var list<RefShipmentPackageTypeV2|string>
     */
    public array $packageTypes;
    /**
     * @var RefCapabilitiesContractDefinitionsResponseOptionsOptionsV2
     */
    public RefCapabilitiesContractDefinitionsResponseOptionsOptionsV2 $options;
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