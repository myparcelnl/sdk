<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Account;

use MyParcelNL\Sdk\src\Model\BaseModel;
use MyParcelNL\Sdk\src\Support\Collection;

class Account extends BaseModel
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $platform_id;

    /**
     * @var Shop[]|\MyParcelNL\Sdk\src\Support\Collection
     */
    private $shops;

    /**
     * @param  array $data
     */
    public function __construct(array $data)
    {
        $this->id          = $data['id'];
        $this->platform_id = $data['platform_id'];
        $this->shops       = (new Collection($data['shops']))->mapInto(Shop::class);
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
     */
    public function getPlatformId(): int
    {
        return $this->platform_id;
    }

    /**
     * @return \MyParcelNL\Sdk\src\Model\Account\Shop[]|\MyParcelNL\Sdk\src\Support\Collection
     */
    public function getShops(): Collection
    {
        return $this->shops;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id'          => $this->getId(),
            'platform_id' => $this->getPlatformId(),
            'shops'       => $this->getShops(),
        ];
    }
}
