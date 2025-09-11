<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Mock\Datasets;

/**
 * Dataset for common shipment API responses
 */
class ShipmentResponses
{
    /**
     * Create shipment response (POST /shipments)
     */
    public static function createShipmentResponse(int $shipmentId, string $referenceId): array
    {
        return [
            'response' => json_encode([
                'data' => [
                    'ids' => [
                        [
                            'id' => $shipmentId,
                            'reference_identifier' => $referenceId
                        ]
                    ]
                ]
            ]),
            'code' => 200
        ];
    }
    
    /**
     * Get PDF link response (GET /shipment_labels)
     */
    public static function getPdfLinkResponse(int $shipmentId): array
    {
        return [
            'response' => json_encode([
                'data' => [
                    'pdfs' => [
                        'url' => '/pdfs/download/' . $shipmentId
                    ]
                ]
            ]),
            'code' => 200
        ];
    }
    
    /**
     * Get shipment details response (GET /shipments/{id})
     */
    public static function getShipmentDetailsResponse(array $params): array
    {
        $defaults = [
            'id' => 12345678,
            'barcode' => '3SMYPA' . rand(100000000, 999999999),
            'status' => 1,
            'shop_id' => 12345,
            'account_id' => 67890,
            'reference_identifier' => 'test-ref',
            'carrier_id' => 1,
            'delivery_date' => null,
            'options' => [
                'signature' => false,
                'insurance' => [
                    'amount' => 0,
                    'currency' => 'EUR'
                ],
                'return' => false,
                'package_type' => 1,
                'only_recipient' => false,
                'age_check' => false,
                'large_format' => false,
            ],
            'recipient' => [
                'cc' => 'NL',
                'postal_code' => '2132JE',
                'city' => 'Hoofddorp',
                'street' => 'Antareslaan',
                'number' => '31',
                'person' => 'Test Person',
                'company' => null,
                'email' => 'test@myparcel.nl',
                'phone' => '0612345678',
            ],
            'sender' => [
                'cc' => 'NL',
                'postal_code' => '2132JE',
                'city' => 'Hoofddorp',
                'street' => 'Antareslaan',
                'number' => '31',
                'person' => 'Sender Name',
                'company' => 'MyParcel',
                'email' => 'sender@myparcel.nl',
                'phone' => '0612345678',
            ],
            'secondary_shipments' => [],
            'created' => date('Y-m-d H:i:s'),
            'modified' => date('Y-m-d H:i:s'),
        ];
        
        // Merge provided params with defaults
        $shipmentData = array_replace_recursive($defaults, $params);
        
        return [
            'response' => json_encode([
                'data' => [
                    'shipments' => [$shipmentData]
                ]
            ]),
            'code' => 200
        ];
    }
    
    /**
     * Error response
     */
    public static function errorResponse(int $code, string $message, int $httpCode = 422): array
    {
        return [
            'response' => json_encode([
                'errors' => [
                    [
                        'code' => $code,
                        'message' => $message
                    ]
                ]
            ]),
            'code' => $httpCode
        ];
    }
    
    /**
     * Get response set for a standard shipment creation flow
     */
    public static function getStandardShipmentFlow(array $shipmentParams = []): array
    {
        $shipmentId = $shipmentParams['id'] ?? rand(10000000, 99999999);
        // Use provided reference_identifier or generate one
        $referenceId = $shipmentParams['reference_identifier'] ?: ('test-ref-' . time());
        
        return [
            // Response 1: Create shipment
            self::createShipmentResponse($shipmentId, $referenceId),
            
            // Response 2: Get PDF link
            self::getPdfLinkResponse($shipmentId),
            
            // Response 3: Get shipment details
            self::getShipmentDetailsResponse(array_merge(
                ['id' => $shipmentId, 'reference_identifier' => $referenceId],
                $shipmentParams
            ))
        ];
    }
    
