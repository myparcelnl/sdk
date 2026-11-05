<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class RatesPostRatesRequestV2PhysicalPropertiesWeight
{
    /**
     * The weight value
     *
     * @var int
     */
    public int $value;
    /**
     * The unit of measurement in SI units (e.g., g, kg)
     *
     * @var RatesPostRatesRequestV2PhysicalPropertiesWeightUnit|string
     */
    public RatesPostRatesRequestV2PhysicalPropertiesWeightUnit|string $unit;
}