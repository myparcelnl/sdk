<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model;

interface ArrayWithoutNullInterface
{
    public function toArrayWithoutNull(): array;
}
