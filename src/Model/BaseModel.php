<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model;

use MyParcelNL\Sdk\Support\Helpers;

abstract class BaseModel
{
    /**
     * @return array
     */
    public function toArray(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function toArrayWithoutNull(): array
    {
        return Helpers::toArrayWithoutNull($this->toArray());
    }
}
