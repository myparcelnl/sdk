# Example: Migrating a Test to Use Mocking

## Step 1: Add the use statements
```php
use MyParcelNL\Sdk\Test\Mock\MockMyParcelCurl;
use MyParcelNL\Sdk\Test\Mock\Datasets\ShipmentResponses;
```

## Step 2: Update the test method

### BEFORE (old situation):
```php
public function testPostNLConsignments(array $testData): void
{
    $this->doConsignmentTest($testData);
}
```

### AFTER (with mock):
```php
public function testPostNLConsignments(array $testData): void
{
    // Clear the queue
    MockMyParcelCurl::clearQueue();
    
    // Get responses for PostNL
    $responses = ShipmentResponses::getPostNLFlow([
        'reference_identifier' => $testData[self::REFERENCE_IDENTIFIER] ?? null,
        'signature' => $testData[self::SIGNATURE] ?? false,
        'insurance' => $testData[self::INSURANCE] ?? 0,
        'package_type' => $testData[self::PACKAGE_TYPE] ?? 1,
        'country' => $testData[self::COUNTRY] ?? 'NL',
        'postal_code' => $testData[self::POSTAL_CODE] ?? '2132JE',
        'city' => $testData[self::CITY] ?? 'Hoofddorp',
        'person' => $testData[self::PERSON] ?? 'Test Person',
        'company' => $testData[self::COMPANY] ?? 'MyParcel',
        'email' => $testData[self::EMAIL] ?? 'spam@myparcel.nl',
        'phone' => $testData[self::PHONE] ?? '023 303 0315',
    ]);
    
    // Queue all responses
    foreach ($responses as $response) {
        MockMyParcelCurl::addResponse($response);
    }
    
    // Run the test
    $this->doConsignmentTest($testData);
}
```

## For other carriers:

- **DHL For You**: use `ShipmentResponses::getDHLForYouFlow($testData)`
- **DHL Parcel Connect**: use `ShipmentResponses::getDHLParcelConnectFlow($testData)`
- **DPD**: use `ShipmentResponses::getDPDFlow($testData)`
- **bpost**: use `ShipmentResponses::getBpostFlow($testData)`
- **UPS Express**: use `ShipmentResponses::getUPSFlow($testData, 14)`
- **UPS Standard**: use `ShipmentResponses::getUPSFlow($testData, 13)`

## More Complex Example: Custom Responses

```php
public function testWithCustomResponse(): void
{
    MockMyParcelCurl::clearQueue();
    
    // First response: create shipment
    MockMyParcelCurl::addResponse([
        'response' => json_encode([
            'data' => [
                'ids' => [
                    ['id' => 123456, 'reference_identifier' => 'my-ref']
                ]
            ]
        ]),
        'code' => 201
    ]);
    
    // Second response: get shipment details
    MockMyParcelCurl::addResponse([
        'response' => json_encode([
            'data' => [
                'shipments' => [
                    [
                        'id' => 123456,
                        'barcode' => '3SMYPA123456789',
                        'status' => 2
                    ]
                ]
            ]
        ])
    ]);
    
    // Your test logic here
}
```

## Tips:

1. **Count your API calls**: Make sure you queue exactly the right number of responses
2. **Check the order**: Responses are consumed in FIFO order
3. **Use datasets**: For standard flows, use the ShipmentResponses dataset
4. **Test errors**: Don't forget to test error scenarios with error responses

## Done! 🎉
