<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model;

use MyParcelNL\Sdk\Support\Helpers;

/**
 * @internal Legacy — used by Order v1 (fulfilment) and web services.
 */
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
