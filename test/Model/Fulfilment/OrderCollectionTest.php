<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Fulfilment;

use DateTime;
use Faker\Factory;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentPackageTypeV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesDeliveryTypeV2;
use MyParcelNL\Sdk\Collection\Fulfilment\OrderCollection;
use MyParcelNL\Sdk\Model\CustomsDeclaration;
use MyParcelNL\Sdk\Model\Fulfilment\Order;
use MyParcelNL\Sdk\Model\Fulfilment\OrderLine;
use MyParcelNL\Sdk\Model\Fulfilment\Product;
use MyParcelNL\Sdk\Model\MyParcelCustomsItem;
use MyParcelNL\Sdk\Model\Recipient;
use MyParcelNL\Sdk\Model\Shipment\ShipmentOptions;
use MyParcelNL\Sdk\Support\Collection;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class OrderCollectionTest extends TestCase
{
    private const DROP_OFF_POINT = [
        'cc'            => 'NL',
        'city'          => 'Leiden',
        'location_code' => 'ed14eb91-7374-4dcc-a41d-34c0d3e45c01',
        'location_name' => 'Dropoff',
        'number'        => '2',
        'number_suffix' => 'H',
        'postal_code'   => '2321 TD',
        'street'        => 'Telderskade',
    ];

    /**
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testQuery(): void
    {
        $apiKey = $this->getApiKey();
        $mockResponse = [
            'response' => json_encode([
                'data' => [
                    'orders' => [
                        [
                            'uuid' => 'test-uuid-1',
                            'external_identifier' => 'test-external-id-1',
                            'language' => 'NL',
                            'status' => 'processing',
                            'type' => 'test'
                        ],
                        [
                            'uuid' => 'test-uuid-2',
                            'external_identifier' => 'test-external-id-2',
                            'language' => 'NL',
                            'status' => 'processing',
                            'type' => 'test'
                        ]
                    ]
                ]
            ]),
            'headers' => [],
            'code' => 200
        ];
        
        $mockCurl = $this->mockCurl();
        $mockCurl->shouldReceive('write')->once()->andReturnSelf();
        $mockCurl->shouldReceive('getResponse')->once()->andReturn($mockResponse);
        $mockCurl->shouldReceive('close')->once()->andReturnSelf();
        
        $collection = OrderCollection::query($apiKey);

        self::assertNotEmpty($collection->toArray());
    }

    /**
     * Create 3 Orders, each with 3 filled in OrderLines, put them in an OrderCollection and save them.
     *
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testSave(): void
    {
        $this->faker     = Factory::create('nl_NL');
        $apiKey          = $this->getApiKey();
        $orderCollection = (new OrderCollection())->setApiKey($apiKey);

        // 3 orders
        for ($i = 0; $i < 3; $i++) {
            $deliveryTypes = [
                RefTypesDeliveryTypeV2::MORNING,
                RefTypesDeliveryTypeV2::STANDARD,
                RefTypesDeliveryTypeV2::EVENING,
            ];
            $shipmentOptions = (new ShipmentOptions())
                ->setDeliveryDate((new \DateTime())->format('Y-m-d H:i:s'))
                ->setDeliveryType($deliveryTypes[$i])
                ->setPackageType(RefShipmentPackageTypeV2::PACKAGE);

            $order      = $this->generateOrder($shipmentOptions);
            $order->setCarrierId(1);
            $orderLines = $this->generateOrderLines(3);

            $order->setOrderLines($orderLines);
            $order->setWeight($orderLines->sum('weight'));
            $orderCollection->push($order);
        }
        
        // Prepare mock response for the save API call
        $saveResponse = $this->prepareSaveResponse($orderCollection);
        
        // Set up the mock
        $mockCurl = $this->mockCurl();
        $mockCurl->shouldReceive('write')->once()->andReturnSelf();
        $mockCurl->shouldReceive('getResponse')->once()->andReturn($saveResponse);
        $mockCurl->shouldReceive('close')->once()->andReturnSelf();
        
        $savedOrderCollection = $orderCollection->save();
        self::assertEquals($savedOrderCollection->count(), $orderCollection->count());

        $i = 0;
        $savedOrderCollection->each(
            static function (Order $savedOrder) use (&$i, $orderCollection) {
                /**
                 * @var \MyParcelNL\Sdk\Model\Fulfilment\Order $originalOrder
                 */
                $originalOrder = $orderCollection->all()[$i];

                self::assertEquals(
                    3,
                    $savedOrder->getOrderLines()
                        ->count()
                );
                self::assertIsString($savedOrder->getUuid());
                self::assertIsString($savedOrder->getExternalIdentifier());
                self::assertIsString($savedOrder->getLanguage());
                self::assertIsString($savedOrder->getType());
                self::assertIsString($savedOrder->getStatus());

                self::assertArraySame(
                    $originalOrder->getInvoiceAddress()
                        ->toArray(),
                    $savedOrder->getInvoiceAddress()
                        ->toArray(),
                    'invoice address is not the same'
                );
                self::assertArraySame(
                    $originalOrder->getRecipient()
                        ->toArray(),
                    $savedOrder->getRecipient()
                        ->toArray(),
                    'recipient is not the same'
                );

                $savedOrder
                    ->getOrderLines()
                    ->each(static function (OrderLine $orderLine) {
                        self::assertIsString($orderLine->getUuid(), 'uuid is missing in order line');
                    });

                $i++;
            }
        );
    }

    /**
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testSaveSendsTheGivenHeaders(): void
    {
        $this->faker     = Factory::create('nl_NL');
        $orderCollection = (new OrderCollection())->setApiKey($this->getApiKey());
        $shipmentOptions = (new ShipmentOptions())
            ->setDeliveryType(RefTypesDeliveryTypeV2::STANDARD)
            ->setPackageType(RefShipmentPackageTypeV2::PACKAGE);

        $order      = $this->generateOrder($shipmentOptions);
        $orderLines = $this->generateOrderLines();
        $order->setCarrierId(1);
        $order->setOrderLines($orderLines);
        $order->setWeight($orderLines->sum('weight'));
        $orderCollection->push($order);

        $mockCurl = $this->mockCurl();
        $mockCurl->shouldReceive('write')
            ->once()
            ->with('POST', \Mockery::any(), \Mockery::on(static function (array $headers): bool {
                return 'true' === ($headers['x-dmp-no-tracking'] ?? null)
                    && 0 === strpos($headers['Authorization'] ?? '', 'basic ');
            }), \Mockery::any())
            ->andReturnSelf();
        $mockCurl->shouldReceive('getResponse')->once()->andReturn($this->prepareSaveResponse($orderCollection));
        $mockCurl->shouldReceive('close')->once()->andReturnSelf();

        $orderCollection->save(['x-dmp-no-tracking' => 'true']);
    }

    /**
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testSaveSendsTheExternalProductReference(): void
    {
        $customsDeclaration = $this->saveAndCaptureCustomsDeclaration('catalog_item_abc123def456g');

        self::assertSame('catalog_item_abc123def456g', $customsDeclaration['items'][0]['external_product_reference']);
    }

    /**
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testSaveLeavesOutAnUnsetExternalProductReference(): void
    {
        $customsDeclaration = $this->saveAndCaptureCustomsDeclaration(null);

        self::assertSame(
            [
                'contents' => 1,
                'items'    => [
                    [
                        'description'    => 'Shoes',
                        'amount'         => 2,
                        'weight'         => 500,
                        'item_value'     => ['amount' => 1000, 'currency' => 'EUR'],
                        'classification' => '6403',
                        'country'        => 'NL',
                    ],
                ],
                'invoice'  => '1234',
                'weight'   => 500,
            ],
            $customsDeclaration
        );
    }

    /**
     * @throws \Exception
     */
    public function testToArrayContainsTheCustomsDeclaration(): void
    {
        $this->faker = Factory::create('nl_NL');
        $order       = $this->generateOrder(new ShipmentOptions())
            ->setCarrierId(1)
            ->setOrderLines($this->generateOrderLines())
            ->setCustomsDeclaration($this->generateCustomsDeclaration('catalog_item_abc123def456g'));

        $orders = (new OrderCollection([$order]))->toArray();

        self::assertSame(
            'catalog_item_abc123def456g',
            $orders[0]['customs_declaration']['items'][0]['external_product_reference']
        );
    }

    /**
     * Saves one order with a customs declaration and returns the customs declaration from the request body.
     *
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     * @throws \Exception
     */
    protected function saveAndCaptureCustomsDeclaration(?string $externalProductReference): array
    {
        $this->faker     = Factory::create('nl_NL');
        $orderCollection = (new OrderCollection())->setApiKey($this->getApiKey());
        $shipmentOptions = (new ShipmentOptions())
            ->setDeliveryType(RefTypesDeliveryTypeV2::STANDARD)
            ->setPackageType(RefShipmentPackageTypeV2::PACKAGE);

        $order      = $this->generateOrder($shipmentOptions);
        $orderLines = $this->generateOrderLines();
        $order->setCarrierId(1);
        $order->setOrderLines($orderLines);
        $order->setWeight($orderLines->sum('weight'));
        $order->setCustomsDeclaration($this->generateCustomsDeclaration($externalProductReference));
        $orderCollection->push($order);

        $body     = [];
        $mockCurl = $this->mockCurl();
        $mockCurl->shouldReceive('write')
            ->once()
            ->with('POST', \Mockery::any(), \Mockery::any(), \Mockery::on(static function (string $json) use (&$body): bool {
                $body = json_decode($json, true);

                return true;
            }))
            ->andReturnSelf();
        $mockCurl->shouldReceive('getResponse')->once()->andReturn($this->prepareSaveResponse($orderCollection));
        $mockCurl->shouldReceive('close')->once()->andReturnSelf();

        $orderCollection->save();

        return $body['data']['orders'][0]['shipment']['customs_declaration'];
    }

    protected function generateCustomsDeclaration(?string $externalProductReference): CustomsDeclaration
    {
        $customsDeclaration = (new CustomsDeclaration())
            ->setContents(1)
            ->setInvoice('1234');
        $customsDeclaration->setWeight(500);
        $customsDeclaration->addCustomsItem(
            (new MyParcelCustomsItem())
                ->setDescription('Shoes')
                ->setAmount(2)
                ->setWeight(500)
                ->setItemValue(1000)
                ->setClassification('6403')
                ->setCountry('NL')
                ->setExternalProductReference($externalProductReference)
        );

        return $customsDeclaration;
    }

    /**
     * @param  \MyParcelNL\Sdk\Model\Shipment\ShipmentOptions $deliveryOptions
     *
     * @return \MyParcelNL\Sdk\Model\Fulfilment\Order
     * @throws \Exception
     */
    protected function generateOrder(ShipmentOptions $deliveryOptions): Order
    {
        return (new Order())
            ->setStatus($this->faker->randomElement(['open', 'processing', 'completed']))
            ->setDeliveryOptions($deliveryOptions)
            ->setExternalIdentifier($this->faker->uuid)
            ->setFulfilmentPartnerIdentifier($this->faker->uuid)
            ->setInvoiceAddress($this->generateRecipient())
            ->setRecipient($this->generateRecipient())
            ->setLanguage('NL')
            ->setType($this->faker->word)
            ->setOrderDate((new DateTime())->format('Y-M-d'))
            ;
    }

    /**
     * @param  \MyParcelNL\Sdk\Model\Fulfilment\Product $product
     *
     * @return \MyParcelNL\Sdk\Model\Fulfilment\OrderLine
     */
    protected function generateOrderLine(Product $product): OrderLine
    {
        // Calculate random prices with or without vat
        $priceAfterVat = $this->faker->numberBetween(100, 10000);
        $vat           = (int) ($priceAfterVat * $this->faker->randomElement([0, 0.09, 0.21]));
        $price         = ($priceAfterVat - $vat);

        return (new OrderLine())
            ->setUuid($this->faker->uuid)
            ->setInstructions([
                'wrapping' => implode(' ', $this->faker->words(4)),
            ])
            ->setQuantity($this->faker->numberBetween(1, 20))
            ->setPrice($price)
            ->setPriceAfterVat($priceAfterVat)
            ->setVat($vat)
            ->setProduct($product);
    }

    /**
     * @param  int $amount
     *
     * @return \MyParcelNL\Sdk\Support\Collection
     */
    protected function generateOrderLines(int $amount = 1): Collection
    {
        $orderLines = new Collection();

        for ($i = 0; $i < $amount; $i++) {
            $product   = $this->generateProduct();
            $orderLine = $this->generateOrderLine($product);
            $orderLines->push($orderLine);
        }

        return $orderLines;
    }

    /**
     * @return \MyParcelNL\Sdk\Model\Fulfilment\Product
     */
    protected function generateProduct(): Product
    {
        return (new Product())
            ->setDescription(implode(' ', $this->faker->words(10)))
            ->setEan($this->faker->uuid)
            ->setExternalIdentifier($this->faker->uuid)
            ->setName(implode(' ', $this->faker->words(22)))
            ->setSku(str_pad((string) $this->faker->numberBetween(100, 1000), 10, '0', STR_PAD_LEFT))
            ->setUuid($this->faker->uuid)
            ->setHeight($this->faker->numberBetween(100, 1000))
            ->setLength($this->faker->numberBetween(100, 1000))
            ->setWeight($this->faker->numberBetween(100, 1000))
            ->setWidth($this->faker->numberBetween(100, 1000));
    }

    /**
     * @return \MyParcelNL\Sdk\Model\Recipient
     */
    protected function generateRecipient(): Recipient
    {
        return (new Recipient())
            ->setCc('NL')
            ->setCity($this->faker->city)
            ->setCompany($this->faker->company)
            ->setEmail($this->faker->email)
            ->setPerson(substr($this->faker->name(), 0, 50))
            ->setPhone($this->faker->phoneNumber)
            ->setPostalCode($this->faker->postcode)
            ->setStreet(substr($this->faker->streetAddress, 0, 40));
    }
    
    /**
     * Prepares a mock API response for saving orders
     * 
     * @param \MyParcelNL\Sdk\Collection\Fulfilment\OrderCollection $orderCollection
     * 
     * @return array
     */
    protected function prepareSaveResponse(OrderCollection $orderCollection): array
    {
        $responseData = [];
        
        // Copy all orders and assign UUIDs and other server-generated fields
        foreach ($orderCollection as $index => $order) {
            // Build the response data structure that matches what the API returns
            $orderData = [
                // Top-level order fields
                'uuid' => $order->getUuid() ?: "generated-uuid-{$index}",
                'external_identifier' => $order->getExternalIdentifier(),
                'fulfilment_partner_identifier' => $order->getFulfilmentPartnerIdentifier(),
                'language' => $order->getLanguage(),
                'order_date' => $order->getOrderDateString('Y-m-d H:i:s'),
                'status' => $order->getStatus(),
                'type' => $order->getType(),
                'order_shipments' => [],
                
                // Invoice address
                'invoice_address' => $order->getInvoiceAddress() ? $order->getInvoiceAddress()->toArray() : [],
                
                // Order lines with UUIDs
                'order_lines' => [],
                
                // Shipment data (nested structure expected by Order constructor)
                'shipment' => [
                    'recipient' => $order->getRecipient() ? $order->getRecipient()->toArray() : [],
                ]
            ];
            
            // Add order lines with UUIDs
            if ($order->getOrderLines()) {
                foreach ($order->getOrderLines() as $lineIndex => $orderLine) {
                    $lineData = $orderLine->toArray();
                    $lineData['uuid'] = $orderLine->getUuid() ?: "generated-line-uuid-{$index}-{$lineIndex}";
                    $orderData['order_lines'][] = $lineData;
                }
            }
            
            $responseData[] = $orderData;
        }
        
        return [
            'response' => json_encode([
                'data' => [
                    'orders' => $responseData
                ]
            ]),
            'headers' => [],
            'code' => 200
        ];
    }
}
