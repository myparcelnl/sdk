<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Shipment;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi;
use MyParcelNL\Sdk\Collection\ShipmentCollection;
use MyParcelNL\Sdk\Model\Shipment\Shipment;
use MyParcelNL\Sdk\Services\Shipment\ShipmentPrintService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;

final class ShipmentPrintServiceTest extends TestCase
{
    public function testPrintThrowsWhenNoShipmentsWereAdded(): void
    {
        $api = $this->createMock(ShipmentApi::class);
        $httpClient = $this->createMock(ClientInterface::class);

        $service = new ShipmentPrintService($this->getApiKey(), $api, $httpClient);
        $collection = new ShipmentCollection();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('At least one shipment must be added before calling print().');

        $service->print($collection, 'printer-group-1');
    }

    public function testPrintSetsDirectPrintAcceptHeaderAndParsesResponse(): void
    {
        $printerGroupId = 'printer-group-1';
        $expectedAccept = "application/vnd.shipment_label+json+print;printer-group-id={$printerGroupId}";

        $api = $this->createMock(ShipmentApi::class);
        $api->expects(self::once())
            ->method('postShipmentsRequest')
            ->willReturn(new Request('POST', 'https://api.myparcel.nl/shipments', [], '{}'));

        $capturedRequest = null;
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects(self::once())
            ->method('sendRequest')
            ->willReturnCallback(function (RequestInterface $request) use (&$capturedRequest) {
                $capturedRequest = $request;

                return new Response(200, ['Content-Type' => 'application/json'], json_encode([
                    'data' => [
                        'ids' => [
                            ['id' => 6001, 'reference_identifier' => 'order-1001'],
                        ],
                    ],
                ]));
            });

        $collection = new ShipmentCollection([
            (new Shipment())->setReferenceIdentifier('order-1001'),
        ]);

        $service = new ShipmentPrintService($this->getApiKey(), $api, $httpClient);
        $service->setUserAgentForProposition('Magento', '2.4.7');

        $result = $service->print($collection, $printerGroupId);

        self::assertSame([6001 => 'order-1001'], $result);
        self::assertNotNull($capturedRequest);
        self::assertSame($expectedAccept, $capturedRequest->getHeaderLine('Accept'));
    }

    public function testPrintGeneratesMissingReferenceIdentifiers(): void
    {
        $api = $this->createMock(ShipmentApi::class);
        $api->method('postShipmentsRequest')
            ->willReturn(new Request('POST', 'https://api.myparcel.nl/shipments', [], '{}'));

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->method('sendRequest')
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'data' => [
                    'ids' => [
                        ['id' => 9001, 'reference_identifier' => null],
                    ],
                ],
            ])));

        $shipment = new Shipment();
        $collection = new ShipmentCollection([$shipment]);
        $service = new ShipmentPrintService($this->getApiKey(), $api, $httpClient);

        $service->print($collection, 'printer-group-1');

        self::assertNotNull($shipment->getReferenceIdentifier());
        self::assertStringStartsWith('sdk_', $shipment->getReferenceIdentifier());
    }

    public function testPrintReturnsEmptyArrayOnUnexpectedResponse(): void
    {
        $api = $this->createMock(ShipmentApi::class);
        $api->method('postShipmentsRequest')
            ->willReturn(new Request('POST', 'https://api.myparcel.nl/shipments', [], '{}'));

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->method('sendRequest')
            ->willReturn(new Response(200, [], '{"error": "something"}'));

        $collection = new ShipmentCollection([
            (new Shipment())->setReferenceIdentifier('order-x'),
        ]);

        $service = new ShipmentPrintService($this->getApiKey(), $api, $httpClient);

        self::assertSame([], $service->print($collection, 'printer-group-1'));
    }
}