    /**
     * DHL Europlus specific response set
     */
    public static function getDHLEuroplusFlow(array $testData = []): array
    {
        return self::getStandardShipmentFlow([
            'reference_identifier' => $testData['reference_identifier'] ?? null,
            'carrier_id' => 11,
            'recipient' => [
                'cc' => $testData['country'] ?? 'DE',
                'postal_code' => $testData['postal_code'] ?? '39394',
                'city' => $testData['city'] ?? 'Schwanebeck',
                'street' => 'Feldstrasse',
                'number' => '17',
                'person' => $testData['person'] ?? 'Test Person',
                'company' => $testData['company'] ?? 'MyParcel',
                'email' => $testData['email'] ?? 'test@myparcel.nl',
                'phone' => $testData['phone'] ?? '0612345678',
            ],
            'options' => [
                'signature' => $testData['signature'] ?? false,
                'insurance' => [
                    'amount' => $testData['insurance'] ?? 0,
                    'currency' => 'EUR'
                ],
                'return' => false,
                'package_type' => $testData['package_type'] ?? 1,
            ],
        ]);
    }
    
    /**
     * DHL For You specific response set
     */
    public static function getDHLForYouFlow(array $testData = []): array
    {
        return self::getStandardShipmentFlow([
            'reference_identifier' => $testData['reference_identifier'] ?? null,
            'carrier_id' => 9,
            'recipient' => [
                'cc' => $testData['country'] ?? 'NL',
                'postal_code' => $testData['postal_code'] ?? '6825ME',
                'city' => $testData['city'] ?? 'Arnhem',
                'street' => 'Meander',
                'number' => '631',
                'person' => $testData['person'] ?? 'Test Person',
                'company' => $testData['company'] ?? 'MyParcel',
                'email' => $testData['email'] ?? 'test@myparcel.nl',
                'phone' => $testData['phone'] ?? '123456',
            ],
            'options' => [
                'signature' => $testData['signature'] ?? false,
                'insurance' => [
                    'amount' => ($testData['insurance'] ?? 0) * 100,
                    'currency' => 'EUR'
                ],
                'return' => $testData['return'] ?? false,
                'package_type' => $testData['package_type'] ?? 1,
                'only_recipient' => $testData['only_recipient'] ?? false,
                'age_check' => $testData['age_check'] ?? false,
                'same_day_delivery' => $testData['same_day_delivery'] ?? false,
                'hide_sender' => $testData['hide_sender'] ?? false,
            ],
        ]);
    }
    
    /**
     * DHL Parcel Connect specific response set
     */
    public static function getDHLParcelConnectFlow(array $testData = []): array
    {
        $shipmentData = [
            'reference_identifier' => $testData['reference_identifier'] ?? null,
            'carrier_id' => 10,
            'recipient' => [
                'cc' => $testData['country'] ?? 'FR',
                'postal_code' => $testData['postal_code'] ?? '92000',
                'city' => $testData['city'] ?? 'Nanterre',
                'street' => $testData['street'] ?? 'Raymond Poincaré',
                'number' => $testData['number'] ?? '92',
                'person' => $testData['person'] ?? 'Test Person',
                'company' => $testData['company'] ?? 'MyParcel',
                'email' => $testData['email'] ?? 'test@myparcel.nl',
                'phone' => $testData['phone'] ?? '04.94.36.42.48',
            ],
            'options' => [
                'signature' => $testData['signature'] ?? false,
                'insurance' => [
                    'amount' => $testData['insurance'] ?? 0,
                    'currency' => 'EUR'
                ],
                'return' => false,
                'package_type' => $testData['package_type'] ?? 1,
                'delivery_type' => $testData['delivery_type'] ?? 4,
            ],
        ];
        
        // Add pickup location data if provided
        if (isset($testData['pickup_location_code'])) {
            $shipmentData['pickup'] = [
                'location_code' => $testData['pickup_location_code'],
                'location_name' => $testData['pickup_location_name'] ?? '',
                'street' => $testData['pickup_street'] ?? '',
                'number' => $testData['pickup_number'] ?? '',
                'postal_code' => $testData['pickup_postal_code'] ?? '',
                'city' => $testData['pickup_city'] ?? '',
                'cc' => $testData['pickup_country'] ?? 'FR',
                'retail_network_id' => $testData['retail_network_id'] ?? '',
            ];
        }
        
        return self::getStandardShipmentFlow($shipmentData);
    }
    
