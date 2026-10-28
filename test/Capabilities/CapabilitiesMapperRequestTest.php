<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Capabilities;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2Direction;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedCarrierV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentLocationTypeV2;
use MyParcelNL\Sdk\Client\Jane\CoreApiModels;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesMapper;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesRequest;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

/**
 * The tests read the request as the API gets it: with the camelCase field names of the v2 capabilities API.
 */
final class CapabilitiesMapperRequestTest extends TestCase
{
    public function testMapToCoreApiSetsAllProvidedFields(): void
    {
        $req = CapabilitiesRequest::forCountry('NL')
            ->withShopId(42)
            ->withDeliveryType('STANDARD_DELIVERY')
            ->withCarrier(RefCapabilitiesSharedCarrierV2::Postnl->value)
            ->withPackageType('PACKAGE')
            ->withDirection(CapabilitiesPostCapabilitiesRequestV2Direction::Outbound->value)
            ->withPickup([
                'location' => [
                    'type' => RefShipmentLocationTypeV2::Retail->value,
                ],
            ]);

        $coreReq = (new CapabilitiesMapper())->mapToCoreApi($req);

        $this->assertJsonStringEqualsJsonString(
            '{"shopId":42,"recipient":{"countryCode":"NL"},"carrier":"POSTNL","packageType":"PACKAGE",'
            . '"deliveryType":"STANDARD_DELIVERY","direction":"OUTBOUND","pickup":{"location":{"type":"RETAIL"}}}',
            self::body($coreReq)
        );
        $this->assertSame(RefCapabilitiesSharedCarrierV2::Postnl, $coreReq->carrier);
    }

    public function testMapToCoreApiKeepsAValueTheSpecDoesNotKnow(): void
    {
        $coreReq = (new CapabilitiesMapper())->mapToCoreApi(
            CapabilitiesRequest::forCountry('NL')->withCarrier('FUTURE_CARRIER')
        );

        $this->assertSame('FUTURE_CARRIER', $coreReq->carrier);
    }

    public function testMapToCoreApiAllowsMinimalPayload(): void
    {
        $coreReq = (new CapabilitiesMapper())->mapToCoreApi(CapabilitiesRequest::forCountry('BE'));

        $this->assertJsonStringEqualsJsonString('{"recipient":{"countryCode":"BE"}}', self::body($coreReq));
    }

    public function testMapToCoreApiMapsSenderAndPhysicalProperties(): void
    {
        $req = CapabilitiesRequest::forCountry('DE')
            ->withSender(['country_code' => 'DE', 'is_business' => true])
            ->withPhysicalProperties([
                'height' => ['value' => 10, 'unit' => 'cm'],
                'length' => ['value' => 20, 'unit' => 'cm'],
                'weight' => ['value' => 250, 'unit' => 'g'],
                'width'  => ['value' => 15, 'unit' => 'cm'],
                'volume' => ['value' => 3, 'unit' => 'l'],
            ]);

        $coreReq = (new CapabilitiesMapper())->mapToCoreApi($req);

        $this->assertJsonStringEqualsJsonString('{"countryCode":"DE","isBusiness":true}', self::body($coreReq->sender));
        $this->assertJsonStringEqualsJsonString(
            '{"height":{"value":10,"unit":"cm"},"length":{"value":20,"unit":"cm"},'
            . '"weight":{"value":250,"unit":"g"},"width":{"value":15,"unit":"cm"}}',
            self::body($coreReq->physicalProperties)
        );
    }

    /**
     * CapabilitiesRequest::fromShipment() gives the weight as a float, such as 500.0. The spec types it as an integer.
     */
    public function testMapToCoreApiSendsAWholeFloatAsAnInteger(): void
    {
        $req = CapabilitiesRequest::forCountry('NL')
            ->withPhysicalProperties(['weight' => ['value' => 500.0, 'unit' => 'g']]);

        $coreReq = (new CapabilitiesMapper())->mapToCoreApi($req);

        $this->assertSame('{"weight":{"value":500,"unit":"g"}}', self::body($coreReq->physicalProperties));
    }

    public function testMapToCoreApiCreatesSenderObjectEvenWhenNoKnownFieldsArePresent(): void
    {
        $req = CapabilitiesRequest::forCountry('FR')
            ->withSender(['invalid_key' => 'value']);

        $coreReq = (new CapabilitiesMapper())->mapToCoreApi($req);

        $this->assertSame('{"recipient":{"countryCode":"FR"},"sender":{}}', self::body($coreReq));
    }

