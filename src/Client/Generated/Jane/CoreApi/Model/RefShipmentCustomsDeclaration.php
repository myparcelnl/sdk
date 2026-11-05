<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class RefShipmentCustomsDeclaration
{
    /**
     * @var ShipmentDefsCustomsShipmentType|int
     */
    public ShipmentDefsCustomsShipmentType|int $contents;
    /**
     * @var string|null
     */
    public ?string $invoice;
    /**
     * @var string|null
     */
    public ?string $eoriNumber;
    /**
     * @var string|null
     */
    public ?string $vatNumber;
    /**
     * @var string|null
     */
    public ?string $vatNumberUk;
    /**
     * @var string|null
     */
    public ?string $voecNumber;
    /**
     * @var int
     */
    public int $weight;
    /**
     * @var list<RefShipmentCustomsDeclarationItem>
     */
    public array $items;
}