    /**
     * DPD specific response set
     */
    public static function getDpdFlow(array $testData = []): array
    {
        return self::getStandardShipmentFlow([
            'reference_identifier' => $testData['reference_identifier'] ?? null,
            'carrier_id' => 4,
            'recipient' => [
                'cc' => $testData['country'] ?? 'NL',
                'postal_code' => $testData['postal_code'] ?? '2132JE',
                'city' => $testData['city'] ?? 'Hoofddorp',
                'street' => $testData['street'] ?? 'Antareslaan',
                'number' => $testData['number'] ?? '31',
                'person' => $testData['person'] ?? 'Test Person',
                'company' => $testData['company'] ?? 'MyParcel',
                'email' => $testData['email'] ?? 'test@myparcel.nl',
                'phone' => $testData['phone'] ?? '0612345678',
            ],
            'options' => [
                'signature' => $testData['signature'] ?? false,
                'insurance' => [
                    'amount' => $testData['insurance'] ?? 0,
                    'currency' => 'EUR'
                ],
                'return' => $testData['return'] ?? false,
                'package_type' => $testData['package_type'] ?? 1,
            ],
        ]);
    }
    
    /**
     * bpost specific response set
     */
    public static function getBpostFlow(array $testData = []): array
    {
        // Default to BE address, but use NL address if country is NL
        $isNL = ($testData['country'] ?? 'BE') === 'NL';
        $defaultStreet = $isNL ? 'Antareslaan' : 'Adriaan Brouwerstraat';
        $defaultNumber = $isNL ? '31' : '16';
        $defaultPostalCode = $isNL ? '2132JE' : '2000';
        $defaultCity = $isNL ? 'Hoofddorp' : 'Antwerpen';
        
        return self::getStandardShipmentFlow([
            'reference_identifier' => $testData['reference_identifier'] ?? null,
            'carrier_id' => 2, // bpost correct ID
            'recipient' => [
                'cc' => $testData['country'] ?? 'BE',
                'postal_code' => $testData['postal_code'] ?? $defaultPostalCode,
                'city' => $testData['city'] ?? $defaultCity,
                'street' => $testData['street'] ?? $defaultStreet,
                'number' => $testData['number'] ?? $defaultNumber,
                'person' => $testData['person'] ?? 'Test Person',
                'company' => $testData['company'] ?? 'MyParcel',
                'email' => $testData['email'] ?? 'test@myparcel.nl',
                'phone' => $testData['phone'] ?? '0612345678',
            ],
            'options' => [
                'signature' => $testData['signature'] ?? false,
                'insurance' => [
                    'amount' => $testData['insurance'] ?? 0,
                    'currency' => 'EUR'
                ],
                'return' => $testData['return'] ?? false,
                'package_type' => $testData['package_type'] ?? 1,
                'delivery_type' => $testData['delivery_type'] ?? 2,
                'only_recipient' => $testData['only_recipient'] ?? false,
            ],
        ]);
    }
    
