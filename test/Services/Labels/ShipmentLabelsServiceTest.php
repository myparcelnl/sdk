<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Labels;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\BadResponseException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetShipmentsLabelsClientErrorException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetShipmentsLabelsNotFoundException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\GetShipmentsLabelsPaymentRequiredException;
use MyParcelNL\Sdk\Client\Jane\JaneClientFactory;
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
        self::assertSame(['User-Agent: MyPlugin/1.0'], $this->requests[0]['user_agent']);
        self::assertSame('https://api.myparcel.nl/pdfs/download/101;102', $result);
        self::assertSame($result, $service->getLinkOfLabels());
    }

    /**
     * The spec has the positions 1 to 4. Another number leaves the parameter out.
     */
    public function testSetLinkOfLabelsLeavesOutAPositionThatTheSpecDoesNotHave(): void
    {
        $service = $this->createService(json_encode(['data' => ['pdf' => ['url' => 'https://cdn.myparcel.nl/label.pdf']]]));

        $result = $service->setLinkOfLabels([101], 7);

        self::assertSame('https://api.myparcel.nl/shipment_labels/101?format=A4', $this->requests[0]['url']);
        self::assertSame('https://cdn.myparcel.nl/label.pdf', $result);
    }

    public function testSetLinkOfLabelsSendsAListOfPositions(): void
    {
        $this->createService(json_encode(['data' => ['pdfs' => ['url' => '/pdfs/download/1']]]))->setLinkOfLabels([1], [1, 3]);

        self::assertSame('https://api.myparcel.nl/shipment_labels/1?format=A4&positions=1%3B3', $this->requests[0]['url']);
    }

    public function testSetLinkOfLabelsAsksForA6WithoutPositions(): void
    {
        $service = $this->createService(json_encode(['data' => ['pdfs' => ['url' => '/pdfs/download/1']]]));

        $service->setLinkOfLabels(range(1, 26), false);

        self::assertSame('https://api.myparcel.nl/shipment_labels/' . implode('%3B', range(1, 26)) . '?format=A6', $this->requests[0]['url']);
    }

    /**
     * @dataProvider provideInvalidLinkResponseBodies
     */
    public function testSetLinkOfLabelsThrowsOnInvalidResponseBody(string $body): void
    {
        $service = $this->createService($body);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Did not receive expected label link response. Please contact MyParcel.');

        $service->setLinkOfLabels([101]);
    }

    /**
     * @return array<string, array{string}>
     */
    public static function provideInvalidLinkResponseBodies(): array
    {
        return [
            'not JSON'          => ['not-json'],
            'JSON without link' => ['{"data":[]}'],
        ];
    }

    public function testAnErrorResponseThrowsTheGeneratedException(): void
    {
        $service = $this->createService('{"message":"Not found","request_id":"1","status_code":404,"errors":[]}', 404);

        try {
            $service->setLinkOfLabels([101]);
            self::fail('A 404 response must throw.');
        } catch (GetShipmentsLabelsNotFoundException $exception) {
            self::assertSame(404, $exception->getResponse()->getStatusCode());
        }
    }

    public function testSetPdfOfLabelsSendsThePdfAcceptHeaderAndStoresThePdf(): void
    {
        $service = $this->createService('%PDF-1.4 label', 200, 'application/pdf');

        $result = $service->setPdfOfLabels([101]);

        self::assertSame(['application/pdf'], $this->requests[0]['accept']);
        self::assertSame('%PDF-1.4 label', $result);
        self::assertSame($result, $service->getLabelPdf());
    }

    /**
     * The generated endpoint reads a 402 as payment instructions only with the content type of the link.
     */
    public function testAPaymentRequiredResponseThrowsTheGeneratedException(): void
    {
        $service = $this->createService(
            json_encode(['data' => ['payment_instructions' => ['payment_url' => 'https://pay.test/1'], 'shipments' => []]]),
            402,
            'application/vnd.shipment_label_link+json'
        );

        $this->expectException(GetShipmentsLabelsPaymentRequiredException::class);

        $service->setLinkOfLabels([101]);
    }

    /**
     * A 402 with a plain JSON body, for example on a PDF request, throws the client error exception.
     */
    public function testAPaymentRequiredResponseWithAJsonBodyThrowsTheClientErrorException(): void
    {
        $service = $this->createService('{"message":"Payment required","request_id":"1","status_code":402,"errors":[]}', 402);

        $this->expectException(GetShipmentsLabelsClientErrorException::class);

        $service->setPdfOfLabels([101]);
    }

    public function testAnErrorResponseThatIsNotJsonThrowsTheBadResponseException(): void
    {
        $service = $this->createService('<html>Bad gateway</html>', 502, 'text/html');

        $this->expectException(BadResponseException::class);

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

        return new ShipmentLabelsService('my-api-key', JaneClientFactory::coreApi('my-api-key', null, 'MyPlugin/1.0', [], $httpClient));
    }
}
