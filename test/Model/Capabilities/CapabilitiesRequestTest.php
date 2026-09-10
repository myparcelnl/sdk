<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Capabilities;

use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesMapper;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesRequest;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class CapabilitiesRequestTest extends TestCase
{
    public function testGetUnsupportedOptionsReturnsEmptyListWithoutOptions(): void
    {
        $request = CapabilitiesRequest::forCountry('NL');

        $this->assertSame([], $request->getUnsupportedOptions());
        $this->assertSame([], $request->withOptions([])->getUnsupportedOptions());
    }

    public function testGetUnsupportedOptionsReportsNamesBeforeMappingWithoutChangingTheRequest(): void
    {
        $input = [
            'signature'         => null,
            'tracked'           => null,
            'unknown_option'    => (object) ['enabled' => true],
            'SAME_DAY_DELIVERY' => null,
            'noTracking'        => null,
        ];
        $request = CapabilitiesRequest::forCountry('NL')->withOptions($input);

        $this->assertSame(['tracked', 'unknown_option'], $request->getUnsupportedOptions());
        $this->assertSame($input, $request->getOptions());

        $options = (new CapabilitiesMapper())->mapToCoreApi($request)->getOptions();

        $this->assertNotNull($options->getRequiresSignature());
        $this->assertNotNull($options->getSameDayDelivery());
        $this->assertNotNull($options->getNoTracking());
    }

    public function testGetUnsupportedOptionsUpdatesWhenTheCallerReplacesOptions(): void
    {
        $request = CapabilitiesRequest::forCountry('NL')->withOptions(['tracked' => null]);
        $updated = $request->withOptions(['no_tracking' => null]);

        $this->assertSame(['tracked'], $request->getUnsupportedOptions());
        $this->assertSame([], $updated->getUnsupportedOptions());
    }
}
