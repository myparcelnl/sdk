<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Ecommerce;

use MyParcelNL\Sdk\Services\Ecommerce\PushResult;
use MyParcelNL\Sdk\Services\Ecommerce\PushResultReader;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

/**
 * The reader turns the answer of a push into one result per order or product.
 */
final class PushResultReaderTest extends TestCase
{
    private const ACCEPTED = '{"status":202}';

    private const REFUSED = <<<'JSON'
        {
            "type": "urn:problem:client",
            "status": 400,
            "title": "Bad Request",
            "detail": "The order is not valid.",
            "instance": "urn:uuid:6f1e6f3e-0000-4000-8000-000000000000",
            "errors": [{"detail": "The status is not allowed.", "pointer": "/body/1"}]
        }
        JSON;

    private const SERVER_ERROR = <<<'JSON'
        {
            "type": "urn:problem:server",
            "status": 500,
            "title": "Internal Server Error",
            "detail": "Something went wrong.",
            "instance": "urn:uuid:6f1e6f3e-0000-4000-8000-000000000001"
        }
        JSON;

    public function testAnAcceptedItemReadsAsAccepted(): void
    {
        $result = $this->read(self::ACCEPTED)[0];

        self::assertTrue($result->isAccepted());
        self::assertSame(PushResult::STATUS_ACCEPTED, $result->getStatus());
        self::assertNull($result->getType());
        self::assertSame([], $result->getErrors());
    }

    public function testARefusedItemCarriesTheProblem(): void
    {
        $result = $this->read(self::REFUSED)[0];

        self::assertFalse($result->isAccepted());
        self::assertSame(400, $result->getStatus());
        self::assertSame('urn:problem:client', $result->getType());
        self::assertSame('Bad Request', $result->getTitle());
        self::assertSame('The order is not valid.', $result->getDetail());
        self::assertSame([['detail' => 'The status is not allowed.', 'pointer' => '/body/1']], $result->getErrors());
    }

    public function testAServerErrorItemIsNotAcceptedAndHasNoErrors(): void
    {
        $result = $this->read(self::SERVER_ERROR)[0];

        self::assertFalse($result->isAccepted());
        self::assertSame(500, $result->getStatus());
        self::assertSame([], $result->getErrors());
    }

    public function testTheResultsKeepTheOrderOfTheAnswer(): void
    {
        $results = $this->read(self::ACCEPTED, self::REFUSED, self::ACCEPTED);

        self::assertSame(
            [true, false, true],
            array_map(static fn (PushResult $result): bool => $result->isAccepted(), $results)
        );
    }

    public function testAnItemWithoutAStatusIsNotAccepted(): void
    {
        // MyParcel did not say that it accepted the item, so the SDK does not say it either.
        $result = $this->read('{}')[0];

        self::assertFalse($result->isAccepted());
        self::assertNull($result->getStatus());
    }

    public function testAnEmptyAnswerGivesNoResults(): void
    {
        self::assertSame([], $this->read());
    }

    /**
     * Read an answer with these items, decoded as the generated client decodes it.
     *
     * @return \MyParcelNL\Sdk\Services\Ecommerce\PushResult[]
     */
    private function read(string ...$items): array
    {
        return (new PushResultReader())->read(json_decode('[' . implode(',', $items) . ']', false, 512, JSON_THROW_ON_ERROR));
    }
}
