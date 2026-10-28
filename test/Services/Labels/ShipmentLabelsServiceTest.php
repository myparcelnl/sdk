<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Labels;

use MyParcelNL\Sdk\Exception\ApiException;
use MyParcelNL\Sdk\Exception\MissingFieldException;
use MyParcelNL\Sdk\Services\Labels\ShipmentLabelsService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

final class ShipmentLabelsServiceTest extends TestCase
{
    /**
     * @var array<int, array{method: string, url: string, accept: string[], user_agent: string[]}>
     */
    private array $requests = [];

    public function testUseLabelPrepareThreshold(): void
    {
        $service = new ShipmentLabelsService($this->getApiKey());

        self::assertFalse($service->useLabelPrepare(25));
        self::assertTrue($service->useLabelPrepare(26));
    }

    public function testSetLinkOfLabelsSendsTheLinkAcceptHeaderAndStoresTheAbsoluteLink(): void
    {
        $service = $this->createService(json_encode(['data' => ['pdfs' => ['url' => '/pdfs/download/101;102']]]));

        $result = $service->setLinkOfLabels([101, 102], 2);

        self::assertSame('GET', $this->requests[0]['method']);
        self::assertSame('https://api.myparcel.nl/shipment_labels/101%3B102?format=A4&positions=2%3B3%3B4', $this->requests[0]['url']);
        self::assertSame(['application/vnd.shipment_label_link+json'], $this->requests[0]['accept']);
        self::assertNotEmpty($this->requests[0]['user_agent']);
        self::assertSame('https://api.myparcel.nl/pdfs/download/101;102', $result);
        self::assertSame($result, $service->getLinkOfLabels());
    }

    public function testSetLinkOfLabelsKeepsLegacyEmptyPositionsForOutOfRangeNumericInput(): void
    {
        $service = $this->createService(json_encode(['data' => ['pdf' => ['url' => 'https://cdn.myparcel.nl/label.pdf']]]));

        $result = $service->setLinkOfLabels([101], 7);

        self::assertSame('https://api.myparcel.nl/shipment_labels/101?format=A4&positions=', $this->requests[0]['url']);
        self::assertSame('https://cdn.myparcel.nl/label.pdf', $result);
    }

    public function testSetLinkOfLabelsAsksForA6WithoutPositions(): void
    {
        $service = $this->createService(json_encode(['data' => ['pdfs' => ['url' => '/pdfs/download/1']]]));

        $service->setLinkOfLabels(range(1, 26), false);

        self::assertSame('https://api.myparcel.nl/shipment_labels/' . implode('%3B', range(1, 26)) . '?format=A6', $this->requests[0]['url']);
    }

    public function testSetLinkOfLabelsThrowsOnInvalidResponseBody(): void
    {
        $service = $this->createService(json_encode(['data' => []]));

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Did not receive expected label link response. Please contact MyParcel.');

        $service->setLinkOfLabels([101]);
    }

    public function testSetLinkOfLabelsThrowsOnAnErrorResponse(): void
    {
        $service = $this->createService(json_encode(['errors' => [['code' => 3501, 'message' => 'Not found']]]), 404);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Did not receive expected label link response. Please contact MyParcel.');

        $service->setLinkOfLabels([101]);
    }

    public function testSetPdfOfLabelsSendsThePdfAcceptHeaderAndStoresThePdf(): void
    {
        $service = $this->createService('%PDF-1.4 label', 200, 'application/pdf');

        $result = $service->setPdfOfLabels([101]);

        self::assertSame(['application/pdf'], $this->requests[0]['accept']);
        self::assertSame('%PDF-1.4 label', $result);
        self::assertSame($result, $service->getLabelPdf());
    }

    public function testSetPdfOfLabelsThrowsOnPaymentInstructions(): void
    {
        $service = $this->createService(json_encode(['data' => ['payment_instructions' => [['id' => 1]]]]), 402);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Received payment link instead of pdf. Check your MyParcel account status.');

        $service->setPdfOfLabels([101]);
    }

    public function testSetPdfOfLabelsThrowsOnInvalidPdfResponse(): void
    {
        $service = $this->createService('not-a-pdf', 200, 'application/pdf');

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Did not receive expected pdf response. Please contact MyParcel.');

        $service->setPdfOfLabels([101]);
    }

    public function testDownloadPdfOfLabelsThrowsWhenPdfIsMissing(): void
    {
        $service = $this->createService('');

        $this->expectException(MissingFieldException::class);
        $this->expectExceptionMessage('First set label_pdf key with setPdfOfLabels() before running downloadPdfOfLabels()');

        $service->downloadPdfOfLabels();
    }

    private function createService(string $body, int $status = 200, string $contentType = 'application/json'): ShipmentLabelsService
    {
        $httpClient = new MockHttpClient(function (string $method, string $url, array $options) use ($body, $status, $contentType): MockResponse {
            $this->requests[] = [
                'method'     => $method,
                'url'        => $url,
                'accept'     => array_map(static fn(string $line): string => substr($line, 8), $options['normalized_headers']['accept'] ?? []),
                'user_agent' => $options['normalized_headers']['user-agent'] ?? [],
            ];

            return new MockResponse($body, ['http_code' => $status, 'response_headers' => ['content-type' => $contentType]]);
        });

        return new ShipmentLabelsService('my-api-key', null, $httpClient);
    }
}