    /**
     * UPS specific response set
     */
    public static function getUPSFlow(array $testData = [], int $carrierId = 14): array
    {
        $country = $testData['country'] ?? 'DE';
        $defaultStreet = $country === 'DE' ? 'Feldstrasse' : ($country === 'NL' ? 'Hoofdstraat' : 'Antareslaan');
        $defaultNumber = $country === 'DE' ? '17' : ($country === 'NL' ? '1' : '31');
        $defaultPostalCode = $country === 'DE' ? '39394' : ($country === 'NL' ? '1234AB' : '2132JE');
        $defaultCity = $country === 'DE' ? 'Schwanebeck' : ($country === 'NL' ? 'Amsterdam' : 'Hoofddorp');
        
        $hasPickupLocationCode = !empty($testData['pickup_location_code']);
        $hasRequiredPickupFields = !empty($testData['pickup_street']) 
            && !empty($testData['pickup_city']) 
            && !empty($testData['pickup_postal_code'])
            && !empty($testData['pickup_location_name']);
        
        $requestedDeliveryType = $testData['delivery_type'] ?? ($carrierId === 13 ? 3 : 2);
        $actualDeliveryType = $requestedDeliveryType;
        
        if ($requestedDeliveryType == 4 && $hasPickupLocationCode && !$hasRequiredPickupFields) {
            $actualDeliveryType = $carrierId === 13 ? 7 : 2;
        }
        
        $shipmentData = [
            'reference_identifier' => $testData['reference_identifier'] ?? null,
            'carrier_id' => $carrierId,
            'recipient' => [
                'cc' => $country,
                'postal_code' => $testData['postal_code'] ?? $defaultPostalCode,
                'city' => $testData['city'] ?? $defaultCity,
                'street' => $testData['street'] ?? $defaultStreet,
                'number' => $testData['number'] ?? $defaultNumber,
                'person' => $testData['person'] ?? 'Test Person',
                'company' => $testData['company'] ?? 'MyParcel',
                'email' => $testData['email'] ?? 'test@myparcel.nl',
                'phone' => $testData['phone'] ?? '0612345678',
            ],
            'options' => [
                'signature' => $testData['signature'] ?? ($testData['age_check'] ?? false), // Age check implies signature
                'insurance' => [
                    'amount' => $testData['insurance'] ?? 0,
                    'currency' => 'EUR'
                ],
                'return' => $testData['return'] ?? false,
                'package_type' => $testData['package_type'] ?? 1,
                'delivery_type' => $actualDeliveryType,
                'age_check' => $testData['age_check'] ?? false,
            ],
        ];
        
        if ($hasPickupLocationCode && $hasRequiredPickupFields) {
            $shipmentData['pickup'] = [
                'location_code' => $testData['pickup_location_code'],
                'location_name' => $testData['pickup_location_name'] ?? '',
                'street' => $testData['pickup_street'] ?? '',
                'number' => $testData['pickup_number'] ?? '',
                'postal_code' => $testData['pickup_postal_code'] ?? '',
                'city' => $testData['pickup_city'] ?? '',
                'cc' => $testData['pickup_country'] ?? $country,
                'retail_network_id' => $testData['retail_network_id'] ?? '',
            ];
            $shipmentData['options']['delivery_type'] = 4;
        } else if ($hasPickupLocationCode && !$hasRequiredPickupFields) {
            $shipmentData['pickup'] = [
                'location_code' => '',
                'location_name' => '',
                'street' => '',
                'number' => '',
                'postal_code' => '',
                'city' => '',
                'cc' => '',
                'retail_network_id' => '',
            ];
        }
        
        if (isset($testData['extra_options']) && is_array($testData['extra_options'])) {
            foreach ($testData['extra_options'] as $key => $value) {
                $shipmentData['options'][$key] = $value;
            }
        }
        
        return self::getStandardShipmentFlow($shipmentData);
    }
    
    /**
     * PostNL specific response set
     */
    public static function getPostNLFlow(array $testData = []): array
    {
        // Auto-detect pickup logic: if auto_detect_pickup is true, set delivery_type to pickup
        $deliveryType = $testData['delivery_type'] ?? 2; // Default to standard delivery
        if (isset($testData['auto_detect_pickup']) && $testData['auto_detect_pickup'] === true) {
            $deliveryType = 4; // Pickup delivery type
        }
        
        // Check if we have pickup location code and required fields
        $hasPickupLocationCode = !empty($testData['pickup_location_code']);
        $hasRequiredPickupFields = 
            !empty($testData['pickup_city']) && 
            !empty($testData['pickup_postal_code']) && 
            !empty($testData['pickup_street']);
        
        $shipmentData = [
            'reference_identifier' => $testData['reference_identifier'] ?? null,
            'carrier_id' => 1, // PostNL carrier ID
            'total_weight' => $testData['total_weight'] ?? null,
            'recipient' => [
                'cc' => $testData['country'] ?? 'NL',
                'postal_code' => $testData['postal_code'] ?? '2132JE',
                'city' => $testData['city'] ?? 'Hoofddorp',
                'street' => $testData['street'] ?? 'Antareslaan',
                'number' => $testData['number'] ?? '31',
                'person' => $testData['person'] ?? 'Test Person',
                'company' => $testData['company'] ?? 'MyParcel',
                'email' => $testData['email'] ?? 'test@myparcel.nl',
                'phone' => $testData['phone'] ?? '0612345678',
            ],
            'options' => [
                'signature' => $testData['signature'] ?? ($testData['age_check'] ?? false), // Age check implies signature
                'only_recipient' => $testData['only_recipient'] ?? false,
                'insurance' => [
                    'amount' => $testData['insurance'] ?? 0,
                    'currency' => 'EUR'
                ],
                'return' => $testData['return'] ?? false,
                'age_check' => $testData['age_check'] ?? false,
                'large_format' => $testData['large_format'] ?? false,
                'package_type' => $testData['package_type'] ?? 1,
                'delivery_type' => $deliveryType,
                'delivery_date' => $testData['delivery_date'] ?? null,
            ],
        ];
        
        // Add label_description if provided
        if (isset($testData['label_description'])) {
            $shipmentData['options']['label_description'] = $testData['label_description'];
        }
        
        // Add delivery_date if provided
        if (isset($testData['delivery_date'])) {
            $shipmentData['delivery_date'] = $testData['delivery_date'];
        }
        
        // Add pickup location data only if all required fields are present
        if ($hasPickupLocationCode && $hasRequiredPickupFields) {
            $shipmentData['pickup'] = [
                'location_code' => $testData['pickup_location_code'],
                'location_name' => $testData['pickup_location_name'] ?? '',
                'street' => $testData['pickup_street'] ?? '',
                'number' => $testData['pickup_number'] ?? '',
                'postal_code' => $testData['pickup_postal_code'] ?? '',
                'city' => $testData['pickup_city'] ?? '',
                'cc' => $testData['pickup_country'] ?? $testData['country'] ?? 'NL',
                'retail_network_id' => $testData['retail_network_id'] ?? '',
            ];
            $shipmentData['options']['delivery_type'] = 4; // Pickup delivery type
        } else if ($hasPickupLocationCode && !$hasRequiredPickupFields) {
            // Clear pickup location when validation fails
            $shipmentData['pickup'] = [
                'location_code' => '',
                'location_name' => '',
                'street' => '',
                'number' => '',
                'postal_code' => '',
                'city' => '',
                'cc' => '',
                'retail_network_id' => '',
            ];
        }
        
        return self::getStandardShipmentFlow($shipmentData);
    }
    
