<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model;

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
        return array_filter(
            $this->toArray(),
            static function ($item) {
                return null !== $item;
            }
        );
    }

    /**
     * @param $param
     *
     * @return int|null
     */
    protected function intOrNull($param): ?int
    {
        if ($param) {
            return (int) $param;
        }

        return null;
    }
}
