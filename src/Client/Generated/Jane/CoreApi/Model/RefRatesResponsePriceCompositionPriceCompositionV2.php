<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class RefRatesResponsePriceCompositionPriceCompositionV2
{
    /**
     * A V2 Price object.
     *
     * @var RefRatesResponsePriceCompositionPriceV2
     */
    public RefRatesResponsePriceCompositionPriceV2 $base;
    /**
     * @var list<RefRatesResponsePriceCompositionPriceV2>
     */
    public array $charges;
    /**
     * @var list<RefRatesResponsePriceCompositionPriceV2>
     */
    public array $discounts;
    /**
     * @var list<RefRatesResponsePriceCompositionPriceV2>
     */
    public array $options;
    /**
     * @var list<RefRatesResponsePriceCompositionPriceV2>
     */
    public array $other;
    /**
     * A V2 Price object.
     *
     * @var RefRatesResponsePriceCompositionPriceV2
     */
    public RefRatesResponsePriceCompositionPriceV2 $total;
}