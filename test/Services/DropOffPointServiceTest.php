<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services;

use Exception;
use MyParcelNL\Sdk\src\Model\Carrier\CarrierRedJePakketje;
use MyParcelNL\Sdk\src\Services\Web\DropOffPointWebService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class DropOffPointServiceTest extends TestCase
{
    /**
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testGetDropOffPoint(): void
    {
        $service = (new DropOffPointWebService(new CarrierRedJePakketje()))->setApiKey($this->getApiKey());
        $result  = $service->getDropOffPoint('e9149b66-7bee-439b-bab0-7a5d92ddc519');

        if ($result) {
            self::assertEquals('e9149b66-7bee-439b-bab0-7a5d92ddc519', $result->getLocationCode());
        } else {
            throw new Exception('Not one drop off point returned for external identifier');
        }
    }

    /**
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testGetDropOffPoints(): void
    {
        $service       = (new DropOffPointWebService(new CarrierRedJePakketje()))->setApiKey($this->getApiKey());
        $dropOffPoints = $service->getDropOffPoints('6825ME');

        self::assertNotEmpty($dropOffPoints->all(), 'No dropoff points found');
    }
}
