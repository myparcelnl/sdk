<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\IamApi;

use MyParcelNL\Sdk\Client\Generated\IamApi\Api\DefaultApi;
use MyParcelNL\Sdk\Services\IamApi\IamApiFactory;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class IamApiFactoryTest extends TestCase
{
    public function testMakeCreatesConfiguredGeneratedIamClient(): void
    {
        $api = IamApiFactory::make('plain_api_key', 'https://iam.example.test', 'my-app/1.0');

        self::assertInstanceOf(DefaultApi::class, $api);
        self::assertSame('https://iam.example.test', $api->getConfig()->getHost());
        self::assertSame(base64_encode('plain_api_key'), $api->getConfig()->getAccessToken());
        self::assertSame('my-app/1.0', $api->getConfig()->getUserAgent());
    }

    public function testMakeThrowsWhenApiKeyIsEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('API key cannot be empty');

        IamApiFactory::make('');
    }
}
