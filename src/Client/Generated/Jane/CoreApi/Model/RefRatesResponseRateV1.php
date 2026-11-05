<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class RefRatesResponseRateV1
{
    /**
     * Carrier ID definition for capabilities endpoints (v1)
     *
     * @var RefCapabilitiesSharedCarrier|int
     */
    public RefCapabilitiesSharedCarrier|int $carrierId;
    /**
     * The contract object.
     *
     * @var RefRatesResponseContract
     */
    public RefRatesResponseContract $contract;
    /**
     * Package Type.
     *
     * @var RefShipmentPackageType|int
     */
    public RefShipmentPackageType|int $packageType;
    /**
     * @var RefTypesDeliveryType|int
     */
    public RefTypesDeliveryType|int $deliveryType;
    /**
     * @var RefTypesShipmentType|int
     */
    public RefTypesShipmentType|int $shipmentType;
    /**
     * The price composition object, containing the base, options, charges, discounts and total prices.
     *
     * @var RefRatesResponsePriceCompositionPriceCompositionV1
     */
    public RefRatesResponsePriceCompositionPriceCompositionV1 $priceComposition;
}