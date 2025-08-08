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
            'carrier_id' => 11, // DHL Europlus
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
                'return' => false, // DHL Europlus doesn't support return
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
            'carrier_id' => 2, // DHL For You
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
                    'amount' => $testData['insurance'] ?? 0,
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
        return self::getStandardShipmentFlow([
            'reference_identifier' => $testData['reference_identifier'] ?? null,
            'carrier_id' => 12, // DHL Parcel Connect
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
                'return' => false, // Not supported
                'package_type' => $testData['package_type'] ?? 1,
            ],
        ]);
    }
    
    /**
     * DPD specific response set
     */
    public static function getDpdFlow(array $testData = []): array
    {
        return self::getStandardShipmentFlow([
            'reference_identifier' => $testData['reference_identifier'] ?? null,
            'carrier_id' => 4, // DPD
            'recipient' => [
                'cc' => $testData['country'] ?? 'NL',
                'postal_code' => $testData['postal_code'] ?? '2132JE',
                'city' => $testData['city'] ?? 'Hoofddorp',
                'street' => 'Antareslaan',
                'number' => '31',
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
        return self::getStandardShipmentFlow([
            'reference_identifier' => $testData['reference_identifier'] ?? null,
            'carrier_id' => 8, // bpost
            'recipient' => [
                'cc' => $testData['country'] ?? 'BE',
                'postal_code' => $testData['postal_code'] ?? '2000',
                'city' => $testData['city'] ?? 'Antwerpen',
                'street' => 'Adriaan Brouwerstraat',
                'number' => '16',
                'person' => $testData['person'] ?? 'Test Person',
                'company' => $testData['company'] ?? 'SendMyParcel',
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
     * UPS specific response set
     */
    public static function getUPSFlow(array $testData = [], int $carrierId = 14): array
    {
        return self::getStandardShipmentFlow([
            'reference_identifier' => $testData['reference_identifier'] ?? null,
            'carrier_id' => $carrierId, // 14 = UPS Express, 13 = UPS Standard
            'recipient' => [
                'cc' => $testData['country'] ?? 'NL',
                'postal_code' => $testData['postal_code'] ?? '2132JE',
                'city' => $testData['city'] ?? 'Hoofddorp',
                'street' => 'Antareslaan',
                'number' => '31',
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
     * PostNL specific response set
     */
    public static function getPostNLFlow(array $testData = []): array
    {
        return self::getStandardShipmentFlow([
            'reference_identifier' => $testData['reference_identifier'] ?? null,
            'carrier_id' => 1, // PostNL carrier ID
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
                'only_recipient' => $testData['only_recipient'] ?? false,
                'insurance' => [
                    'amount' => $testData['insurance'] ?? 0,
                    'currency' => 'EUR'
                ],
                'return' => $testData['return'] ?? false,
                'age_check' => $testData['age_check'] ?? false,
                'large_format' => $testData['large_format'] ?? false,
                'package_type' => $testData['package_type'] ?? 1,
            ],
        ]);
    }
}
