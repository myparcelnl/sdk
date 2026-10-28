<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Returns;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostShipmentsClientErrorException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Normalizer\ValidationException;
use MyParcelNL\Sdk\Services\Returns\ReturnShipmentService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

final class ReturnShipmentServiceTest extends TestCase
{
    private const RELATED_ROW = [
        'parent'  => 1001,
        'carrier' => 1,
        'email'   => 'test@example.com',
        'name'    => 'John Doe',
    ];

    /**
     * @var array<int, array{method: string, url: string, content_type: string[], body: mixed}>
     */
    private array $requests = [];

    public function testCreateRelatedThrowsOnEmptyInput(): void
    {
        $service = $this->createService('{}');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('At least one related return shipment is required');

        $service->createRelated([]);
    }

    public function testCreateRelatedSendsTheReturnContentTypeAndTheMailFlagAndReadsTheIds(): void
    {
        $service = $this->createService(self::idsResponse([['id' => 9001, 'reference_identifier' => 'ret-9001']]));

        $result = $service->createRelated([self::RELATED_ROW], true);

        self::assertSame('POST', $this->requests[0]['method']);
        self::assertSame('https://api.myparcel.nl/shipments?send_return_mail=1', $this->requests[0]['url']);
        self::assertSame(['application/vnd.return_shipment+json'], $this->requests[0]['content_type']);
        self::assertSame(['data' => ['return_shipments' => [self::RELATED_ROW]]], $this->requests[0]['body']);
        self::assertSame([9001 => 'ret-9001'], $result);
    }

    public function testCreateRelatedSendsNoMailByDefault(): void
    {
        $service = $this->createService(self::idsResponse([]));

        $service->createRelated([self::RELATED_ROW]);

        self::assertSame('https://api.myparcel.nl/shipments?send_return_mail=0', $this->requests[0]['url']);
    }

    public function testCreateRelatedChecksTheRowBeforeItSends(): void
    {
        $service = $this->createService(self::idsResponse([]));

        try {
            $service->createRelated([['carrier' => 1, 'email' => 'test@example.com', 'name' => 'John Doe']]);
            self::fail('A related return without a parent must throw.');
        } catch (ValidationException) {
            self::assertSame([], $this->requests);
        }
    }

    public function testCreateRelatedReturnsEmptyMappingWhenResponseHasNoShipments(): void
    {
        $service = $this->createService(self::idsResponse([]));

        self::assertSame([], $service->createRelated([self::RELATED_ROW]));
    }

    public function testCreateRelatedThrowsOnUnexpectedResponse(): void
    {
        $service = $this->createService('{"data":{"pdf":{"url":"/pdfs/1"}}}', 200, 'application/vnd.shipment_label+json');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unexpected response type returned while parsing return shipment response.');

        $service->createRelated([self::RELATED_ROW]);
    }

    public function testAnErrorResponseThrowsTheGeneratedException(): void
    {
        $service = $this->createService('{"type":"urn:problem:not-found","title":"Parent not found","status":404}', 404, 'application/problem+json');

        try {
            $service->createRelated([self::RELATED_ROW]);
            self::fail('A 404 response must throw.');
        } catch (PostShipmentsClientErrorException $exception) {
            self::assertSame(404, $exception->getResponse()->getStatusCode());
        }
    }

    public function testCreateUnrelatedThrowsOnEmptyInput(): void
    {
        $service = $this->createService('{}');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('At least one unrelated return shipment is required');

        $service->createUnrelated([]);
    }

    public function testCreateUnrelatedSendsTheUnrelatedContentTypeAndReadsTheIds(): void
    {
        $row     = ['carrier' => 1, 'email' => 'test@example.com', 'name' => 'Jane Doe'];
        $service = $this->createService(self::idsResponse([['id' => 9101, 'reference_identifier' => 'unr-9101']]));

        $result = $service->createUnrelated([$row]);

        self::assertSame('https://api.myparcel.nl/shipments', $this->requests[0]['url']);
        self::assertSame(['application/vnd.unrelated_return_shipment+json'], $this->requests[0]['content_type']);
        self::assertSame(['data' => ['return_shipments' => [$row]]], $this->requests[0]['body']);
        self::assertSame([9101 => 'unr-9101'], $result);
    }

    /**
     * @param array<int, array<string, mixed>> $shipments
     */
    private static function idsResponse(array $shipments): string
    {
        return json_encode(['data' => ['shipments' => $shipments]], JSON_THROW_ON_ERROR);
    }

    private function createService(string $body, int $status = 200, string $contentType = 'application/json'): ReturnShipmentService
    {
        $httpClient = new MockHttpClient(function (string $method, string $url, array $options) use ($body, $status, $contentType): MockResponse {
            $this->requests[] = [
                'method'       => $method,
                'url'          => $url,
                'content_type' => array_map(static fn(string $line): string => substr($line, 14), $options['normalized_headers']['content-type'] ?? []),
                'body'         => json_decode((string) ($options['body'] ?? ''), true),
            ];

            return new MockResponse($body, ['http_code' => $status, 'response_headers' => ['content-type' => $contentType]]);
        });

        return new ReturnShipmentService('my-api-key', null, $httpClient);
    }
}
