<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Client\Jane;

use MyParcelNL\Sdk\Client\Jane\ProblemJsonAcceptHttpClient;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

final class ProblemJsonAcceptHttpClientTest extends TestCase
{
    /**
     * @dataProvider provideAcceptHeaders
     *
     * @param array<string, string|string[]> $headers
     * @param string[]                       $expected The Accept lines that the API gets.
     */
    public function testAddsProblemJsonToTheFirstAcceptLineOnlyWhenALaterLineHasIt(array $headers, array $expected): void
    {
        $sent       = [];
        $httpClient = new ProblemJsonAcceptHttpClient(new MockHttpClient(
            static function (string $method, string $url, array $options) use (&$sent): MockResponse {
                $sent = $options['normalized_headers']['accept'] ?? [];

                return new MockResponse('');
            }
        ));

        $httpClient->request('GET', 'https://api.myparcel.nl/shipments', ['headers' => $headers]);

        self::assertSame($expected, $sent);
    }

    /**
     * @return array<string, array{array<string, string|string[]>, string[]}>
     */
    public static function provideAcceptHeaders(): array
    {
        return [
            'later line has it' => [
                ['Accept' => ['application/json', 'application/problem+json']],
                ['Accept: application/json, application/problem+json', 'Accept: application/problem+json'],
            ],
            'lower case header name' => [
                ['accept' => ['application/json;charset=utf-8;version=1.2', 'application/*', 'application/problem+json']],
                ['accept: application/json;charset=utf-8;version=1.2, application/problem+json', 'accept: application/*', 'accept: application/problem+json'],
            ],
            'first line has it' => [
                ['Accept' => ['application/problem+json', 'application/json']],
                ['Accept: application/problem+json', 'Accept: application/json'],
            ],
            'no line has it' => [
                ['Accept' => ['application/json']],
                ['Accept: application/json'],
            ],
            'one string' => [
                ['Accept' => 'application/pdf'],
                ['Accept: application/pdf'],
            ],
            'no Accept header, Symfony sends its default' => [
                ['User-Agent' => 'MyParcelNL-SDK'],
                ['Accept: */*'],
            ],
        ];
    }
}
