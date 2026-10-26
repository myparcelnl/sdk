<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class RefRatesResponseRateV2
{
    /**
     * Carrier string definition for capabilities endpoints (v2)
     *
     * @var RefCapabilitiesSharedCarrierV2|string
     */
    public RefCapabilitiesSharedCarrierV2|string $carrier;
    /**
     * The contract object.
     *
     * @var RefRatesResponseContractV2
     */
    public RefRatesResponseContractV2 $contract;
    /**
     * Package Type.
     *
     * @var RefShipmentPackageTypeV2|string
     */
    public RefShipmentPackageTypeV2|string $packageType;
    /**
     * @var RefTypesDeliveryTypeV2|string
     */
    public RefTypesDeliveryTypeV2|string $deliveryType;
    /**
     * @var RefShipmentShipmentTypeV2|string
     */
    public RefShipmentShipmentTypeV2|string $shipmentType;
    /**
     * The V2 price composition object, containing the base, options, charges, discounts and total prices.
     *
     * @var RefRatesResponsePriceCompositionPriceCompositionV2
     */
    public RefRatesResponsePriceCompositionPriceCompositionV2 $priceComposition;
}