<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Labels;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Mockery;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\ShipmentApi;
use MyParcelNL\Sdk\Exception\ApiException;
use MyParcelNL\Sdk\Exception\MissingFieldException;
use MyParcelNL\Sdk\Services\Labels\ShipmentLabelsService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;

final class ShipmentLabelsServiceTest extends TestCase
{
    public function testUseLabelPrepareThreshold(): void
    {
        $service = new ShipmentLabelsService($this->getApiKey());

        self::assertFalse($service->useLabelPrepare(25));
        self::assertTrue($service->useLabelPrepare(26));
    }

    public function testSetLinkOfLabelsUsesRegularEndpointAndStoresLink(): void
    {
        $api = Mockery::mock(ShipmentApi::class);
        $httpClient = Mockery::mock(ClientInterface::class);
        $request = new Request('GET', 'https://api.myparcel.nl/shipment_labels/101;102?format=A4&positions=2;3;4');

        $api->shouldReceive('getShipmentsLabelsRequest')
            ->once()
            ->with(
                '101;102',
                Mockery::type('string'),
                'A4',
                '2;3;4',
                null,
                null,
                ShipmentApi::contentTypes['getShipmentsLabels'][0]
            )
            ->andReturn($request);

        $httpClient->shouldReceive('sendRequest')
            ->once()
            ->with(Mockery::on(static function (RequestInterface $request): bool {
                return ShipmentLabelsServiceTest::assertAcceptHeader(
                    $request,
                    'application/vnd.shipment_label_link+json'
                );
            }))
            ->andReturn(new Response(200, [], json_encode([
                'data' => [
                    'pdfs' => [
                        'url' => '/pdfs/download/101;102',
                    ],
                ],
            ])));

        $service = new ShipmentLabelsService($this->getApiKey(), $api, $httpClient);

        $result = $service->setLinkOfLabels([101, 102], 2);

        $expected = 'https://api.myparcel.nl/pdfs/download/101;102';

        self::assertSame($expected, $result);
        self::assertSame($expected, $service->getLinkOfLabels());
    }

    public function testSetLinkOfLabelsKeepsLegacyEmptyPositionsForOutOfRangeNumericInput(): void
    {
        $api = Mockery::mock(ShipmentApi::class);
        $httpClient = Mockery::mock(ClientInterface::class);
        $request = new Request('GET', 'https://api.myparcel.nl/shipment_labels/101?format=A4&positions=');

        $api->shouldReceive('getShipmentsLabelsRequest')
            ->once()
            ->with(
                '101',
                Mockery::type('string'),
                'A4',
                '',
                null,
                null,
                ShipmentApi::contentTypes['getShipmentsLabels'][0]
            )
            ->andReturn($request);

        $httpClient->shouldReceive('sendRequest')
            ->once()
            ->with(Mockery::on(static function (RequestInterface $request): bool {
                return ShipmentLabelsServiceTest::assertAcceptHeader(
                    $request,
                    'application/vnd.shipment_label_link+json'
                );
            }))
            ->andReturn(new Response(200, [], json_encode([
                'data' => [
                    'pdfs' => [
                        'url' => '/pdfs/download/101',
                    ],
                ],
            ])));

        $service = new ShipmentLabelsService($this->getApiKey(), $api, $httpClient);

        $result = $service->setLinkOfLabels([101], 5);

        $expected = 'https://api.myparcel.nl/pdfs/download/101';

        self::assertSame($expected, $result);
        self::assertSame($expected, $service->getLinkOfLabels());
    }

    public function testSetLinkOfLabelsUsesGeneratedEndpointForLargeBatch(): void
    {
        $api = Mockery::mock(ShipmentApi::class);
        $httpClient = Mockery::mock(ClientInterface::class);
        $ids = range(1, 26);
        $idsAsString = implode(';', $ids);
        $request = new Request('GET', 'https://api.myparcel.nl/shipment_labels/' . $idsAsString . '?format=A6');

        $api->shouldReceive('getShipmentsLabelsRequest')
            ->once()
            ->with(
                $idsAsString,
                Mockery::type('string'),
                'A6',
                null,
                null,
                null,
                ShipmentApi::contentTypes['getShipmentsLabels'][0]
            )
            ->andReturn($request);

        $httpClient->shouldReceive('sendRequest')
            ->once()
            ->with(Mockery::on(static function (RequestInterface $request): bool {
                return ShipmentLabelsServiceTest::assertAcceptHeader(
                    $request,
                    'application/vnd.shipment_label_link+json'
                );
            }))
            ->andReturn(new Response(200, [], json_encode([
                'data' => [
                    'pdf' => [
                        'url' => '/pdfs/prepared/abc',
                    ],
                ],
            ])));

        $service = new ShipmentLabelsService($this->getApiKey(), $api, $httpClient);

        $result = $service->setLinkOfLabels($ids, false);

        $expected = 'https://api.myparcel.nl/pdfs/prepared/abc';

        self::assertSame($expected, $result);
        self::assertSame($expected, $service->getLinkOfLabels());
    }

