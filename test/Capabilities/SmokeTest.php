<?php

namespace MyParcelNL\Sdk\Tests\Capabilities;

use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesMapper;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesRequest;
use MyParcelNL\Sdk\Services\CoreApi\HttpCapabilitiesClient;

final class CapabilitiesSmokeTest extends TestCase
{
    public function test_it_can_fetch_capabilities_for_nl(): void
    {
        // Zorg dat je API key beschikbaar is zoals de SDK ‘m leest (env/const),
        // en dat je factory 'bearer {base64apikey}' zet.

        $client = new HttpCapabilitiesClient(new CapabilitiesMapper());

        $req = new CapabilitiesRequest(
            null,     // shopId (optioneel)
            null,     // shippingMethod (optioneel)
            'POSTNL', // carrier (optioneel; kan ook null)
            'NL'      // countryCode (verplicht)
        );

        $res = $client->getCapabilities($req);

        // Simple rook-asserts
        $this->assertIsArray($res->packageTypes);
        $this->assertIsArray($res->deliveryTypes);
        $this->assertIsArray($res->shipmentOptions);

        // Eventueel wat extra sanity:
        // $this->assertNotEmpty($res->packageTypes);
    }
}
