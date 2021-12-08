<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model;

use MyParcelNL\Sdk\src\Model\MyParcelRequest;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use ReflectionMethod;

class MyParcelRequestTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testGetHeaders(): void
    {
        $request = (new MyParcelRequest())->setApiKey($this->getApiKey())
            ->setHeaders(
                [
                    'Accept' => 'application/pdf',
                ]
            );

        $headers = $request->getHeaders();
        self::assertArrayHasKey('Authorization', $headers);
        self::assertArrayHasKey('Content-Type', $headers);
        self::assertArrayHasKey('User-Agent', $headers);
        self::assertEquals('application/pdf', $headers['Accept']);

        $request->setUserAgent(
            'MyECommercePlatform',
            '4.4.0'
        );

        $headers = $request->getHeaders();
        self::assertContains('MyECommercePlatform/4.4.0', $headers['User-Agent']);
    }

    /**
     * @throws \ReflectionException
     * @throws \Exception
     * @throws \Exception
     */
    public function testGetRequestBody(): void
    {
        $testMethod = new ReflectionMethod(MyParcelRequest::class, 'createRequestUrl');
        $testMethod->setAccessible(true);

        $request = (new MyParcelRequest())->setApiKey($this->getApiKey())
            ->setQuery(['account_id' => '12345', 'size' => 50]);
        self::assertEquals(
            $request->getRequestUrl() . '/shipments?account_id=12345&size=50',
            $testMethod->invokeArgs($request, [MyParcelRequest::REQUEST_TYPE_SHIPMENTS, 'GET'])
        );

        $request = (new MyParcelRequest())->setApiKey($this->getApiKey())
            ->setRequestBody('21124')
            ->setQuery(['q' => 'something']);
        self::assertEquals(
            $request->getRequestUrl() . '/shipments/21124?q=something',
            $testMethod->invokeArgs($request, [MyParcelRequest::REQUEST_TYPE_SHIPMENTS, 'GET'])
        );
    }
}
