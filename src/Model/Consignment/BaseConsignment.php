<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Consignment;

use MyParcelNL\Sdk\src\Helper\CountryCodes;

class BaseConsignment extends AbstractConsignment
{
    /**
     * @return string
     */
    public function getLocalCountryCode(): string
    {
        return CountryCodes::CC_NL;
    }

    /**
     * @return string[]
     */
    public function getAllowedPackageTypes(): array
    {
        return self::PACKAGE_TYPES_NAMES;
    }
}
