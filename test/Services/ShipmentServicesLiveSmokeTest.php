<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use MyParcelNL\Sdk\Helper\ShipmentCollection;
use MyParcelNL\Sdk\Model\MyParcelRequest;
use MyParcelNL\Sdk\Model\Shipment\Carrier;
use MyParcelNL\Sdk\Model\Shipment\PackageType;
use MyParcelNL\Sdk\Model\Shipment\Shipment;
use MyParcelNL\Sdk\Services\Labels\ShipmentLabelsService;
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
        $collection = new ShipmentCollection($this->liveApiKey);
        $collection->addShipment($this->createMinimalNlShipment('smoke-label-' . uniqid('', true)));

        try {
            $created = $collection->createConcepts();
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

    public function testFetchTrackTraceDataForCreatedShipment(): void
    {
        $collection = new ShipmentCollection($this->liveApiKey);
        $collection->addShipment($this->createMinimalNlShipment('smoke-tracktrace-' . uniqid('', true)));

        try {
            $created = $collection->createConcepts();
            $shipmentIds = array_keys($created);

            $trackTrace = new ShipmentTrackTraceService($this->liveApiKey);
            $result = $trackTrace->fetchTrackTraceData($shipmentIds);
        } catch (\Throwable $e) {
            $this->handleLiveException($e);
            return;
        }

        self::assertIsArray($result);

        foreach ($shipmentIds as $shipmentId) {
            if (! isset($result[$shipmentId])) {
                continue;
            }

            self::assertSame($shipmentId, (int) $result[$shipmentId]['shipment_id']);
        }
    }

    private function createMinimalNlShipment(string $referenceIdentifier): Shipment
    {
        return (new Shipment())
            ->withCarrier(Carrier::POSTNL)
            ->withPackageType(PackageType::PACKAGE)
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
}
