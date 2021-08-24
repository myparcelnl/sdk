<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Tests\Services;

use MyParcelNL\Sdk\src\Exception\ApiException;
use MyParcelNL\Sdk\src\Services\Web\RedJePakketjeDropOffPointWebService;
use PHPUnit\Framework\TestCase;

class RedJePakketjeDropOffPointServiceTest extends TestCase
{
    /**
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function testGetDropOffPoints(): void
    {
        $service = (new RedJePakketjeDropOffPointWebService())->setApiKey(getenv('API_KEY'));
        $result  = $service->getDropOffPoints('6825ME');

        self::assertNotEmpty($result);
    }

    /**
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testGetDropOffPoint(): void
    {
        $service = (new RedJePakketjeDropOffPointWebService())->setApiKey(getenv('API_KEY'));
        $result  = $service->getDropOffPoint('e02158ab-7307-434b-956c-0aeb60ef1046');

        if (1 === count($result)) {
            self::assertEquals('e02158ab-7307-434b-956c-0aeb60ef1046', $result[0]['location_code']);
        } else {
            Throw new \Exception('Not one drop off point returned for external identifier');
        }
    }
}