    public function testMapToCoreApiMapsOptionsKnownAndFallbackAndNormalizesNull(): void
    {
        $req = CapabilitiesRequest::forCountry('NL')
            ->withOptions([
                // Known mapping (key differs from the v2 name)
                'signature' => null,

                // Fallback mapping (key matches the v2 name)
                'saturday_delivery' => null,

                // Value should pass through unchanged (array/object)
                'insurance' => ['amount' => 100],
            ]);

        $options = (new CapabilitiesMapper())->mapToCoreApi($req)->options;

        $this->assertJsonStringEqualsJsonString(
            '{"requiresSignature":{},"saturdayDelivery":{},"insurance":{"amount":100}}',
            self::body($options)
        );
    }

    /**
     * Regression test for the three semantic aliases that mapOptions() used to drop silently.
     */
    public function testMapToCoreApiMapsPreviouslyDroppedOptionAliases(): void
    {
        $request = CapabilitiesRequest::forCountry('NL')
            ->withOptions([
                'cash_on_delivery'         => null,
                'drop_off_at_postal_point' => null,
                'extra_assurance'          => null,
            ]);

        $coreOptions = (new CapabilitiesMapper())->mapToCoreApi($request)->options;

        $this->assertInstanceOf(\stdClass::class, $coreOptions->requiresCashOnDelivery);
        $this->assertInstanceOf(\stdClass::class, $coreOptions->deliverAtPostalPoint);
        $this->assertInstanceOf(\stdClass::class, $coreOptions->additionalInsurance);
    }

    public function testMapToCoreApiMapsNoTrackingButExplicitlyIgnoresTracked(): void
    {
        $noTracking = new \stdClass();
        $tracked    = new \stdClass();
        $request    = CapabilitiesRequest::forCountry('NL')
            ->withOptions([
                'no_tracking' => $noTracking,
                // Deliberately comes second: it must not overwrite no_tracking by inversion.
                'tracked'     => $tracked,
            ]);

        $coreOptions = (new CapabilitiesMapper())->mapToCoreApi($request)->options;

        $this->assertSame($noTracking, $coreOptions->noTracking);

        $trackedOnly = CapabilitiesRequest::forCountry('NL')
            ->withOptions(['tracked' => new \stdClass()]);

        $this->assertSame(
            '{"recipient":{"countryCode":"NL"},"options":{}}',
            self::body((new CapabilitiesMapper())->mapToCoreApi($trackedOnly))
        );
    }

    public function testMapToCoreApiKeepsAcceptingGeneratedV2JsonOptionNames(): void
    {
        $signature = new \stdClass();
        $sameDay   = new \stdClass();
        $request   = CapabilitiesRequest::forCountry('NL')
            ->withOptions([
                'requiresSignature' => $signature,
                'sameDayDelivery'   => $sameDay,
            ]);

        $coreOptions = (new CapabilitiesMapper())->mapToCoreApi($request)->options;

        $this->assertSame($signature, $coreOptions->requiresSignature);
        $this->assertSame($sameDay, $coreOptions->sameDayDelivery);
    }

    /**
     * The original setter lookup accepted case variants and spaces as well as snake_case.
     */
    public function testMapToCoreApiPreservesOptionSetterNameVariants(): void
    {
        foreach (['SAME_DAY_DELIVERY', 'SameDayDelivery', 'samedaydelivery', 'same day delivery'] as $key) {
            $value   = new \stdClass();
            $request = CapabilitiesRequest::forCountry('NL')->withOptions([$key => $value]);
            $options = (new CapabilitiesMapper())->mapToCoreApi($request)->options;

            $this->assertSame($value, $options->sameDayDelivery, $key);
        }
    }

    public function testMapToCoreApiIgnoresUnknownOptionKeysWithoutCrashing(): void
    {
        $req = CapabilitiesRequest::forCountry('NL')
            ->withOptions([
                'some_future_option' => null,
                'another_future_option' => ['foo' => 'bar'],
            ]);

        $this->assertSame(
            '{"recipient":{"countryCode":"NL"},"options":{}}',
            self::body((new CapabilitiesMapper())->mapToCoreApi($req))
        );
    }

    public function testMapToCoreApiDoesNotConvertFalseOrZeroToStdClass(): void
    {
        $req = CapabilitiesRequest::forCountry('NL')
            ->withOptions([
                // These values must remain as-is, not be replaced by stdClass().
                'signature' => false,
                'insurance' => 0,
            ]);

        $options = (new CapabilitiesMapper())->mapToCoreApi($req)->options;

        $this->assertFalse($options->requiresSignature);
        $this->assertSame(0, $options->insurance);
    }

    /**
     * The JSON that the client sends for the model.
     */
    private static function body(object $model): string
    {
        return json_encode(CoreApiModels::toArray($model), JSON_THROW_ON_ERROR);
    }
}
