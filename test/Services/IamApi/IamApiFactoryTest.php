<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\IamApi;

use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Client;
use MyParcelNL\Sdk\Services\IamApi\IamApiFactory;
use MyParcelNL\Sdk\Test\Bootstrap\MocksHttpClient;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class IamApiFactoryTest extends TestCase
{
    use MocksHttpClient;

    public function testMakeCreatesTheGeneratedIamClient(): void
    {
        self::assertInstanceOf(Client::class, IamApiFactory::make('plain_api_key', 'https://iam.example.test', 'my-app/1.0'));
    }

    public function testMakeSendsTheApiKeyTheHostTheUserAgentAndTheHeaders(): void
    {
        $client = IamApiFactory::make('plain_api_key', 'https://iam.example.test', 'my-app/1.0', ['x-dmp-test-feature' => 'enabled'], $this->mockHttpClient());

        $client->getWhoami();

        self::assertSame('GET', $this->requests[0]['method']);
        self::assertSame('https://iam.example.test/whoami', $this->requests[0]['url']);
        self::assertSame('Bearer ' . base64_encode('plain_api_key'), $this->header('authorization'));
        self::assertSame('my-app/1.0', $this->header('user-agent'));
        self::assertSame('enabled', $this->header('x-dmp-test-feature'));
    }

    public function testMakeThrowsWhenApiKeyIsEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('API key cannot be empty');

        IamApiFactory::make('');
    }
}
