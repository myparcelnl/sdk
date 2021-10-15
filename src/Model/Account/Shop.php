<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Account;

use MyParcelNL\Sdk\src\Model\BaseModel;

class Shop extends BaseModel
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @param  array $data
     */
    public function __construct(array $data)
    {
        $this->id   = $data['id'];
        $this->name = $data['name'];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
