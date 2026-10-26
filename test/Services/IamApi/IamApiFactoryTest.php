<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\IamApi;

use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Client;
use MyParcelNL\Sdk\Services\IamApi\IamApiFactory;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

/**
 * JaneClientFactoryTest checks the host, the API key and the user agent of the client.
 */
final class IamApiFactoryTest extends TestCase
{
    public function testMakeCreatesTheGeneratedIamClient(): void
    {
        self::assertInstanceOf(Client::class, IamApiFactory::make('plain_api_key', 'https://iam.example.test', 'my-app/1.0'));
    }

    public function testMakeThrowsWhenApiKeyIsEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('API key cannot be empty');

        IamApiFactory::make('');
    }
}
