<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipment;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsTrackTrace;
use MyParcelNL\Sdk\Model\Shipment\FoundShipment;
use MyParcelNL\Sdk\Model\TrackTrace\TrackTrace;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use ReflectionClass;
use ReflectionProperty;

/**
 * A regeneration can add a property to a Jane model. The read model then needs a getter for it.
 */
final class ReadModelGettersTest extends TestCase
{
    /**
     * @dataProvider provideReadModels
     *
     * @param class-string $readModel
     * @param class-string $janeModel
     */
    public function testEveryPropertyOfTheJaneModelHasAGetter(string $readModel, string $janeModel): void
    {
        $missing = $this->missingGetters($readModel, $janeModel);

        self::assertSame([], $missing, sprintf('Add these getters to %s: %s', $readModel, implode(', ', $missing)));
    }

    /**
     * @return array<string, array{class-string, class-string}>
     */
    public static function provideReadModels(): array
    {
        return [
            'FoundShipment' => [FoundShipment::class, ShipmentDefsShipment::class],
            'TrackTrace'    => [TrackTrace::class, ShipmentDefsTrackTrace::class],
        ];
    }

    /**
     * @param class-string $readModel
     * @param class-string $janeModel
     *
     * @return string[] The getters that the read model does not have, such as `getBarcode`.
     */
    private function missingGetters(string $readModel, string $janeModel): array
    {
        $missing = [];

        foreach ((new ReflectionClass($janeModel))->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $getter = 'get' . ucfirst($property->getName());

            if (! method_exists($readModel, $getter)) {
                $missing[] = $getter;
            }
        }

        return $missing;
    }
}
