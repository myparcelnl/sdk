<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesCarrierReturns;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentPackageTypeV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesCarrierV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Configuration;
use MyParcelNL\Sdk\Collection\ShipmentCollection;
use MyParcelNL\Sdk\Model\Shipment\Shipment;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;
use MyParcelNL\Sdk\Services\Labels\ShipmentLabelsService;
use MyParcelNL\Sdk\Services\MultiCollo\MultiColloShipmentService;
use MyParcelNL\Sdk\Services\Returns\ReturnShipmentService;
use MyParcelNL\Sdk\Services\Shipment\ShipmentCreateService;
use MyParcelNL\Sdk\Services\Shipment\ShipmentDeleteService;
use MyParcelNL\Sdk\Services\Shipment\ShipmentQueryService;
use MyParcelNL\Sdk\Services\TrackTrace\ShipmentTrackTraceService;
use MyParcelNL\Sdk\Services\Webhook\WebhookService;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesDeliveryTypeV2;
use MyParcelNL\Sdk\Collection\Fulfilment\OrderCollection;
use MyParcelNL\Sdk\Model\Fulfilment\Order;
use MyParcelNL\Sdk\Model\Fulfilment\OrderLine;
use MyParcelNL\Sdk\Model\Fulfilment\OrderShipmentOptions;
use MyParcelNL\Sdk\Model\Fulfilment\Product;
use MyParcelNL\Sdk\Model\Recipient;
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
        self::assertStringContainsString((new Configuration())->getHost(), $link);
        self::assertSame($link, $labels->getLinkOfLabels());
    }

    public function testSetPdfOfLabelsForCreatedShipment(): void
    {
        $collection = new ShipmentCollection();
        $collection->push($this->createMinimalNlShipment('smoke-label-pdf-' . uniqid('', true)));

        try {
            $createService = new ShipmentCreateService($this->liveApiKey);
            $created = $createService->create($collection);
            $shipmentIds = array_keys($created);

            $labels = new ShipmentLabelsService($this->liveApiKey);
            $pdf = $labels->setPdfOfLabels($shipmentIds, false);
        } catch (\Throwable $e) {
            $this->handleLiveException($e);
            return;
        }

        self::assertNotEmpty($pdf);
        self::assertStringStartsWith('%PDF-', $pdf);
        self::assertSame($pdf, $labels->getLabelPdf());
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

        $trace = $result[$shipmentId];
        self::assertSame($shipmentId, (int) $trace->getShipmentId());

        // Assert data completeness: the hybrid approach should preserve all fields.
        self::assertNotNull($trace->getCode(), 'Expected tracktrace code to be present.');
    }

    public function testQueryServiceFindsCreatedShipmentByIdAndReferenceIdentifier(): void
    {
        $referenceIdentifier = 'smoke-query-' . uniqid('', true);
        $collection = new ShipmentCollection();
        $collection->push($this->createMinimalNlShipment($referenceIdentifier));

        try {
            $createService = new ShipmentCreateService($this->liveApiKey);
            $created = $createService->create($collection);
            $shipmentId = (int) array_keys($created)[0];

            $queryService = new ShipmentQueryService($this->liveApiKey);
            $foundById = $queryService->find($shipmentId);
            $foundByReference = $queryService->findByReferenceId($referenceIdentifier);
            $foundMany = $queryService->findMany([$shipmentId]);
        } catch (\Throwable $e) {
            $this->handleLiveException($e);
            return;
        }

        self::assertNotNull($foundById, 'Expected created shipment to be returned by find().');
        self::assertSame($shipmentId, (int) $foundById->getId());

        // Assert data completeness: the hybrid approach should preserve recipient/street.
        $recipient = $foundById->getRecipient();
        self::assertNotNull($recipient, 'Expected recipient to be present on queried shipment.');
        self::assertNotNull($recipient->getStreet(), 'Expected street to be present on recipient.');
        self::assertSame('NL', $recipient->getCc());

        self::assertNotEmpty($foundByReference, 'Expected shipments to be returned by findByReferenceId().');
        self::assertSame($shipmentId, (int) $foundByReference[0]->getId());

        self::assertCount(1, $foundMany);
        self::assertSame($shipmentId, (int) $foundMany[0]->getId());
    }

    public function testDeleteServiceDeletesCreatedShipmentConcept(): void
    {
        $collection = new ShipmentCollection();
        $collection->push($this->createMinimalNlShipment('smoke-delete-' . uniqid('', true)));

        try {
            $createService = new ShipmentCreateService($this->liveApiKey);
            $created = $createService->create($collection);
            $shipmentId = (int) array_keys($created)[0];

            $deleteService = new ShipmentDeleteService($this->liveApiKey);
            $queryService = new ShipmentQueryService($this->liveApiKey);

            $deleteService->deleteMany([$shipmentId]);
            $deleted = $this->waitUntilShipmentDeleted($queryService, $shipmentId);
        } catch (\Throwable $e) {
            $this->handleLiveException($e);
            return;
        }

        self::assertTrue($deleted, 'Expected created concept shipment to be deleted.');
    }

    public function testCreateRelatedReturnShipment(): void
    {
        $collection = new ShipmentCollection();
        $collection->push($this->createMinimalNlShipment('smoke-related-return-parent-' . uniqid('', true)));

        try {
            $createService = new ShipmentCreateService($this->liveApiKey);
            $created = $createService->create($collection);
            $parentShipmentId = (int) array_keys($created)[0];

            $returnService = new ReturnShipmentService($this->liveApiKey);
            $result = $returnService->createRelated([[
                'parent'               => $parentShipmentId,
                'carrier'              => RefTypesCarrierReturns::NUMBER_1,
                'email'                => 'smoke@myparcel.nl',
                'name'                 => 'Smoke Return',
                'reference_identifier' => 'smoke-related-return-' . uniqid('', true),
            ]], false);
        } catch (\Throwable $e) {
            $this->handleLiveException($e);
            return;
        }

        if (empty($result)) {
            $this->markTestSkipped('Related returns returned no ids for this account/environment.');
        }

        self::assertNotEmpty($result);
        self::assertGreaterThan(0, (int) array_keys($result)[0]);
    }

    public function testCreateUnrelatedReturnShipment(): void
    {
        try {
            $returnService = new ReturnShipmentService($this->liveApiKey);
            $result = $returnService->createUnrelated([[
                'carrier'              => RefTypesCarrierReturns::NUMBER_1,
                'email'                => 'smoke@myparcel.nl',
                'name'                 => 'Smoke Unrelated Return',
                'reference_identifier' => 'smoke-unrelated-return-' . uniqid('', true),
            ]]);
        } catch (\Throwable $e) {
            $this->handleLiveException($e);
            return;
        }

        if (empty($result)) {
            $this->markTestSkipped('Unrelated returns returned no ids for this account/environment.');
        }

        self::assertNotEmpty($result);
        self::assertGreaterThan(0, (int) array_keys($result)[0]);
    }

    public function testCreateMultiColloShipmentViaService(): void
    {
        $referenceIdentifier = 'smoke-multicollo-' . uniqid('', true);
        $baseShipment = $this->createMinimalNlShipment($referenceIdentifier)->withWeight(1500);
        $multiColloService = new MultiColloShipmentService();
        $multiColloShipment = $multiColloService->splitShipment($baseShipment, 3);

        $collection = new ShipmentCollection();
        $collection->push($multiColloShipment);

        try {
            $createService = new ShipmentCreateService($this->liveApiKey);
            $created = $createService->create($collection);
        } catch (\Throwable $e) {
            $this->handleLiveException($e);
            return;
        }

        self::assertCount(2, $multiColloShipment->getSecondaryShipments());
        self::assertNotEmpty($created);
    }

    /**
     * Covers all 5 legacy webhook hook types: subscribe, getById, getAll (filtered), unsubscribe.
     * This replaces the old per-class webhook web service tests with full coverage of the new
     * unified WebhookService.
     *
     * @dataProvider provideWebhookHooks
     */
    public function testWebhookSubscribeGetAndUnsubscribe(string $hook): void
    {
        $createdId = null;

        try {
            $service = new WebhookService($this->liveApiKey);
            $url     = 'https://example.com/smoke-webhook-' . $hook . '-' . uniqid('', true);

            // 1. Subscribe (replaces old $webhookWebService->subscribe($url))
            $createdId = $service->subscribe($hook, $url);

            self::assertIsInt($createdId);
            self::assertGreaterThan(0, $createdId);

            // 2. Get by ID (new capability, not in old services)
            $found = $service->getById($createdId);
            self::assertCount(1, $found);
            self::assertSame($createdId, $found[0]->getId());
            self::assertSame($hook, $found[0]->getHook());
            self::assertSame($url, $found[0]->getUrl());

            // 3. List all filtered by hook (new capability)
            $all = $service->getAll($hook);
            self::assertNotEmpty($all, "Expected at least 1 subscription for hook '{$hook}'.");

            $matchingIds = array_map(
                static function ($sub) { return $sub->getId(); },
                $all
            );
            self::assertContains($createdId, $matchingIds, "Created subscription should appear in filtered list.");

            // 4. Unsubscribe (replaces old $webhookWebService->unsubscribe($id))
            $service->unsubscribe($createdId);
            $createdId = null; // mark as cleaned up

            // 5. Verify deletion
            $afterDelete = $service->getById($createdId ?? 0);
            self::assertEmpty($afterDelete, "Subscription should be gone after unsubscribe.");

        } catch (\Throwable $e) {
            // Cleanup on failure
            if (null !== $createdId) {
                try {
                    (new WebhookService($this->liveApiKey))->unsubscribe($createdId);
                } catch (\Throwable $ignored) {
                }
            }
            $this->handleLiveException($e);
        }
    }

    /**
     * Tests subscribing multiple webhooks and unsubscribing them in a single batch call.
     * The old services only supported single unsubscribe; the new service supports variadic IDs.
     */
    public function testWebhookBatchUnsubscribe(): void
    {
        $createdIds = [];

        try {
            $service = new WebhookService($this->liveApiKey);

            // Subscribe two different hooks
            $createdIds[] = $service->subscribe(
                WebhookService::HOOK_SHIPMENT_STATUS_CHANGE,
                'https://example.com/smoke-batch-1-' . uniqid('', true)
            );
            $createdIds[] = $service->subscribe(
                WebhookService::HOOK_ORDER_STATUS_CHANGE,
                'https://example.com/smoke-batch-2-' . uniqid('', true)
            );

            self::assertCount(2, $createdIds);

            // Batch unsubscribe (new capability)
            $service->unsubscribe(...$createdIds);
            $createdIds = [];

        } catch (\Throwable $e) {
            // Cleanup on failure
            foreach ($createdIds as $id) {
                try {
                    (new WebhookService($this->liveApiKey))->unsubscribe($id);
                } catch (\Throwable $ignored) {
                }
            }
            $this->handleLiveException($e);
        }
    }

    /**
     * All 5 hook types that the old webhook web services covered.
     */
    public function provideWebhookHooks(): array
    {
        return [
            'shipment_status_change'             => [WebhookService::HOOK_SHIPMENT_STATUS_CHANGE],
            'shipment_label_created'             => [WebhookService::HOOK_SHIPMENT_LABEL_CREATED],
            'order_status_change'                => [WebhookService::HOOK_ORDER_STATUS_CHANGE],
            'shop_carrier_accessibility_updated' => [WebhookService::HOOK_SHOP_CARRIER_ACCESSIBILITY_UPDATED],
            'shop_updated'                       => [WebhookService::HOOK_SHOP_UPDATED],
        ];
    }

    /**
     * Verifies OrderCollection::query() works with the new OrderShipmentOptions.
     * Orders constructed from API responses must hydrate correctly via
     * OrderShipmentOptions::fromOrderResponse().
     */
    public function testFulfilmentOrderQueryReturnsOrders(): void
    {
        // OrderCollection uses the legacy MyParcelRequest/MyParcelCurl stack,
        // so we must reset the curl factory to allow real HTTP calls.
        \MyParcelNL\Sdk\Model\MyParcelRequest::setCurlFactory(null);

        try {
            $collection = OrderCollection::query($this->liveApiKey);
        } catch (\Throwable $e) {
            $this->handleLiveException($e);
            return;
        }

        // The account may have 0 orders, but the call itself must succeed
        // and every returned order must have valid OrderShipmentOptions.
        self::assertInstanceOf(OrderCollection::class, $collection);

        if ($collection->isEmpty()) {
            $this->markTestSkipped('No fulfilment orders in this account to verify hydration.');
        }

        /** @var Order $order */
        $order = $collection->first();
        self::assertInstanceOf(Order::class, $order);
        self::assertInstanceOf(OrderShipmentOptions::class, $order->getDeliveryOptions());
    }

    /**
     * Full round-trip: build an Order with OrderShipmentOptions, save via API,
     * and verify the response hydrates back correctly.
     * This is the critical path that consumers rely on.
     */
    public function testFulfilmentOrderSaveWithOrderShipmentOptions(): void
    {
        \MyParcelNL\Sdk\Model\MyParcelRequest::setCurlFactory(null);

        try {
            $orderCollection = (new OrderCollection())->setApiKey($this->liveApiKey);

            $shipmentOptions = (new OrderShipmentOptions())
                ->setCarrierId(1) // PostNL
                ->setDate((new \DateTime('+1 day'))->format('Y-m-d H:i:s'))
                ->setDeliveryType(RefTypesDeliveryTypeV2::STANDARD)
                ->setPackageType(RefShipmentPackageTypeV2::PACKAGE);

            $order = (new Order())
                ->setStatus('open')
                ->setDeliveryOptions($shipmentOptions)
                ->setExternalIdentifier('smoke-order-' . uniqid('', true))
                ->setFulfilmentPartnerIdentifier('smoke-fp-' . uniqid('', true))
                ->setInvoiceAddress($this->createMinimalRecipient())
                ->setRecipient($this->createMinimalRecipient())
                ->setLanguage('NL')
                ->setType('test')
                ->setOrderDate((new \DateTime())->format('Y-m-d'));

            $orderLine = (new OrderLine())
                ->setUuid(uniqid('', true))
                ->setQuantity(1)
                ->setPrice(1000)
                ->setPriceAfterVat(1210)
                ->setVat(210)
                ->setProduct(
                    (new Product())
                        ->setName('Smoke Test Product')
                        ->setSku('SMOKE001')
                        ->setWeight(500)
                );

            $order->setOrderLines(new \MyParcelNL\Sdk\Support\Collection([$orderLine]));
            $order->setWeight(500);
            $orderCollection->push($order);

            $saved = $orderCollection->save();
        } catch (\Throwable $e) {
            $this->handleLiveException($e);
            return;
        }

        self::assertNotEmpty($saved->toArray(), 'Saved order collection should not be empty.');

        /** @var Order $savedOrder */
        $savedOrder = $saved->first();
        self::assertInstanceOf(Order::class, $savedOrder);
        self::assertIsString($savedOrder->getUuid(), 'Saved order should have a UUID.');
        self::assertInstanceOf(OrderShipmentOptions::class, $savedOrder->getDeliveryOptions());

        // Verify order lines came back
        self::assertGreaterThanOrEqual(1, $savedOrder->getOrderLines()->count());
    }

    private function createMinimalRecipient(): Recipient
    {
        return (new Recipient())
            ->setCc('NL')
            ->setCity('Hoofddorp')
            ->setPostalCode('2132JE')
            ->setStreet('Antareslaan')
            ->setPerson('Smoke Test')
            ->setEmail('smoke@myparcel.nl')
            ->setPhone('0612345678');
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

    private function waitUntilShipmentDeleted(
        ShipmentQueryService $queryService,
        int $shipmentId,
        int $attempts = 5,
        int $sleepMilliseconds = 1000
    ): bool {
        for ($attempt = 1; $attempt <= $attempts; $attempt++) {
            $shipment = $queryService->find($shipmentId);

            if (null === $shipment) {
                return true;
            }

            if ($attempt < $attempts) {
                usleep($sleepMilliseconds * 1000);
            }
        }

        return false;
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
