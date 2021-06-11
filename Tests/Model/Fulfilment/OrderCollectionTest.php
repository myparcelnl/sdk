<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Fulfilment;

use DateTime;
use Faker\Factory;
use MyParcelNL\Sdk\src\Collection\Fulfilment\OrderCollection;
use MyParcelNL\Sdk\src\Factory\DeliveryOptionsAdapterFactory;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;
use MyParcelNL\Sdk\src\Model\Recipient;
use MyParcelNL\Sdk\src\Support\Collection;
use PHPUnit\Framework\TestCase;

class OrderCollectionTest extends TestCase
{
    /**
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function testQuery(): void
    {
        $apiKey     = getenv('API_KEY');
        $collection = OrderCollection::query($apiKey);

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
        $faker           = Factory::create('nl_NL');
        $apiKey          = getenv('API_KEY');
        $orderCollection = (new OrderCollection())->setApiKey($apiKey);

        // 3 orders
        for ($i = 0; $i < 3; $i++) {
            $deliveryOptions = DeliveryOptionsAdapterFactory::create(
                [
                    'carrier'      => PostNLConsignment::CARRIER_NAME,
                    'date'         => (new DateTime())->format('Y-m-d H:i:s'),
                    'deliveryType' => AbstractConsignment::DELIVERY_TYPES_NAMES[$i],
                    'packageType'  => AbstractConsignment::PACKAGE_TYPE_PACKAGE_NAME,
                ]
            );

            $createRecipient = static function () use ($faker): Recipient {
                return (new Recipient())
                    ->setCc('NL')
                    ->setCity($faker->city)
                    ->setCompany($faker->company)
                    ->setEmail($faker->email)
                    ->setPerson(substr($faker->name(), 0, 50))
                    ->setPhone($faker->phoneNumber)
                    ->setPostalCode($faker->postcode)
                    ->setStreet(substr($faker->streetAddress, 0, 40));
            };

            $order = (new Order())
                ->setStatus($faker->randomElement(['open', 'processing', 'completed']))
                ->setDeliveryOptions($deliveryOptions)
                ->setExternalIdentifier($faker->uuid)
                ->setFulfilmentPartnerIdentifier($faker->uuid)
                ->setInvoiceAddress($createRecipient())
                ->setRecipient($createRecipient())
                ->setLanguage('NL')
                ->setType($faker->word)
                ->setOrderDate((new DateTime())->format('Y-M-d'));

            $orderLines = new Collection();

            // 3 order lines in each order
            for ($j = 0; $j < 3; $j++) {
                // Calculate random prices with or without vat
                $priceAfterVat = (int) ($faker->numberBetween(100, 10000));
                $vat           = (int) ($priceAfterVat * $faker->randomElement([0, 0.09, 0.21]));
                $price         = ($priceAfterVat - $vat);

                $product = (new Product())
                    ->setDescription(implode(' ', $faker->words(10)))
                    ->setEan($faker->uuid)
                    ->setExternalIdentifier($faker->uuid)
                    ->setName(implode(' ', $faker->words(22)))
                    ->setSku(str_pad((string) $faker->numberBetween(100, 1000), 10, '0', STR_PAD_LEFT))
                    ->setUuid($faker->uuid);

                $orderLine = (new OrderLine())
                    ->setUuid($faker->uuid)
                    ->setInstructions([
                        'wrapping' => implode(' ', $faker->words(4))
                    ])
                    ->setQuantity($faker->numberBetween(1, 20))
                    ->setPrice($price)
                    ->setPriceAfterVat($priceAfterVat)
                    // TODO: After MY-28691 is merged only passing $vat is sufficient.
                    ->setVat($vat === 0 ? null : $vat)
                    ->setProduct($product);

                $orderLine
                    ->getProduct()
                    ->setHeight($faker->numberBetween(100,1000))
                    ->setLength($faker->numberBetween(100,1000))
                    ->setWeight($faker->numberBetween(100,1000))
                    ->setWidth($faker->numberBetween(100,1000));

                $orderLines->push($orderLine);
            }

            $order->setOrderLines($orderLines);
            $orderCollection->push($order);
        }

        $savedOrderCollection = $orderCollection->save();
        self::assertEquals($savedOrderCollection->count(), $orderCollection->count());

        $i = 0;
        $savedOrderCollection->each(
            static function (Order $savedOrder) use (&$i, $orderCollection) {
                /**
                 * @var \MyParcelNL\Sdk\src\Model\Fulfilment\Order $order
                 */
                $order = $orderCollection->all()[$i];

                self::assertEquals(3, $savedOrder->getOrderLines()->count());
                self::assertInternalType('string', $savedOrder->getUuid(), 'uuid does not match expectation');
                self::assertInternalType('string', $savedOrder->getExternalIdentifier(), 'external_identifier does not match expectation');
                self::assertInternalType('string', $savedOrder->getLanguage(), 'language does not match expectation');
                self::assertInternalType('string', $savedOrder->getType(), 'type does not match expectation');
                self::assertInternalType('string', $savedOrder->getStatus(), 'status does not match expectation');

                self::assertArraySame(
                    $order->getInvoiceAddress()->toArray(),
                    $savedOrder->getInvoiceAddress()->toArray(),
                    'invoice_address does not match expectation'
                );
                self::assertArraySame(
                    $order->getRecipient()->toArray(),
                    $savedOrder->getRecipient()->toArray(),
                    'recipient does not match expectation'
                );

                $savedOrder->getOrderLines()->each(
                    static function (OrderLine $orderLine) {
                        self::assertInternalType('string', $orderLine->getUuid(), 'uuid is missing in order line');
                    }
                );

                $i++;
            }
        );
    }

    /**
     * Compares two arrays but ignores order of keys.
     *
     * @param  array       $array1
     * @param  array       $array2
     * @param  string|null $message
     */
    private static function assertArraySame(array $array1, array $array2, string $message = null): void
    {
        ksort($array1);
        ksort($array2);

        self::assertSame($array1, $array2, $message);
    }
}
