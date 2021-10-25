<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Tests\Services;

use Exception;
use MyParcelNL\Sdk\src\Model\Carrier\CarrierRedJePakketje;
use MyParcelNL\Sdk\src\Services\Web\DropOffPointWebService;
use PHPUnit\Framework\TestCase;

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
        $service = (new DropOffPointWebService(new CarrierRedJePakketje()))->setApiKey(getenv('API_KEY'));
        $result  = $service->getDropOffPoint('e02158ab-7307-434b-956c-0aeb60ef1046');

        if ($result) {
            self::assertEquals('e02158ab-7307-434b-956c-0aeb60ef1046', $result->getLocationCode());
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
        $service = (new DropOffPointWebService(new CarrierRedJePakketje()))->setApiKey(getenv('API_KEY'));
        $dropOffPoints  = $service->getDropOffPoints('6825ME');

        self::assertNotEmpty($dropOffPoints->all(), 'No dropoff points found');
    }
}
