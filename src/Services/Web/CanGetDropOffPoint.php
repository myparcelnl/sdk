<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Services\Web;

use MyParcelNL\Sdk\src\Model\Consignment\DropOffPoint;

Interface CanGetDropOffPoint
{
    public function getDropOffPoint(string $externalIdentifier): ?DropOffPoint;
}
