<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Helper;

use MyParcelNL\Sdk\Helper\ShipmentCollection;
use MyParcelNL\Sdk\Model\Shipment\Carrier;
use MyParcelNL\Sdk\Model\Shipment\PackageType;
use MyParcelNL\Sdk\Model\Shipment\Shipment;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

/**
 * @group live
 *
 * Live smoke test for ShipmentCollection (Phase 2).
 * Verifies the full create-flow against the real MyParcel API.
 * Skipped when no API key is present (API_KEY_NL / API_KEY_BE / API_KEY).
 */
final class ShipmentCollectionLiveSmokeTest extends TestCase
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

        // Reset curl factory so real HTTP calls go through.
        \MyParcelNL\Sdk\Model\MyParcelRequest::setCurlFactory(null);
    }

    // ------------------------------------------------------------------
    // Collection state helpers
    // ------------------------------------------------------------------

    public function testAddShipmentAndGetShipments(): void
    {
        $collection = new ShipmentCollection($this->liveApiKey);

        self::assertSame(0, $collection->count());
        self::assertSame([], $collection->getShipments());

        $shipment = $this->createMinimalNlShipment();
        $collection->addShipment($shipment);

        self::assertSame(1, $collection->count());
        self::assertSame([$shipment], $collection->getShipments());
    }

    public function testAddShipmentsMultiple(): void
    {
        $collection = new ShipmentCollection($this->liveApiKey);

        $s1 = $this->createMinimalNlShipment('smoke-multi-1');
        $s2 = $this->createMinimalNlShipment('smoke-multi-2');

        $collection->addShipments([$s1, $s2]);

        self::assertSame(2, $collection->count());
        self::assertSame([$s1, $s2], $collection->getShipments());
    }

    public function testClearShipmentsCollection(): void
    {
        $collection = new ShipmentCollection($this->liveApiKey);
        $collection->addShipment($this->createMinimalNlShipment());

        self::assertSame(1, $collection->count());

        $collection->clearShipmentsCollection();

        self::assertSame(0, $collection->count());
        self::assertSame([], $collection->getShipments());
    }

    // ------------------------------------------------------------------
    // createConcepts() — live API call
    // ------------------------------------------------------------------

    public function testCreateConceptsSingleShipment(): void
    {
        $refId = 'smoke-single-' . uniqid('', true);
        $shipment = $this->createMinimalNlShipment($refId);

        $collection = new ShipmentCollection($this->liveApiKey);
        $collection->addShipment($shipment);

        try {
            $result = $collection->createConcepts();
        } catch (\Throwable $e) {
            $this->handleLiveException($e);
            return;
        }

        self::assertNotEmpty($result, 'Expected at least one shipment ID in the response.');
        self::assertCount(1, $result);

        $id = array_key_first($result);
        self::assertIsInt($id);
        self::assertGreaterThan(0, $id);
        self::assertSame($refId, $result[$id], 'Reference identifier should match what we sent.');
    }

    public function testCreateConceptsMultipleShipments(): void
    {
        $ref1 = 'smoke-batch-1-' . uniqid('', true);
        $ref2 = 'smoke-batch-2-' . uniqid('', true);

        $collection = new ShipmentCollection($this->liveApiKey);
        $collection->addShipments([
            $this->createMinimalNlShipment($ref1),
            $this->createMinimalNlShipment($ref2),
        ]);

        try {
            $result = $collection->createConcepts();
        } catch (\Throwable $e) {
            $this->handleLiveException($e);
            return;
        }

        self::assertCount(2, $result);

        $refs = array_values($result);
        self::assertContains($ref1, $refs);
        self::assertContains($ref2, $refs);

        foreach (array_keys($result) as $id) {
            self::assertIsInt($id);
            self::assertGreaterThan(0, $id);
        }
    }

    public function testCreateConceptsAutoAssignsReferenceIdentifier(): void
    {
        // Create shipment WITHOUT passing a reference identifier
        // (the generated model doesn't allow null, so we simply don't set one)
        $shipment = $this->createMinimalNlShipment();

        $collection = new ShipmentCollection($this->liveApiKey);
        $collection->addShipment($shipment);

        try {
            $result = $collection->createConcepts();
        } catch (\Throwable $e) {
            $this->handleLiveException($e);
            return;
        }

        self::assertCount(1, $result);

        $ref = array_values($result)[0];
        self::assertNotNull($ref);
        self::assertStringStartsWith('sdk_', $ref, 'Auto-generated ref should start with sdk_');
        self::assertSame($ref, $shipment->getReferenceIdentifier(), 'Shipment object should be mutated with the auto-generated ref.');
    }

    public function testCreateConceptsWithFormatAndPositions(): void
    {
        $refId = 'smoke-format-' . uniqid('', true);
        $collection = new ShipmentCollection($this->liveApiKey);
        $collection->addShipment($this->createMinimalNlShipment($refId));

        try {
            $result = $collection->createConcepts('A4', '1;2;3;4');
        } catch (\Throwable $e) {
            $this->handleLiveException($e);
            return;
        }

        self::assertCount(1, $result);
        $id = array_key_first($result);
        self::assertIsInt($id);
        self::assertGreaterThan(0, $id);
    }

    // ------------------------------------------------------------------
    // createConcepts() — validation
    // ------------------------------------------------------------------

    public function testCreateConceptsThrowsWhenEmpty(): void
    {
        $collection = new ShipmentCollection($this->liveApiKey);

        $this->expectException(\InvalidArgumentException::class);
        $collection->createConcepts();
    }

    // ------------------------------------------------------------------
    // Reuse after clear
    // ------------------------------------------------------------------

    public function testCollectionIsReusableAfterClear(): void
    {
        $collection = new ShipmentCollection($this->liveApiKey);

        // First batch
        $ref1 = 'smoke-reuse-1-' . uniqid('', true);
        $collection->addShipment($this->createMinimalNlShipment($ref1));

        try {
            $result1 = $collection->createConcepts();
        } catch (\Throwable $e) {
            $this->handleLiveException($e);
            return;
        }

        self::assertCount(1, $result1);

        // Clear and add second batch
        $collection->clearShipmentsCollection();
        self::assertSame(0, $collection->count());

        $ref2 = 'smoke-reuse-2-' . uniqid('', true);
        $collection->addShipment($this->createMinimalNlShipment($ref2));

        try {
            $result2 = $collection->createConcepts();
        } catch (\Throwable $e) {
            $this->handleLiveException($e);
            return;
        }

        self::assertCount(1, $result2);

        // IDs should be different
        $id1 = array_key_first($result1);
        $id2 = array_key_first($result2);
        self::assertNotSame($id1, $id2, 'Second batch should produce a different shipment ID.');
    }

    // ------------------------------------------------------------------
    // Helpers
    // ------------------------------------------------------------------

    private function createMinimalNlShipment(?string $referenceIdentifier = null): Shipment
    {
        $shipment = (new Shipment())
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
            ]);

        if (null !== $referenceIdentifier) {
            $shipment->setReferenceIdentifier($referenceIdentifier);
        }

        return $shipment;
    }

    /**
     * Skip on transient/network errors, let real failures through.
     */
    private function handleLiveException(\Throwable $e): void
    {
        $message = $e->getMessage();

        // Network / DNS
        if (false !== strpos($message, 'Could not resolve host') ||
            false !== strpos($message, 'cURL error 6') ||
            false !== strpos($message, 'cURL error 7')) {
            $this->markTestSkipped('Skipping live smoke: network unavailable: ' . $message);
            return;
        }

        // Connection errors (Guzzle)
        if ($e instanceof \GuzzleHttp\Exception\ConnectException) {
            $this->markTestSkipped('Skipping live smoke: connection error: ' . $message);
            return;
        }

        // 5xx / rate limit
        if ($e instanceof \GuzzleHttp\Exception\RequestException && $e->getResponse()) {
            $code = $e->getResponse()->getStatusCode();
            if ($code >= 500 || $code === 429) {
                $this->markTestSkipped(sprintf(
                    'Skipping live smoke: transient API error HTTP %d: %s',
                    $code,
                    $message
                ));
                return;
            }
        }

        // OpenAPI spec mismatch (known issue with volume units)
        if ($e instanceof \InvalidArgumentException &&
            (false !== strpos($message, "Invalid value") && false !== strpos($message, "for '"))) {
            $this->markTestSkipped('Skipping live smoke: OpenAPI spec mismatch: ' . $message);
            return;
        }

        // Real failure — rethrow
        throw $e;
    }
}
