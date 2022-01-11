<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model;

use MyParcelNL\Sdk\src\Support\Helpers;

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
