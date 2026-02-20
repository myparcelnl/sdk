<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Labels;

use InvalidArgumentException;
use Mockery;
use MyParcelNL\Sdk\Exception\ApiException;
use MyParcelNL\Sdk\Exception\MissingFieldException;
use MyParcelNL\Sdk\Model\MyParcelRequest;
use MyParcelNL\Sdk\Services\Labels\ShipmentLabelsService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class ShipmentLabelsServiceTest extends TestCase
{
    public function testUseLabelPrepareThreshold(): void
    {
        $service = new ShipmentLabelsService($this->getApiKey());

        self::assertFalse($service->useLabelPrepare(MyParcelRequest::SHIPMENT_LABEL_PREPARE_ACTIVE_FROM));
        self::assertTrue($service->useLabelPrepare(MyParcelRequest::SHIPMENT_LABEL_PREPARE_ACTIVE_FROM + 1));
    }

    public function testSetLinkOfLabelsUsesRegularEndpointAndStoresLink(): void
    {
        $service = new ShipmentLabelsService($this->getApiKey());

        $curlMock = $this->mockCurl();
        $curlMock->shouldReceive('write')
            ->once()
            ->with(
                'GET',
                Mockery::on(static function ($url): bool {
                    return is_string($url)
                        && false !== strpos($url, '/shipment_labels/101;102/?format=A4&positions=2;3;4');
                }),
                Mockery::type('array'),
                Mockery::any()
            );

        $curlMock->shouldReceive('getResponse')
            ->once()
            ->andReturn([
                'response' => json_encode([
                    'data' => [
                        'pdfs' => [
                            'url' => '/pdfs/download/101;102',
                        ],
                    ],
                ]),
                'code' => 200,
            ]);

        $curlMock->shouldReceive('close')->once();

        $result = $service->setLinkOfLabels([101, 102], 2);

        $expected = (new MyParcelRequest())->getRequestUrl() . '/pdfs/download/101;102';

        self::assertSame($expected, $result);
        self::assertSame($expected, $service->getLinkOfLabels());
    }

    public function testSetLinkOfLabelsUsesPreparedEndpointForLargeBatch(): void
    {
        $service = new ShipmentLabelsService($this->getApiKey());

        $ids = range(1, 26);

        $curlMock = $this->mockCurl();
        $curlMock->shouldReceive('write')
            ->once()
            ->with(
                'GET',
                Mockery::on(static function ($url): bool {
                    return is_string($url)
                        && false !== strpos($url, '/v2/shipment_labels/')
                        && false !== strpos($url, '?format=A6');
                }),
                Mockery::type('array'),
                Mockery::any()
            );

        $curlMock->shouldReceive('getResponse')
            ->once()
            ->andReturn([
                'response' => json_encode([
                    'data' => [
                        'pdf' => [
                            'url' => '/pdfs/prepared/abc',
                        ],
                    ],
                ]),
                'code' => 200,
            ]);

        $curlMock->shouldReceive('close')->once();

        $result = $service->setLinkOfLabels($ids, false);

        $expected = (new MyParcelRequest())->getRequestUrl() . '/pdfs/prepared/abc';

        self::assertSame($expected, $result);
        self::assertSame($expected, $service->getLinkOfLabels());
    }

    public function testSetLinkOfLabelsThrowsOnEmptyIds(): void
    {
        $service = new ShipmentLabelsService($this->getApiKey());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('At least one shipment ID is required');

        $service->setLinkOfLabels([]);
    }

    public function testSetPdfOfLabelsThrowsOnPaymentInstructions(): void
    {
        $service = new ShipmentLabelsService($this->getApiKey());

        $curlMock = $this->mockCurl();
        $curlMock->shouldReceive('write')
            ->once()
            ->with(
                'GET',
                Mockery::on(static function ($url): bool {
                    return is_string($url)
                        && false !== strpos($url, '/shipment_labels/101/?format=A4&positions=1;2;3;4');
                }),
                Mockery::on(static function ($headers): bool {
                    return is_array($headers)
                        && isset($headers['Accept'])
                        && 'application/pdf' === $headers['Accept'];
                }),
                Mockery::any()
            );

        $curlMock->shouldReceive('getResponse')
            ->once()
            ->andReturn([
                'response' => json_encode([
                    'data' => [
                        'payment_instructions' => [
                            'link' => 'https://pay.example.test',
                        ],
                    ],
                ]),
                'code' => 200,
            ]);

        $curlMock->shouldReceive('close')->once();

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Received payment link instead of pdf. Check your MyParcel account status.');

        $service->setPdfOfLabels([101]);
    }

    public function testSetPdfOfLabelsThrowsOnInvalidPdfResponse(): void
    {
        $service = new ShipmentLabelsService($this->getApiKey());

        $curlMock = $this->mockCurl();
        $curlMock->shouldReceive('write')->once();
        $curlMock->shouldReceive('getResponse')
            ->once()
            ->andReturn([
                'response' => 'not-a-pdf',
                'code' => 200,
            ]);
        $curlMock->shouldReceive('close')->once();

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Did not receive expected pdf response. Please contact MyParcel.');

        $service->setPdfOfLabels([101]);
    }

    public function testDownloadPdfOfLabelsThrowsWhenPdfIsMissing(): void
    {
        $service = new ShipmentLabelsService($this->getApiKey());

        $this->expectException(MissingFieldException::class);
        $this->expectExceptionMessage('First set label_pdf key with setPdfOfLabels() before running downloadPdfOfLabels()');

        $service->downloadPdfOfLabels();
    }
}
