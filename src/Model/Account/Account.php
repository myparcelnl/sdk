<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Account;

use MyParcelNL\Sdk\Model\BaseModel;
use MyParcelNL\Sdk\Support\Collection;

class Account extends BaseModel
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     * Primary storage is proposition_id for future-forward approach
     */
    private $proposition_id;

    /**
     * @var Shop[]|\MyParcelNL\Sdk\Support\Collection
     */
    private $shops;

    /**
     * @param  array $data
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'];
        
        // Support both current API (platform_id) and future API (proposition_id)
        // Store as proposition_id internally for clean future transition
        $this->proposition_id = $data['proposition_id'] ?? $data['platform_id'];
        
        $this->shops = (new Collection($data['shops']))->mapInto(Shop::class);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     * @deprecated Use getPropositionId() instead. Will be removed in next major version.
     */
    public function getPlatformId(): int
    {
        return $this->proposition_id; // Legacy method accessing proposition data
    }

    /**
     * @return int
     */
    public function getPropositionId(): int
    {
        return $this->proposition_id;
    }

    /**
     * @return \MyParcelNL\Sdk\Model\Account\Shop[]|\MyParcelNL\Sdk\Support\Collection
     */
    public function getShops(): Collection
    {
        return $this->shops;
    }

    /**
     * @return array
     */
    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id'             => $this->getId(),
            'proposition_id' => $this->getPropositionId(), // Primary/future format
            'platform_id'    => $this->getPlatformId(),    // Legacy format for compatibility
            'shops'          => $this->getShops(),
        ];
    }
}
