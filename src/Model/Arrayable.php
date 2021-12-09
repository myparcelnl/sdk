<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model;

interface Arrayable
{
    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray();
}

