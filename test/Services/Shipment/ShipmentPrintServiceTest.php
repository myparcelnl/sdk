<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Shipment;

use InvalidArgumentException;
use Mockery;
use MyParcelNL\Sdk\Collection\ShipmentCollection;
use MyParcelNL\Sdk\Model\MyParcelRequest;
use MyParcelNL\Sdk\Model\Shipment\Shipment;
use MyParcelNL\Sdk\Services\Shipment\ShipmentPrintService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class ShipmentPrintServiceTest extends TestCase
{
    public function testPrintThrowsWhenNoShipmentsWereAdded(): void
    {
        $service = new ShipmentPrintService($this->getApiKey());
        $collection = new ShipmentCollection();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('At least one shipment must be added before calling print().');

        $service->print($collection, 'printer-group-1');
    }

    public function testPrintThrowsWhenMoreThanHundredShipmentsWereAdded(): void
    {
        $service = new ShipmentPrintService($this->getApiKey());
        $collection = new ShipmentCollection();

        for ($i = 0; $i < 101; $i++) {
            $collection->add(new Shipment());
        }

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Maximum 100 shipments per call');

        $service->print($collection, 'printer-group-1');
    }

    public function testPrintUsesMyParcelRequestWithDirectPrintHeader(): void
    {
        $collection = new ShipmentCollection([
            (new Shipment())->setReferenceIdentifier('order-1001'),
        ]);

        $service = new ShipmentPrintService($this->getApiKey());
        $service->setUserAgentForProposition('Magento', '2.4.7');

        $expectedUserAgent = $service->getUserAgentHeader();
        $printerGroupId = 'printer-group-1';
        $expectedAccept = MyParcelRequest::getDirectPrintAcceptHeader($printerGroupId)['Accept'];

        $curlMock = $this->mockCurl();
        $curlMock->shouldReceive('write')
            ->once()
            ->with(
                Mockery::on(static function ($method): bool {
                    return 'POST' === strtoupper((string) $method);
                }),
                Mockery::on(static function ($url): bool {
                    return is_string($url)
                        && false !== strpos($url, MyParcelRequest::REQUEST_URL . '/shipments')
                        && false === strpos($url, MyParcelRequest::PRINTING_API_URL);
                }),
                Mockery::on(static function ($headers) use ($expectedAccept): bool {
                    self::assertIsArray($headers);
                    self::assertArrayHasKey('Accept', $headers);
                    self::assertArrayHasKey('Content-Type', $headers);
                    self::assertArrayHasKey('Authorization', $headers);
                    self::assertSame($expectedAccept, $headers['Accept']);
                    self::assertSame(
                        MyParcelRequest::HEADER_CONTENT_TYPE_SHIPMENT['Content-Type'],
                        $headers['Content-Type']
                    );

                    return true;
                }),
                Mockery::on(static function ($body) use ($expectedUserAgent): bool {
                    self::assertIsString($body);
                    $decoded = json_decode($body, true);

                    self::assertIsArray($decoded);
                    self::assertSame($expectedUserAgent, $decoded['data']['user_agent'] ?? null);
                    self::assertSame('order-1001', $decoded['data']['shipments'][0]['reference_identifier'] ?? null);

                    return true;
                })
            );

        $curlMock->shouldReceive('getResponse')
            ->once()
            ->andReturn([
                'response' => json_encode([
                    'data' => [
                        'ids' => [
                            ['id' => 6001, 'reference_identifier' => 'order-1001'],
                        ],
                    ],
                ]),
                'code' => 200,
            ]);

        $curlMock->shouldReceive('close')->once();

        $result = $service->print($collection, $printerGroupId);

        self::assertSame([6001 => 'order-1001'], $result);
    }

    public function testPrintGeneratesMissingReferenceIdentifiers(): void
    {
        $shipment = new Shipment();
        $collection = new ShipmentCollection([$shipment]);
        $service = new ShipmentPrintService($this->getApiKey());

        $curlMock = $this->mockCurl();
        $curlMock->shouldReceive('write')->once();
        $curlMock->shouldReceive('getResponse')->once()->andReturn([
            'response' => json_encode([
                'data' => [
                    'ids' => [
                        ['id' => 9001, 'reference_identifier' => null],
                    ],
                ],
            ]),
            'code' => 200,
        ]);
        $curlMock->shouldReceive('close')->once();

        $service->print($collection, 'printer-group-1');

        self::assertNotNull($shipment->getReferenceIdentifier());
        self::assertStringStartsWith('sdk_', $shipment->getReferenceIdentifier());
    }
}
