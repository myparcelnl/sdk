<?php

namespace MyParcelNL\Sdk\Model\Capabilities;

use MyParcel\CoreApi\Generated\Capabilities\Model\CapabilitiesRequest as CoreCapabilitiesRequest;
use MyParcel\CoreApi\Generated\Capabilities\Model\CapabilitiesResponse as CoreCapabilitiesResponse;

class CapabilitiesMapper
{
    public function mapToCoreApi(CapabilitiesRequest $sdk): CoreCapabilitiesRequest
    {
        return new CoreCapabilitiesRequest([
            'data' => [
                'capabilities' => [[
                                       'recipient' => [
                                           'cc' => $sdk->getCountryCode(),
                                       ],
                                   ]],
            ],
        ]);
    }


    public function mapFromCoreApi(CoreCapabilitiesResponse $core): CapabilitiesResponse
    {
        // veilige manier: zoek data->capabilities
        $data = method_exists($core, 'getData') ? $core->getData() : null;
        $caps = is_array($data) && isset($data['capabilities'])
            ? $data['capabilities']
            : (method_exists($data, 'getCapabilities') ? $data->getCapabilities() : []);

        $first = is_array($caps) ? ($caps[0] ?? null) : null;

        $packageTypes   = [];
        $deliveryTypes  = [];
        $shipmentOptions = [];

        if ($first) {
            // array of object-safe extract
            if (is_array($first)) {
                $packageTypes  = (array)($first['package_types'] ?? []);
                $deliveryTypes = (array)($first['delivery_types'] ?? []);

                $opts = $first['options'] ?? [];
                $shipmentOptions = is_array($opts) ? array_keys($opts) : array_keys((array)$opts);
            } else {
                // object met getters (kan per generator verschillen)
                if (method_exists($first, 'getPackageTypes')) {
                    $packageTypes = (array) $first->getPackageTypes();
                }
                if (method_exists($first, 'getDeliveryTypes')) {
                    $deliveryTypes = (array) $first->getDeliveryTypes();
                }
                if (method_exists($first, 'getOptions')) {
                    $opts = $first->getOptions();
                    $shipmentOptions = is_array($opts) ? array_keys($opts) : array_keys((array)$opts);
                }
            }
        }

        return new CapabilitiesResponse($packageTypes, $deliveryTypes, $shipmentOptions);
    }

}
