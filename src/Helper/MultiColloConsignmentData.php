<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Helper;

/**
 * Data object for a single consignment in a multi collo shipment.
 */
class MultiColloConsignmentData
{
    /** @var int */
    public $carrierId;
    /** @var string */
    public $apiKey;
    /** @var int */
    public $totalWeight;
    /** @var string|null */
    public $referenceIdentifier;

    public function __construct(int $carrierId, string $apiKey, int $totalWeight, ?string $referenceIdentifier = null)
    {
        $this->carrierId = $carrierId;
        $this->apiKey = $apiKey;
        $this->totalWeight = $totalWeight;
        $this->referenceIdentifier = $referenceIdentifier;
    }
} 