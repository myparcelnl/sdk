<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Web;

use MyParcelNL\Sdk\Model\Consignment\DropOffPoint;

interface CanGetDropOffPoint
{
    /**
     * @param  string $externalIdentifier
     *
     * @return null|\MyParcelNL\Sdk\Model\Consignment\DropOffPoint
     */
    public function getDropOffPoint(string $externalIdentifier): ?DropOffPoint;
}
