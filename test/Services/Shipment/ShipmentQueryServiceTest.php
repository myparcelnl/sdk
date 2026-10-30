<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Shipment;

use InvalidArgumentException;
use MyParcelNL\Sdk\Model\Shipment\FoundShipment;
use MyParcelNL\Sdk\Client\Jane\JaneClientFactory;
use MyParcelNL\Sdk\Services\Shipment\ShipmentQueryService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

final class ShipmentQueryServiceTest extends TestCase
{
    /**
     * @var array<int, array{method: string, url: string, headers: string[]}>
     */
    private array $requests = [];

    public function testFindManyReturnsEmptyArrayForEmptyInput(): void
    {
        self::assertSame([], $this->createService([])->findMany([]));
        self::assertSame([], $this->requests);
    }

    public function testFindManyBuildsRequestAndParsesResponse(): void
    {
        $result = $this->createService([['id' => 10], ['id' => 20]])->findMany([10, 20]);

        self::assertSame('GET', $this->requests[0]['method']);
        self::assertSame('https://api.myparcel.nl/shipments/10%3B20', $this->requests[0]['url']);
        self::assertCount(2, $result);
        self::assertSame(10, $result[0]->getId());
        self::assertSame(20, $result[1]->getId());
    }

    public function testFindReturnsFirstShipmentOrNull(): void
    {
        $result = $this->createService([['id' => 42]])->find(42);

        self::assertInstanceOf(FoundShipment::class, $result);
        self::assertSame(42, $result->getId());
    }

    public function testFindReturnsNullWhenNoShipmentExists(): void
    {
        self::assertNull($this->createService([])->find(999));
    }

    public function testQueryPassesFiltersAndTheDefaultPageSize(): void
    {
        $result = $this->createService([['id' => 123]])->query([
            'barcode'              => '3SMYPA1234567',
            'reference_identifier' => 'order-100',
            'page'                 => 2,
            'sort'                 => 'desc',
            'status'               => 1,
        ]);

        self::assertSame(
            [
                'barcode'              => '3SMYPA1234567',
                'page'                 => '2',
                'reference_identifier' => 'order-100',
                'size'                 => '300',
                'sort'                 => 'desc',
                'status'               => '1',
            ],
            $this->queryOfRequest(0)
        );
        self::assertCount(1, $result);
    }

    public function testQueryKeepsAPageSizeThatTheCallerSets(): void
    {
        $this->createService([])->query(['size' => 50]);

        self::assertSame(['size' => '50'], $this->queryOfRequest(0));
    }

    public function testQueryThrowsOnUnknownParameter(): void
    {
        $service = $this->createService([]);

        try {
            $service->query(['barcod' => '3SMYPA1234567']);
            self::fail('An unknown query parameter must throw.');
        } catch (InvalidArgumentException $exception) {
            self::assertStringContainsString('barcod', $exception->getMessage());
        }

        self::assertSame([], $this->requests);
    }

    public function testFindByReferenceIdLeavesThePageSizeToTheApi(): void
    {
        $result = $this->createService([['id' => 1001], ['id' => 1002], ['id' => 1003]])->findByReferenceId('ref-a');

        self::assertSame(['reference_identifier' => 'ref-a'], $this->queryOfRequest(0));
        self::assertSame([1001, 1002, 1003], array_map(static fn (FoundShipment $shipment) => $shipment->getId(), $result));
    }

    public function testSendsTheUserAgentThatIsSetAfterTheConstructor(): void
    {
        $service = $this->createService([]);
        $service->setUserAgentForProposition('Magento', '2.4.7');

        $service->find(1);
        $service->query();

        foreach ($this->requests as $request) {
            $userAgents = preg_grep('/^User-Agent: /i', $request['headers']);

            self::assertCount(1, $userAgents);
            self::assertStringStartsWith('User-Agent: Magento/2.4.7 MyParcelNL-SDK/', reset($userAgents));
        }
    }

    public function testRecipientIsRead(): void
    {
        $result = $this->createService([[
            'id'        => 1,
            'recipient' => [
                'cc'          => 'NL',
                'street'      => 'Antareslaan',
                'number'      => '31',
                'postal_code' => '2132JE',
                'city'        => 'Hoofddorp',
                'person'      => 'Test Person',
            ],
        ]])->find(1);

        self::assertNotNull($result);
        self::assertSame('Antareslaan', $result->getRecipient()->street);
        self::assertSame('NL', $result->getRecipient()->cc);
        self::assertSame('31', $result->getRecipient()->number);
        self::assertSame('2132JE', $result->getRecipient()->postalCode);
        self::assertSame('Hoofddorp', $result->getRecipient()->city);
        self::assertSame('Test Person', $result->getRecipient()->person);
    }

    public function testAGetterReturnsNullForAFieldThatTheApiDidNotSend(): void
    {
        $result = $this->createService([['id' => 1]])->find(1);

        self::assertNotNull($result);
        self::assertNull($result->getBarcode());
        self::assertNull($result->getRecipient());
        self::assertFalse($result->getDelayed());
    }

    /**
     * The spec allows a region of two upper-case letters or `Wereld`.
     *
     * @return array<string, array{string}>
     */
    public function provideRegions(): array
    {
        return ['EU' => ['EU'], 'NL' => ['NL'], 'BE' => ['BE'], 'Wereld' => ['Wereld']];
    }

    /**
     * @dataProvider provideRegions
     */
    public function testAFoundShipmentEncodesToJsonWithEveryRegion(string $region): void
    {
        $result = $this->createService([['id' => 1, 'region' => $region]])->find(1);

        self::assertNotNull($result);
        self::assertSame($region, $result->getRegion());

        $json = json_decode(json_encode($result, JSON_THROW_ON_ERROR), true);

        self::assertSame(1, $json['id']);
        self::assertSame($region, $json['region']);
    }

    /**
     * @param array<int, array<string, mixed>> $shipments The shipments in the response.
     */
    private function createService(array $shipments): ShipmentQueryService
    {
        $body = json_encode(['data' => ['shipments' => $shipments, 'results' => count($shipments)]], JSON_THROW_ON_ERROR);

        $httpClient = new MockHttpClient(function (string $method, string $url, array $options) use ($body): MockResponse {
            $this->requests[] = ['method' => $method, 'url' => $url, 'headers' => $options['headers']];

            return new MockResponse($body, ['http_code' => 200, 'response_headers' => ['content-type' => 'application/json']]);
        });

        return new ShipmentQueryService($this->getApiKey(), JaneClientFactory::coreApi('my-api-key', null, null, [], $httpClient));
    }

    /**
     * @return array<string, string> The query parameters of a request, sorted by name.
     */
    private function queryOfRequest(int $index): array
    {
        parse_str((string) parse_url($this->requests[$index]['url'], PHP_URL_QUERY), $query);
        ksort($query);

        return $query;
    }
}