    /**
     * UPS Standard specific response set
     */
    public static function getUPSStandardFlow(array $testData = []): array
    {
        return self::getUPSFlow($testData, 12); // UPS Standard carrier ID
    }
    
    /**
     * UPS Express specific response set
     */
    public static function getUPSExpressFlow(array $testData = []): array
    {
        return self::getUPSFlow($testData, 13); // UPS Express carrier ID
    }
    
    /**
     * GLS specific response set
     */
    public static function getGLSFlow(array $testData = []): array
    {
        $country = $testData['country'] ?? 'NL';
        $deliveryType = $testData['delivery_type'] ?? 2; // Standard delivery

        $insuranceAmount = max(10000, $testData['insurance'] ?? 10000);
        
        // Outside NL signature required by default
        $signature = $testData['signature'] ?? ($country !== 'NL');
        
        $shipmentData = [
            'reference_identifier' => $testData['reference_identifier'] ?? null,
            'carrier_id' => 14,
            'recipient' => [
                'cc' => $country,
                'postal_code' => $testData['postal_code'] ?? '2132JE',
                'city' => $testData['city'] ?? 'Hoofddorp',
                'street' => $testData['street'] ?? 'Antareslaan',
                'number' => $testData['number'] ?? '31',
                'person' => $testData['person'] ?? 'Test Person',
                'company' => $testData['company'] ?? 'MyParcel',
                'email' => $testData['email'] ?? 'test@myparcel.nl',
                'phone' => $testData['phone'] ?? '0612345678',
            ],
            'options' => [
                'signature' => $signature,
                'insurance' => [
                    'amount' => $insuranceAmount,
                    'currency' => 'EUR'
                ],
                'only_recipient' => $testData['only_recipient'] ?? false,
                'package_type' => $testData['package_type'] ?? 1,
                'delivery_type' => $deliveryType,
            ],
        ];
        
        // Saturday delivery  NL
        if ($country === 'NL' && isset($testData['extra_options']['delivery_saturday'])) {
            $shipmentData['options']['delivery_saturday'] = $testData['extra_options']['delivery_saturday'];
        }
        
        // Pickup location data if pickup delivery
        if ($deliveryType == 4 && isset($testData['pickup_location_code'])) {
            $shipmentData['pickup'] = [
                'location_code' => $testData['pickup_location_code'],
                'location_name' => $testData['pickup_location_name'] ?? '',
                'street' => $testData['pickup_street'] ?? '',
                'number' => $testData['pickup_number'] ?? '',
                'postal_code' => $testData['pickup_postal_code'] ?? '',
                'city' => $testData['pickup_city'] ?? '',
                'cc' => $testData['pickup_country'] ?? $country,
                'retail_network_id' => $testData['retail_network_id'] ?? '',
            ];
        }
        
        return self::getStandardShipmentFlow($shipmentData);
    }
}
