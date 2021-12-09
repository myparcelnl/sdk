<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Entity\Consignment;

use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;

class PackageType extends AbstractEntity
{
    /**
     * @param  mixed $input
     */
    public function __construct($input = null)
    {
        parent::__construct($input ?? AbstractConsignment::PACKAGE_TYPE_PACKAGE);
    }

    protected function getNamesIdsMap(): array
    {
        return AbstractConsignment::PACKAGE_TYPES_NAMES_IDS_MAP;
    }
}
