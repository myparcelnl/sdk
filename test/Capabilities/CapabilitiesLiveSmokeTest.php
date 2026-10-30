<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Capabilities;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostCapabilitiesClientErrorException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostCapabilitiesServerErrorException;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesRequest;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesResponse;
use MyParcelNL\Sdk\Services\Capabilities\CapabilitiesService;
use MyParcelNL\Sdk\Services\CoreApi\HttpCapabilitiesClient;
use MyParcelNL\Sdk\Services\CoreApi\ShipmentApiFactory;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @group live
 *
 * Minimal live smoke test to verify the Capabilities endpoint works
 * with a real API key. This test does not validate business logic,
 * only that the Core API responds with the expected data structure.
 */
final class CapabilitiesLiveSmokeTest extends TestCase
{
    private CapabilitiesService $service;

    protected function setUp(): void
    {
        parent::setUp();

        // Skip if no API key is present
        $hasAnyKey = getenv('API_KEY') || getenv('API_KEY_NL') || getenv('API_KEY_BE');
        if (! $hasAnyKey) {
            $this->markTestSkipped('Skipping live smoke tests: no API key found in env (API_KEY / API_KEY_NL / API_KEY_BE).');
        }

        $this->service = new CapabilitiesService(
            new HttpCapabilitiesClient(null, ShipmentApiFactory::make(null, self::liveApiHost()))
        );
    }

    /**
     * Simple NL smoke test (country + optional shopId).
     *
     * @throws \Throwable
     */
    public function testFetchCapabilitiesNlMinimal(): void
    {
        $request = CapabilitiesRequest::forCountry('NL')->withShopId(18);

        $response = $this->fetch(fn(): CapabilitiesResponse => $this->service->get($request));

        // Minimal but real check
        $this->assertBasicShape($response);
        $this->assertNotEmpty($response->getPackageTypes(), 'Expected at least one package type for NL.');
    }

    /**
     * Live smoke using fromShipment() path (recipient + weight only).
     */
    public function testFromShipmentLiveNlMinimal(): void
    {
        $shipment = (new \MyParcelNL\Sdk\Model\Shipment\Shipment())
            ->setRecipient(['cc' => 'NL'])
            ->setPhysicalProperties(['weight' => 500]);

        $response = $this->fetch(fn(): CapabilitiesResponse => $this->service->fromShipment($shipment));

        $this->assertBasicShape($response);
        $this->assertIsArray($response->getPackageTypes());
    }

    /**
     * Skip the test for a network error, a server error or rate limiting. Another error fails the test.
     *
     * @param callable(): CapabilitiesResponse $call
     */
    private function fetch(callable $call): CapabilitiesResponse
    {
        try {
            return $call();
        } catch (TransportExceptionInterface $e) {
            $this->markTestSkipped('Skipping live smoke: network unavailable: ' . $e->getMessage());
        } catch (PostCapabilitiesServerErrorException $e) {
            $this->markTestSkipped('Skipping live smoke due to transient API error: ' . $e->getMessage());
        } catch (PostCapabilitiesClientErrorException $e) {
            if (429 === $e->getResponse()->getStatusCode()) {
                $this->markTestSkipped('Skipping live smoke due to rate limiting: ' . $e->getMessage());
            }

            throw $e;
        }
    }

    /**
     * Minimal shape validation.
     */
    private function assertBasicShape(CapabilitiesResponse $response): void
    {
        $this->assertIsArray($response->getPackageTypes());
        $this->assertIsArray($response->getDeliveryTypes());
        $this->assertIsArray($response->getTransactionTypes());

        // v11 returned the internal property names of the generated model, such as "\0*\0container".
        foreach ($response->getShipmentOptions() as $option) {
            $this->assertMatchesRegularExpression('/^[a-z][A-Za-z_]*$/', $option);
        }

        if (null !== $response->getCarrier()) {
            $this->assertIsString($response->getCarrier());
        }

        if (null !== $response->getColloMax()) {
            $this->assertIsInt($response->getColloMax());
        }
    }
}
