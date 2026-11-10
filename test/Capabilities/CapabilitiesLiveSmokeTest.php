<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Capabilities;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\BadResponseException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostCapabilitiesClientErrorException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception\PostCapabilitiesServerErrorException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2;
use MyParcelNL\Sdk\Client\Jane\CoreApiModels;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesResponsesCapabilitiesV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem;
use MyParcelNL\Sdk\Services\Capabilities\CapabilitiesService;
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

        $this->service = new CapabilitiesService();
    }

    /**
     * Simple NL smoke test (country + optional shopId).
     *
     * @throws \Throwable
     */
    public function testFetchCapabilitiesNlMinimal(): void
    {
        $request = CoreApiModels::toModel(['recipient' => ['countryCode' => 'NL'], 'shopId' => 18], CapabilitiesPostCapabilitiesRequestV2::class);

        $response = $this->fetch(fn(): CapabilitiesResponsesCapabilitiesV2 => $this->service->get($request));

        // Minimal but real check
        $this->assertBasicShape($response);
        $this->assertNotEmpty($response->results[0]->packageTypes ?? [], 'Expected at least one package type for NL.');
    }

    /**
     * Live smoke using fromShipment() path (recipient + weight only).
     */
    public function testFromShipmentLiveNlMinimal(): void
    {
        $shipment = CoreApiModels::toModel(
            ['recipient' => ['cc' => 'NL'], 'physical_properties' => ['weight' => 500]],
            ShipmentPostShipmentsRequestV11DataShipmentsItem::class
        );

        $response = $this->fetch(fn(): CapabilitiesResponsesCapabilitiesV2 => $this->service->fromShipment($shipment));

        $this->assertBasicShape($response);
        $this->assertIsArray($response->results);
    }

    /**
     * Skip the test for a network error, a server error or rate limiting. Another error fails the test.
     *
     * @param callable(): CapabilitiesResponsesCapabilitiesV2 $call
     */
    private function fetch(callable $call): CapabilitiesResponsesCapabilitiesV2
    {
        try {
            return $call();
        } catch (TransportExceptionInterface $e) {
            $this->markTestSkipped('Skipping live smoke: network unavailable: ' . $e->getMessage());
        } catch (BadResponseException $e) {
            // A gateway error, such as a 502 with an HTML body, has no JSON content type, so the generated
            // endpoint throws this exception instead of a status exception.
            if (429 === $e->getCode() || $e->getCode() >= 500) {
                $this->markTestSkipped('Skipping live smoke due to transient API error: ' . $e->getMessage());
            }

            throw $e;
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
    private function assertBasicShape(CapabilitiesResponsesCapabilitiesV2 $response): void
    {
        $this->assertIsArray($response->results);

        foreach ($response->results as $result) {
            $this->assertIsArray($result->packageTypes ?? []);
            $this->assertIsArray($result->deliveryTypes ?? []);

            foreach (array_keys(isset($result->options) ? CoreApiModels::toArray($result->options) : []) as $option) {
                $this->assertMatchesRegularExpression('/^[a-z][A-Za-z_]*$/', $option);
            }
        }
    }
}
