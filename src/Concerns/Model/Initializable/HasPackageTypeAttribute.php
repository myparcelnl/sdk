<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Concerns\Model\Initializable;

use MyParcelNL\Sdk\src\Entity\Consignment\PackageType;

/**
 * @property \MyParcelNL\Sdk\src\Entity\Consignment\PackageType $packageType
 */
trait HasPackageTypeAttribute
{
    protected function initializeHasPackageType(): void
    {
        $this->append('packageType');
    }

    /**
     * @param  string|int $packageType
     *
     * @return void
     */
    protected function setPackageTypeAttribute($packageType): void
    {
        $this->packageType = new PackageType($packageType);
    }
}
