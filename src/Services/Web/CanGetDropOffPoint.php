<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Services\Web;

use MyParcelNL\Sdk\src\Model\Consignment\DropOffPoint;

interface CanGetDropOffPoint
{
    /**
     * @param  string $externalIdentifier
     *
     * @return null|\MyParcelNL\Sdk\src\Model\Consignment\DropOffPoint
     */
    public function getDropOffPoint(string $externalIdentifier): ?DropOffPoint;
}