    public function testSetLinkOfLabelsThrowsOnInvalidResponseBody(): void
    {
        $api = Mockery::mock(ShipmentApi::class);
        $httpClient = Mockery::mock(ClientInterface::class);
        $request = new Request('GET', 'https://api.myparcel.nl/shipment_labels/101?format=A4&positions=1;2;3;4');

        $api->shouldReceive('getShipmentsLabelsRequest')
            ->once()
            ->with(
                '101',
                Mockery::type('string'),
                'A4',
                '1;2;3;4',
                null,
                null,
                ShipmentApi::contentTypes['getShipmentsLabels'][0]
            )
            ->andReturn($request);

        $httpClient->shouldReceive('sendRequest')
            ->once()
            ->with(Mockery::on(static function (RequestInterface $request): bool {
                return ShipmentLabelsServiceTest::assertAcceptHeader(
                    $request,
                    'application/vnd.shipment_label_link+json'
                );
            }))
            ->andReturn(new Response(200, [], 'not-json'));

        $service = new ShipmentLabelsService($this->getApiKey(), $api, $httpClient);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Did not receive expected label link response. Please contact MyParcel.');

        $service->setLinkOfLabels([101]);
    }

    public function testSetPdfOfLabelsThrowsOnPaymentInstructions(): void
    {
        $api = Mockery::mock(ShipmentApi::class);
        $httpClient = Mockery::mock(ClientInterface::class);
        $request = new Request('GET', 'https://api.myparcel.nl/shipment_labels/101?format=A4&positions=1;2;3;4');

        $api->shouldReceive('getShipmentsLabelsRequest')
            ->once()
            ->with(
                '101',
                Mockery::type('string'),
                'A4',
                '1;2;3;4',
                null,
                null,
                ShipmentApi::contentTypes['getShipmentsLabels'][0]
            )
            ->andReturn($request);

        $httpClient->shouldReceive('sendRequest')
            ->once()
            ->with(Mockery::on(static function (RequestInterface $request): bool {
                return ShipmentLabelsServiceTest::assertAcceptHeader($request, 'application/pdf');
            }))
            ->andReturn(new Response(200, [], json_encode([
                'data' => [
                    'payment_instructions' => [
                        'link' => 'https://pay.example.test',
                    ],
                ],
            ])));

        $service = new ShipmentLabelsService($this->getApiKey(), $api, $httpClient);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Received payment link instead of pdf. Check your MyParcel account status.');

        $service->setPdfOfLabels([101]);
    }

    public function testSetPdfOfLabelsStoresPdfBody(): void
    {
        $api = Mockery::mock(ShipmentApi::class);
        $httpClient = Mockery::mock(ClientInterface::class);
        $request = new Request('GET', 'https://api.myparcel.nl/shipment_labels/101?format=A4&positions=1;2;3;4');
        $pdf = "%PDF-1.4\nfake-pdf";

        $api->shouldReceive('getShipmentsLabelsRequest')
            ->once()
            ->with(
                '101',
                Mockery::type('string'),
                'A4',
                '1;2;3;4',
                null,
                null,
                ShipmentApi::contentTypes['getShipmentsLabels'][0]
            )
            ->andReturn($request);

        $httpClient->shouldReceive('sendRequest')
            ->once()
            ->with(Mockery::on(static function (RequestInterface $request): bool {
                return ShipmentLabelsServiceTest::assertAcceptHeader($request, 'application/pdf');
            }))
            ->andReturn(new Response(200, ['Content-Type' => 'application/pdf'], $pdf));

        $service = new ShipmentLabelsService($this->getApiKey(), $api, $httpClient);

        $result = $service->setPdfOfLabels([101]);

        self::assertSame($pdf, $result);
        self::assertSame($pdf, $service->getLabelPdf());
    }

    public function testSetPdfOfLabelsThrowsOnInvalidPdfResponse(): void
    {
        $api = Mockery::mock(ShipmentApi::class);
        $httpClient = Mockery::mock(ClientInterface::class);
        $request = new Request('GET', 'https://api.myparcel.nl/shipment_labels/101?format=A4&positions=1;2;3;4');

        $api->shouldReceive('getShipmentsLabelsRequest')
            ->once()
            ->with(
                '101',
                Mockery::type('string'),
                'A4',
                '1;2;3;4',
                null,
                null,
                ShipmentApi::contentTypes['getShipmentsLabels'][0]
            )
            ->andReturn($request);

        $httpClient->shouldReceive('sendRequest')
            ->once()
            ->with(Mockery::on(static function (RequestInterface $request): bool {
                return ShipmentLabelsServiceTest::assertAcceptHeader($request, 'application/pdf');
            }))
            ->andReturn(new Response(200, [], 'not-a-pdf'));

        $service = new ShipmentLabelsService($this->getApiKey(), $api, $httpClient);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Did not receive expected pdf response. Please contact MyParcel.');

        $service->setPdfOfLabels([101]);
    }

    public function testDownloadPdfOfLabelsThrowsWhenPdfIsMissing(): void
    {
        $service = new ShipmentLabelsService(
            $this->getApiKey(),
            Mockery::mock(ShipmentApi::class),
            Mockery::mock(ClientInterface::class)
        );

        $this->expectException(MissingFieldException::class);
        $this->expectExceptionMessage('First set label_pdf key with setPdfOfLabels() before running downloadPdfOfLabels()');

        $service->downloadPdfOfLabels();
    }

    private static function assertAcceptHeader(RequestInterface $request, string $expected): bool
    {
        return $expected === $request->getHeaderLine('Accept');
    }
}
