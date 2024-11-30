<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services;

use Exception;
use MyParcelNL\Sdk\Model\Carrier\CarrierPostNL;
use MyParcelNL\Sdk\Services\Web\DropOffPointWebService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class DropOffPointServiceTest extends TestCase
{
    /**
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testGetDropOffPoint(): void
    {
        $service = (new DropOffPointWebService(new CarrierPostNL()))->setApiKey($this->getApiKey());
        $result  = $service->getDropOffPoint('171963');

        if ($result) {
            self::assertEquals('171963', $result->getLocationCode());
        } else {
            throw new Exception('Not one drop off point returned for external identifier');
        }
    }

    /**
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testGetDropOffPoints(): void
    {
        $service       = (new DropOffPointWebService(new CarrierPostNL()))->setApiKey($this->getApiKey());
        $dropOffPoints = $service->getDropOffPoints('6825ME');

        self::assertNotEmpty($dropOffPoints->all(), 'No dropoff points found');
    }
}
