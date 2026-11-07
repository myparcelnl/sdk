<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Shipment;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesCarrier;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipment;
use MyParcelNL\Sdk\Client\Jane\CoreApiModels;
use MyParcelNL\Sdk\Client\Jane\JaneClientFactory;
use MyParcelNL\Sdk\Services\Shipment\ShipmentQueryService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use TypeError;

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
        self::assertSame(10, $result[0]->id);
        self::assertSame(20, $result[1]->id);
    }

    public function testFindReturnsTheFirstShipment(): void
    {
        $result = $this->createService([['id' => 42]])->find(42);

        self::assertInstanceOf(ShipmentDefsShipment::class, $result);
        self::assertSame(42, $result->id);
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

    public function testQueryLeavesOutAFilterWithANullValueOrAnEmptyString(): void
    {
        $this->createService([])->query(['barcode' => null, 'q' => '', 'reference_identifier' => 'order-1', 'size' => null]);

        self::assertSame(['reference_identifier' => 'order-1'], $this->queryOfRequest(0));
    }

    public function testQuerySendsTheValueOfAnEnumFilter(): void
    {
        $this->createService([])->query(['carrier_id' => RefTypesCarrier::Postnl]);

        self::assertSame(['carrier_id' => '1', 'size' => '300'], $this->queryOfRequest(0));
    }

    /**
     * The spec documents a `;`-separated string for `carrier_id` and `status`.
     */
    public function testQuerySendsAnArrayFilterAsASemicolonSeparatedString(): void
    {
        $this->createService([])->query(['carrier_id' => [RefTypesCarrier::Postnl, RefTypesCarrier::Dpd], 'status' => [1, 2]]);

        self::assertSame(['carrier_id' => '1;4', 'size' => '300', 'status' => '1;2'], $this->queryOfRequest(0));
    }

    /**
     * In v11, `false` left the filter out. Now the API gets the filter.
     */
    public function testQuerySendsFalseAsZero(): void
    {
        $this->createService([])->query(['delivered' => false]);

        self::assertSame(['delivered' => '0', 'size' => '300'], $this->queryOfRequest(0));
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

    public function testQueryThrowsForAValueWithATypeThatTheSpecDoesNotAllow(): void
    {
        $service = $this->createService([]);

        try {
            $service->query(['page' => '2']);
            self::fail('A string for an integer parameter must throw.');
        } catch (InvalidArgumentException $exception) {
            self::assertStringContainsString('page', $exception->getMessage());
        }

        self::assertSame([], $this->requests);
    }

    public function testFindByReferenceIdLeavesThePageSizeToTheApi(): void
    {
        $result = $this->createService([['id' => 1001], ['id' => 1002], ['id' => 1003]])->findByReferenceId('ref-a');

        self::assertSame(['reference_identifier' => 'ref-a'], $this->queryOfRequest(0));
        self::assertSame([1001, 1002, 1003], array_map(static fn (ShipmentDefsShipment $shipment): int => $shipment->id, $result));
    }

    /**
     * A client that the caller passes keeps its own User-Agent, also after setUserAgentForProposition().
     */
    public function testKeepsTheUserAgentOfTheClient(): void
    {
        $service = $this->createService([]);
        $service->setUserAgentForProposition('Magento', '2.4.7');

        $service->find(1);
        $service->query();

        foreach ($this->requests as $request) {
            self::assertSame(['User-Agent: MyPlugin/1.0'], array_values(preg_grep('/^User-Agent: /i', $request['headers'])));
        }
    }

    public function testANestedObjectIsReadAsAModelWithCamelCaseProperties(): void
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
        self::assertSame('Antareslaan', $result->recipient->street);
        self::assertSame('NL', $result->recipient->cc);
        self::assertSame('31', $result->recipient->number);
        self::assertSame('2132JE', $result->recipient->postalCode);
        self::assertSame('Hoofddorp', $result->recipient->city);
        self::assertSame('Test Person', $result->recipient->person);
    }

    public function testAnEnumFieldHoldsTheCaseOrTheRawValueThatTheSdkDoesNotKnow(): void
    {
        $service = $this->createService([['id' => 1, 'carrier_id' => 1, 'status' => 2], ['id' => 2, 'carrier_id' => 999, 'status' => 999]]);

        [$known, $unknown] = $service->findMany([1, 2]);

        self::assertSame(RefTypesCarrier::Postnl, $known->carrierId);
        self::assertSame(2, $known->status->value);
        self::assertSame(999, $unknown->carrierId);
        self::assertSame(999, $unknown->status);
    }

    public function testAFieldThatTheApiDidNotSendIsNotInitialized(): void
    {
        $result = $this->createService([['id' => 1]])->find(1);

        self::assertNotNull($result);
        self::assertFalse(isset($result->barcode));
        self::assertFalse(isset($result->recipient));
        self::assertFalse($result->delayed);
    }

    /**
     * The spec does not allow null for these fields. A TypeError is not an Exception.
     *
     * @return array<string, array{array<string, mixed>}>
     */
    public static function provideNullsThatTheSpecDoesNotAllow(): array
    {
        return [
            'barcode'                 => [['id' => 1, 'barcode' => null]],
            'created'                 => [['id' => 1, 'created' => null]],
            'street of the recipient' => [['id' => 1, 'recipient' => ['cc' => 'NL', 'street' => null]]],
            'package type'            => [['id' => 1, 'options' => ['package_type' => null]]],
        ];
    }

    /**
     * @dataProvider provideNullsThatTheSpecDoesNotAllow
     *
     * @param array<string, mixed> $shipment
     */
    public function testANullThatTheSpecDoesNotAllowThrowsATypeError(array $shipment): void
    {
        $service = $this->createService([$shipment]);

        $this->expectException(TypeError::class);

        $service->find(1);
    }

    public function testFindThrowsForABodyThatIsNotJson(): void
    {
        $service = $this->createService([], 'not json');

        $this->expectException(NotEncodableValueException::class);

        $service->find(1);
    }

    /**
     * A sample of the regions that the spec allows: two upper-case letters or `Wereld`.
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
    public function testAShipmentIsReadWithEachRegionOfTheSample(string $region): void
    {
        $result = $this->createService([['id' => 1, 'region' => $region]])->find(1);

        self::assertNotNull($result);
        self::assertSame($region, $result->region);
        self::assertSame($region, CoreApiModels::toArray($result)['region']);
    }

    /**
     * @param array<int, array<string, mixed>> $shipments The shipments in the response.
     * @param string|null                      $body      A body that replaces the encoded shipments.
     */
    private function createService(array $shipments, ?string $body = null): ShipmentQueryService
    {
        $body ??= json_encode(['data' => ['shipments' => $shipments, 'results' => count($shipments)]], JSON_THROW_ON_ERROR);

        $httpClient = new MockHttpClient(function (string $method, string $url, array $options) use ($body): MockResponse {
            $this->requests[] = ['method' => $method, 'url' => $url, 'headers' => $options['headers']];

            return new MockResponse($body, ['http_code' => 200, 'response_headers' => ['content-type' => 'application/json']]);
        });

        return new ShipmentQueryService($this->getApiKey(), JaneClientFactory::coreApi('my-api-key', null, 'MyPlugin/1.0', [], $httpClient));
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
