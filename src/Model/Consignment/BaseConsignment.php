<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Consignment;

class BaseConsignment extends AbstractConsignment
{
    /**
     * @return string
     */
    public function getLocalCountryCode(): string
    {
        return self::CC_NL;
    }

    /**
     * @return string[]
     */
    public function getAllowedPackageTypes(): array
    {
        return self::PACKAGE_TYPES_NAMES;
    }
}
