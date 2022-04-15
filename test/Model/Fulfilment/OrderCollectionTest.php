<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Fulfilment;

use DateTime;
use Faker\Factory;
use MyParcelNL\Sdk\src\Adapter\DeliveryOptions\AbstractDeliveryOptionsAdapter;
use MyParcelNL\Sdk\src\Collection\Fulfilment\OrderCollection;
use MyParcelNL\Sdk\src\Factory\DeliveryOptionsAdapterFactory;
use MyParcelNL\Sdk\src\Model\Carrier\CarrierPostNL;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\DropOffPoint;
use MyParcelNL\Sdk\src\Model\Fulfilment\Order;
use MyParcelNL\Sdk\src\Model\Fulfilment\OrderLine;
use MyParcelNL\Sdk\src\Model\Fulfilment\Product;
use MyParcelNL\Sdk\src\Model\Recipient;
use MyParcelNL\Sdk\src\Support\Collection;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class OrderCollectionTest extends TestCase
{
    private const DROP_OFF_POINT = [
        'cc'            => 'NL',
        'city'          => 'Leiden',
        'location_code' => 'ed14eb91-7374-4dcc-a41d-34c0d3e45c01',
        'location_name' => 'Instabox',
        'number'        => '2',
        'number_suffix' => 'H',
        'postal_code'   => '2321 TD',
        'street'        => 'Telderskade',
    ];

    /**
     * @return void
     * @before
     */
    public function before(): void
    {
        self::skipUnlessEnabled(self::ENV_TEST_ORDERS, 'The Order API is not available on production yet.');
    }

    /**
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testQuery(): void
    {
        $collection = OrderCollection::query($this->getApiKey());

        self::assertNotEmpty($collection->toArray());
    }

    /**
     * Create 3 Orders, each with 3 filled in OrderLines, put them in an OrderCollection and save them.
     *
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testSave(): void
    {
        $this->faker     = Factory::create('nl_NL');
        $apiKey          = $this->getApiKey();
        $orderCollection = (new OrderCollection())->setApiKey($apiKey);

        // 3 orders
        for ($i = 0; $i < 3; $i++) {
            $deliveryOptions = DeliveryOptionsAdapterFactory::create(
                [
                    'carrier'      => CarrierPostNL::NAME,
                    'date'         => (new DateTime())->format('Y-m-d H:i:s'),
                    'deliveryType' => AbstractConsignment::DELIVERY_TYPES_NAMES[$i],
                    'packageType'  => AbstractConsignment::PACKAGE_TYPE_PACKAGE_NAME,
                ]
            );

            $order      = $this->generateOrder($deliveryOptions);
            $orderLines = $this->generateOrderLines(3);

            $order->setOrderLines($orderLines);
            $orderCollection->push($order);
        }

        $savedOrderCollection = $orderCollection->save();
        self::assertEquals($savedOrderCollection->count(), $orderCollection->count());

        $i = 0;
        $savedOrderCollection->each(
            static function (Order $savedOrder) use (&$i, $orderCollection) {
                /**
                 * @var \MyParcelNL\Sdk\src\Model\Fulfilment\Order $originalOrder
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
                        ->toArray()
                );
                self::assertArraySame(
                    $originalOrder->getRecipient()
                        ->toArray(),
                    $savedOrder->getRecipient()
                        ->toArray()
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
     * @param  \MyParcelNL\Sdk\src\Adapter\DeliveryOptions\AbstractDeliveryOptionsAdapter $deliveryOptions
     *
     * @return \MyParcelNL\Sdk\src\Model\Fulfilment\Order
     * @throws \Exception
     */
    protected function generateOrder(AbstractDeliveryOptionsAdapter $deliveryOptions): Order
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
            ->setDropOffPoint(new DropOffPoint(self::DROP_OFF_POINT));
    }

    /**
     * @param  \MyParcelNL\Sdk\src\Model\Fulfilment\Product $product
     *
     * @return \MyParcelNL\Sdk\src\Model\Fulfilment\OrderLine
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
     * @return \MyParcelNL\Sdk\src\Support\Collection
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
     * @return \MyParcelNL\Sdk\src\Model\Fulfilment\Product
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
     * @return \MyParcelNL\Sdk\src\Model\Recipient
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
}
