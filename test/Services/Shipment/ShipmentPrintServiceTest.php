<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Shipment;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostShipmentsClientErrorException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem as ShipmentItem;
use MyParcelNL\Sdk\Client\Jane\CoreApiModels;
use MyParcelNL\Sdk\Client\Jane\JaneClientFactory;
use MyParcelNL\Sdk\Services\Shipment\ShipmentPrintService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

final class ShipmentPrintServiceTest extends TestCase
{
    /**
     * @var array<int, array{method: string, url: string, accept: string[], body: array<string, mixed>}>
     */
    private array $requests = [];

    public function testPrintThrowsWhenNoShipmentsWereAdded(): void
    {
        $service = $this->createService('{}');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('At least one shipment must be added before calling print().');

        $service->print([], 'printer-group-1');
    }

    public function testPrintSendsTheDirectPrintAcceptHeaderAndReadsTheIds(): void
    {
        $result = $this->createService('{"data":{"ids":[{"id":5001,"reference_identifier":"order-1"}]}}')
            ->print([self::shipment('order-1')], 'printer-group-1');

        self::assertSame([5001 => 'order-1'], $result);
        self::assertSame('POST', $this->requests[0]['method']);
        self::assertSame('https://api.myparcel.nl/shipments', $this->requests[0]['url']);
        self::assertSame(
            [
                'application/vnd.shipment_label+json+print;printer-group-id=printer-group-1',
                'application/problem+json',
            ],
            $this->requests[0]['accept']
        );
        self::assertSame('order-1', $this->requests[0]['body']['data']['shipments'][0]['reference_identifier']);
    }

    public function testPrintGeneratesMissingReferenceIdentifiers(): void
    {
        $shipment = self::shipment();

        $this->createService('{"data":{"ids":[{"id":5002}]}}')->print([$shipment], 'printer-group-1');

        self::assertStringStartsWith('sdk_', (string) $shipment->referenceIdentifier);
        self::assertSame($shipment->referenceIdentifier, $this->requests[0]['body']['data']['shipments'][0]['reference_identifier']);
    }

    public function testPrintThrowsOnUnexpectedResponse(): void
    {
        $service = $this->createService('{}');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unexpected response type returned while parsing direct print response.');

        $service->print([self::shipment('order-1')], 'printer-group-1');
    }

    /**
     * The spec does not say which Accept value the API uses for an error, so the test covers both formats.
     *
     * @return array<string, array{string, string}>
     */
    public static function provideErrorResponses(): array
    {
        return [
            'application/json'         => ['{"message":"Printer group not found","request_id":"1","status_code":404,"errors":[]}', 'application/json'],
            'application/problem+json' => ['{"type":"urn:problem:not-found","title":"Printer group not found","status":404}', 'application/problem+json'],
        ];
    }

    /**
     * @dataProvider provideErrorResponses
     */
    public function testAnErrorResponseThrowsTheGeneratedException(string $body, string $contentType): void
    {
        $service = $this->createService($body, 404, $contentType);

        try {
            $service->print([self::shipment('order-1')], 'printer-group-1');
            self::fail('A 404 response must throw.');
        } catch (PostShipmentsClientErrorException $exception) {
            self::assertSame(404, $exception->getResponse()->getStatusCode());
        }
    }

    /**
     * A shipment with the fields that the spec requires.
     */
    private static function shipment(?string $referenceIdentifier = null): ShipmentItem
    {
        return CoreApiModels::toModel(array_filter([
            'reference_identifier' => $referenceIdentifier,
            'carrier'              => 1,
            'options'              => ['package_type' => 1],
            'recipient'            => [
                'cc'          => 'NL',
                'postal_code' => '2132JE',
                'city'        => 'Hoofddorp',
                'street'      => 'Antareslaan',
                'number'      => '31',
                'person'      => 'Test Person',
            ],
        ], static fn (mixed $value): bool => null !== $value), ShipmentItem::class);
    }

    private function createService(string $body, int $status = 200, string $contentType = 'application/json'): ShipmentPrintService
    {
        $httpClient = new MockHttpClient(function (string $method, string $url, array $options) use ($body, $status, $contentType): MockResponse {
            $this->requests[] = [
                'method' => $method,
                'url'    => $url,
                'accept' => array_map(static fn (string $line): string => substr($line, 8), $options['normalized_headers']['accept']),
                'body'   => json_decode((string) $options['body'], true, 512, JSON_THROW_ON_ERROR),
            ];

            return new MockResponse($body, ['http_code' => $status, 'response_headers' => ['content-type' => $contentType]]);
        });

        return new ShipmentPrintService('my-api-key', JaneClientFactory::coreApi('my-api-key', null, null, [], $httpClient));
    }
}
