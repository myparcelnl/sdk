<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class RefRatesResponsePriceCompositionPriceCompositionV1
{
    /**
     * A V1 Price object.
     *
     * @var RefRatesResponsePriceCompositionPriceV1
     */
    public RefRatesResponsePriceCompositionPriceV1 $base;
    /**
     * @var list<RefRatesResponsePriceCompositionPriceV1>
     */
    public array $charges;
    /**
     * @var list<RefRatesResponsePriceCompositionPriceV1>
     */
    public array $discounts;
    /**
     * @var list<RefRatesResponsePriceCompositionPriceV1>
     */
    public array $options;
    /**
     * @var list<RefRatesResponsePriceCompositionPriceV1>
     */
    public array $other;
    /**
     * A V1 Price object.
     *
     * @var RefRatesResponsePriceCompositionPriceV1
     */
    public RefRatesResponsePriceCompositionPriceV1 $total;
}