<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentPackageTypeV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesCarrierV2;
use MyParcelNL\Sdk\Collection\ShipmentCollection;
use MyParcelNL\Sdk\Model\MyParcelRequest;
use MyParcelNL\Sdk\Model\Shipment\Shipment;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;
use MyParcelNL\Sdk\Services\Labels\ShipmentLabelsService;
use MyParcelNL\Sdk\Services\Shipment\ShipmentCreateService;
use MyParcelNL\Sdk\Services\TrackTrace\ShipmentTrackTraceService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

/**
 * @group live
 */
final class ShipmentServicesLiveSmokeTest extends TestCase
{
    private string $liveApiKey;

    protected function setUp(): void
    {
        parent::setUp();

        $key = getenv('API_KEY') ?: getenv('API_KEY_NL') ?: getenv('API_KEY_BE');

        if (! $key) {
            $this->markTestSkipped('Skipping live smoke: no API key found (API_KEY / API_KEY_NL / API_KEY_BE).');
        }

        $this->liveApiKey = $key;

        // Use real HTTP calls.
        MyParcelRequest::setCurlFactory(null);
    }

    public function testSetLinkOfLabelsForCreatedShipment(): void
    {
        $collection = new ShipmentCollection();
        $collection->push($this->createMinimalNlShipment('smoke-label-' . uniqid('', true)));

        try {
            $createService = new ShipmentCreateService($this->liveApiKey);
            $created = $createService->create($collection);
            $shipmentIds = array_keys($created);

            $labels = new ShipmentLabelsService($this->liveApiKey);
            $link = $labels->setLinkOfLabels($shipmentIds, false);
        } catch (\Throwable $e) {
            $this->handleLiveException($e);
            return;
        }

        self::assertNotEmpty($link);
        self::assertStringContainsString((new MyParcelRequest())->getRequestUrl(), $link);
        self::assertSame($link, $labels->getLinkOfLabels());
    }

    public function testGeneratedLabelsRequestWithExplicitAcceptReturnsBody(): void
    {
        $collection = new ShipmentCollection();
        $collection->push($this->createMinimalNlShipment('smoke-generated-label-body-' . uniqid('', true)));

        try {
            $createService = new ShipmentCreateService($this->liveApiKey);
            $created = $createService->create($collection);
            $shipmentIds = array_keys($created);

            $api = ShipmentApiFactory::make($this->liveApiKey);
            $request = $api->getShipmentsLabelsRequest(
                implode(';', $shipmentIds),
                'SDK-LiveSmoke/labels-body-check',
                'A6'
            )->withHeader('Accept', 'application/vnd.shipment_label_link+json');

            $response = (new GuzzleClient(['timeout' => 10]))->send($request);
            $body = (string) $response->getBody();
        } catch (\Throwable $e) {
            $this->handleLiveException($e);
            return;
        }

        self::assertNotEmpty($body, 'Expected non-empty response body for label link request.');

        $decoded = json_decode($body, true);
        self::assertIsArray($decoded, 'Expected JSON body for label link response.');

        $link = $decoded['data']['pdfs']['url'] ?? $decoded['data']['pdf']['url'] ?? null;
        self::assertIsString($link, 'Expected label URL in response body.');
        self::assertNotSame('', $link, 'Expected non-empty label URL in response body.');
    }

    public function testFetchTrackTraceDataForCreatedShipment(): void
    {
        $collection = new ShipmentCollection();
        $collection->push($this->createMinimalNlShipment('smoke-tracktrace-' . uniqid('', true)));

        try {
            $createService = new ShipmentCreateService($this->liveApiKey);
            $created = $createService->create($collection);
            $shipmentIds = array_keys($created);
            $shipmentId = (int) $shipmentIds[0];

            $trackTrace = new ShipmentTrackTraceService($this->liveApiKey);
            $result = $this->fetchTrackTraceWithRetry($trackTrace, $shipmentIds, $shipmentId);
        } catch (\Throwable $e) {
            $this->handleLiveException($e);
            return;
        }

        self::assertIsArray($result);
        self::assertArrayHasKey($shipmentId, $result, 'Expected tracktrace for created shipment was not returned.');
        self::assertSame($shipmentId, (int) $result[$shipmentId]->getShipmentId());
    }

    private function createMinimalNlShipment(string $referenceIdentifier): Shipment
    {
        return (new Shipment())
            ->setCarrier(RefTypesCarrierV2::POSTNL)
            ->withPackageType(RefShipmentPackageTypeV2::PACKAGE)
            ->withWeight(1000)
            ->setRecipient([
                'cc'          => 'NL',
                'city'        => 'Hoofddorp',
                'street'      => 'Antareslaan',
                'number'      => '31',
                'postal_code' => '2132JE',
                'person'      => 'Smoke Test',
                'email'       => 'smoke@myparcel.nl',
                'phone'       => '0612345678',
            ])
            ->setReferenceIdentifier($referenceIdentifier);
    }

    private function handleLiveException(\Throwable $e): void
    {
        $message = $e->getMessage();

        if (false !== strpos($message, 'Could not resolve host') ||
            false !== strpos($message, 'cURL error 6') ||
            false !== strpos($message, 'cURL error 7')) {
            $this->markTestSkipped('Skipping live smoke: network unavailable: ' . $message);
            return;
        }

        if ($e instanceof ConnectException) {
            $this->markTestSkipped('Skipping live smoke: connection error: ' . $message);
            return;
        }

        if ($e instanceof RequestException && $e->getResponse()) {
            $code = $e->getResponse()->getStatusCode();
            if ($code >= 500 || 429 === $code) {
                $this->markTestSkipped(sprintf(
                    'Skipping live smoke: transient API error HTTP %d: %s',
                    $code,
                    $message
                ));
                return;
            }
        }

        throw $e;
    }

    /**
     * Poll track & trace endpoint briefly to account for eventual consistency.
     *
     * @param int[] $shipmentIds
     * @return array<int, mixed>
     */
    private function fetchTrackTraceWithRetry(
        ShipmentTrackTraceService $trackTraceService,
        array $shipmentIds,
        int $expectedShipmentId,
        int $attempts = 5,
        int $sleepMilliseconds = 1000
    ): array {
        $lastResult = [];

        for ($attempt = 1; $attempt <= $attempts; $attempt++) {
            $lastResult = $trackTraceService->fetchTrackTraceData($shipmentIds);

            if (isset($lastResult[$expectedShipmentId])) {
                return $lastResult;
            }

            if ($attempt < $attempts) {
                usleep($sleepMilliseconds * 1000);
            }
        }

        return $lastResult;
    }
}
