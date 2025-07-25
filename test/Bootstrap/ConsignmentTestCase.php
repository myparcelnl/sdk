<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Bootstrap;

use DateInterval;
use DateTime;
use MyParcelNL\Sdk\Exception\MissingFieldException;
use MyParcelNL\Sdk\Helper\Utils;
use MyParcelNL\Sdk\Factory\ConsignmentFactory;
use MyParcelNL\Sdk\Helper\MyParcelCollection;
use MyParcelNL\Sdk\Model\Carrier\CarrierPostNL;
use MyParcelNL\Sdk\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Model\MyParcelCustomsItem;
use MyParcelNL\Sdk\Services\Web\DropOffPointWebService;
use MyParcelNL\Sdk\Support\Arr;
use MyParcelNL\Sdk\Support\Str;
use PHPUnit\Runner\Version;
use RuntimeException;

class ConsignmentTestCase extends TestCase
{
    public const    ENV_ALLOW_DHL_FOR_YOU       = 'ALLOW_DHL_FOR_YOU';
    protected const ADD_DROPOFF_POINT           = 'add_dropoff_point';
    protected const AGE_CHECK                   = 'age_check';
    protected const API_KEY                     = 'api_key';
    protected const AUTO_DETECT_PICKUP          = 'auto_detect_pickup';
    protected const CARRIER_ID                  = 'carrier_id';
    protected const CHECKOUT_DATA               = 'checkout_data';
    protected const CITY                        = 'city';
    protected const COMPANY                     = 'company';
    protected const COUNTRY                     = 'country';
    protected const CUSTOMS_DECLARATION         = 'customs_items';
    protected const CUSTOMS_DECLARATION_ITEMS   = 'items';
    protected const CUSTOMS_ITEM_AMOUNT         = 'amount';
    protected const CUSTOMS_ITEM_CLASSIFICATION = 'classification';
    protected const CUSTOMS_ITEM_COUNTRY        = 'country';
    protected const CUSTOMS_ITEM_DESCRIPTION    = 'description';
    protected const CUSTOMS_ITEM_ITEM_VALUE     = 'item_value';
    protected const CUSTOMS_ITEM_WEIGHT         = 'weight';
    protected const DELIVERY_DATE               = 'delivery_date';
    protected const DELIVERY_TYPE               = 'delivery_type';
    protected const EMAIL                       = 'email';
    protected const EXCEPTION                   = 'exception';
    protected const EXTRA_OPTIONS               = 'extra_options';
    protected const FULL_STREET                 = 'full_street';
    protected const FULL_STREET_INPUT           = 'full_street_input';
    protected const INSURANCE                   = 'insurance';
    protected const INVOICE                     = 'invoice';
    protected const LABEL_DESCRIPTION           = 'label_description';
    protected const LARGE_FORMAT                = 'large_format';
    protected const MULTI_COLLO_AMOUNT          = 'multi_collo_amount';
    protected const NUMBER                      = 'number';
    protected const ONLY_RECIPIENT              = 'only_recipient';
    protected const PACKAGE_TYPE                = 'package_type';
    protected const PERSON                      = 'person';
    protected const PHONE                       = 'phone';
    protected const PICKUP_CITY                 = 'pickup_city';
    protected const PICKUP_COUNTRY              = 'pickup_country';
    protected const PICKUP_LOCATION_NAME        = 'pickup_location_name';
    protected const PICKUP_NUMBER               = 'pickup_number';
    protected const PICKUP_POSTAL_CODE          = 'pickup_postal_code';
    protected const PICKUP_STREET               = 'pickup_street';
    protected const POSTAL_CODE                 = 'postal_code';
    protected const REFERENCE_IDENTIFIER        = 'reference_identifier';
    protected const REGION                      = 'region';
    protected const RETAIL_NETWORK_ID           = 'retail_network_id';
    protected const RETURN                      = 'return';
    protected const SAVE_RECIPIENT_ADDRESS      = 'save_recipient_address';
    protected const SIGNATURE                   = 'signature';
    protected const SAME_DAY_DELIVERY           = 'same_day_delivery';
    protected const HIDE_SENDER                 = 'hide_sender';
    protected const EXTRA_ASSURANCE             = 'extra_assurance';
    protected const STREET                      = 'street';
    protected const TOTAL_WEIGHT                = 'total_weight';
    protected const WEIGHT                      = 'weight';
    /**
     * Consignment properties whose getters which don't follow the "get<property>" format.
     */
    private const ALTERNATIVE_GETTERS_MAP      = [
        self::AGE_CHECK         => 'hasAgeCheck',
        self::LARGE_FORMAT      => 'isLargeFormat',
        self::ONLY_RECIPIENT    => 'isOnlyRecipient',
        self::RETURN            => 'isReturn',
        self::SIGNATURE         => 'isSignature',
        self::HIDE_SENDER       => 'hasHideSender',
        self::EXTRA_ASSURANCE   => 'hasExtraAssurance',
        self::SAME_DAY_DELIVERY => 'isSameDayDelivery',
    ];
    private const EXPECTED_SUFFIX              = '_expected';
    private const KEYS_EXCLUDED_FROM_TEST_NAME = [
        self::API_KEY,
    ];

    /**
     * @param MyParcelCollection $collection
     *
     * @return void
     */
    protected static function assertHasPdfLink(MyParcelCollection $collection): void
    {
        self::assertMatchesRegularExpression('#^https://api.myparcel.nl/pdfs#', $collection->getLinkOfLabels(), 'Can\'t get link of PDF');
    }

    /**
     * @param array[] $datasets
     * @param array   $defaults
     *
     * @return array
     * @throws \Exception
     * @throws \Exception
     */
    protected function createConsignmentProviderDataset(array $datasets, array $defaults = []): array
    {
        $newDefaults = $this->createConsignmentTestData($defaults);
        $newDatasets = [];

        foreach ($datasets as $key => $dataset) {
            $data              = self::normalizeTestData($dataset);
            $key               = is_string($key) ? $key : $this->createIdentifierForConsignments($data);
            $newDatasets[$key] = $data;
        }

        return $this->createProviderDataset($newDatasets, $newDefaults);
    }

    /**
     * @param array $consignmentData
     *
     * @return array
     * @throws \Exception
     */
    protected function createConsignmentTestData(array $consignmentData = []): array
    {
        return array_replace($this->getDefaultConsignmentData(), $consignmentData);
    }

    /**
     * @param array $consignmentData - Array of consignmentData arrays.
     *
     * @return array
     */
    protected function createConsignmentsTestData(array $consignmentData = []): array
    {
        return array_map([$this, 'createConsignmentTestData'], $consignmentData);
    }

    /**
     * @param array $dataset
     *
     * @return string
     */
    protected function createIdentifierForConsignments(array $dataset): string
    {
        $keyArray = [];

        foreach ($dataset as $index => $consignment) {
            if (count($dataset) > 1) {
                $keyArray[] = $index . '] ';
            }

            foreach ($consignment as $key => $value) {
                if (in_array($key, self::KEYS_EXCLUDED_FROM_TEST_NAME, true) || strpos($key, self::expected())) {
                    continue;
                }

                $keyArray[] = $key . ': ' . $value;
            }
        }

        return implode(', ', $keyArray);
    }

    /**
     * @param array $testData
     *
     * @throws \Exception
     */
    protected function doConsignmentTest(array $testData): void
    {
        if (isset($testData[self::EXCEPTION])) {
            $this->expectExceptionMessage($testData[self::EXCEPTION]);
        }

        $collection = $this->generateCollection($testData);
        $collection->setLinkOfLabels();

        $consignment = $collection->getOneConsignment();
        self::assertCount(1, $collection, 'Collection expected to have only one result.');
        self::assertTrue($consignment->getConsignmentId() > 1, 'No id found');
        self::validateConsignmentOptions($testData, $consignment);
        self::assertHasPdfLink($collection);

        if ($testData[self::PACKAGE_TYPE] === AbstractConsignment::PACKAGE_TYPE_PACKAGE) {
            self::assertNotEmpty($consignment->getBarcode(), 'Barcode is missing');
        }
    }

    /**
     * @param string $property
     *
     * @return string
     */
    protected static function expected(string $property = ''): string
    {
        return $property . self::EXPECTED_SUFFIX;
    }

    /**
     * @param array $testData
     *
     * @return MyParcelCollection
     * @throws MissingFieldException
     * @throws \Exception
     */
    protected function generateCollection(array $testData = []): MyParcelCollection
    {
        $testData   = self::normalizeTestData($testData);
        $collection = (new MyParcelCollection())->setUserAgents(['PHPUnit' => Version::id()]);

        foreach ($testData as $consignmentData) {
            $consignment = $this->generateConsignment($consignmentData);

            // Add multicollo if needed.
            $colloAmount = $consignmentData[self::MULTI_COLLO_AMOUNT] ?? 1;
            if ($colloAmount > 1) {
                $collection->addMultiCollo($consignment, $colloAmount);
            } else {
                $collection->addConsignment($consignment);
            }
        }

        return $collection;
    }

    /**
     * Generates a consignment by given array data. Uses all setters related to entered array values.
     *
     * @param array $data
     * @param bool  $addDefaults
     *
     * @return AbstractConsignment|null
     * @throws MissingFieldException
     * @throws \Exception
     */
    protected function generateConsignment(array $data = [], bool $addDefaults = false): ?AbstractConsignment
    {
        if ($addDefaults) {
            $data = $this->createConsignmentTestData($data);
        }

        $carrierId   = $data[self::CARRIER_ID] ?? CarrierPostNL::ID;
        $consignment = (ConsignmentFactory::createByCarrierId($carrierId));

        Utils::fillObject($consignment, $data);
        $this->addDropOffPoint($data, $consignment);
        $this->addCustomsDeclaration($data, $consignment);

        return $consignment;
    }

    /**
     * Creates a delivery date two days in the future.
     *
     * @param string $interval
     *
     * @return string
     * @throws \Exception
     * @see https://www.php.net/manual/en/dateinterval.construct.php
     */
    protected function generateDeliveryDate(string $interval = 'P2D'): string
    {
        return (new DateTime())
            ->setTime(0, 0)
            ->add(new DateInterval($interval))
            ->format('Y-m-d H:m:i')
        ;
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function generateUniqueIdentifier(): string
    {
        return $this->generateTimestamp() . '_' . $this->faker->unique()->firstName();
    }

    /**
     * @throws \Exception
     */
    protected function getDefaultAddress(string $country = AbstractConsignment::CC_NL): array
    {
        switch ($country) {
            case AbstractConsignment::CC_NL:
                return [
                    self::COUNTRY     => AbstractConsignment::CC_NL,
                    self::COMPANY     => 'MyParcel',
                    self::FULL_STREET => 'Antareslaan 31',
                    self::POSTAL_CODE => '2132JE',
                    self::CITY        => 'Hoofddorp',
                ];
            case AbstractConsignment::CC_BE:
                return [
                    self::COUNTRY     => AbstractConsignment::CC_BE,
                    self::COMPANY     => 'SendMyParcel',
                    self::FULL_STREET => 'Adriaan Brouwerstraat 16',
                    self::POSTAL_CODE => '2000',
                    self::CITY        => 'Antwerpen',
                ];
            case 'DE':
                return [
                    self::COUNTRY     => 'DE',
                    self::FULL_STREET => 'Kurfürstendamm 195',
                    self::POSTAL_CODE => '10707',
                    self::CITY        => 'Berlin',
                ];
            case 'CA':
                return [
                    self::COUNTRY     => 'CA',
                    self::FULL_STREET => 'Pierre-de Coubertin Ave 4777',
                    self::POSTAL_CODE => 'H1V 1B3',
                    self::CITY        => 'Montreal',
                ];
            default:
                throw new RuntimeException("No default address available for country $country.");
        }
    }

    /**
     * Default values to use in creating test consignments.
     *
     * @return string[]
     * @throws \Exception
     */
    protected function getDefaultConsignmentData(): array
    {
        return $this->getDefaultAddress() + [
                self::API_KEY                => $this->getApiKey(),

                // Base options
                self::CARRIER_ID             => CarrierPostNL::ID,
                self::PACKAGE_TYPE           => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
                self::REFERENCE_IDENTIFIER   => $this->generateUniqueIdentifier(),

                // Contact information
                self::PERSON                 => Str::limit($this->faker->firstName . ' ' . $this->faker->lastName, 50, ''),
                self::EMAIL                  => 'spam@myparcel.nl',
                self::PHONE                  => '023 303 0315',

                // Delivery options
                self::DELIVERY_DATE          => null,
                self::DELIVERY_TYPE          => AbstractConsignment::DELIVERY_TYPE_STANDARD,

                // Shipment options
                self::AGE_CHECK              => false,
                self::INSURANCE              => 0,
                self::LARGE_FORMAT           => false,
                self::ONLY_RECIPIENT         => false,
                self::RETURN                 => false,
                self::SIGNATURE              => false,

                // Extra options
                self::AUTO_DETECT_PICKUP     => false,
                self::MULTI_COLLO_AMOUNT     => 1,
                self::SAVE_RECIPIENT_ADDRESS => false,
            ];
    }

    /**
     * @return array[]
     */
    protected function getDefaultCustomsDeclaration(string $country = AbstractConsignment::CC_NL): array
    {
        return [
            self::INVOICE                   => '12345678',
            self::CUSTOMS_DECLARATION_ITEMS => [
                [
                    self::COUNTRY                     => $country,
                    self::CUSTOMS_ITEM_AMOUNT         => 2,
                    self::CUSTOMS_ITEM_CLASSIFICATION => '200800',
                    self::CUSTOMS_ITEM_DESCRIPTION    => 'Cool Mobile',
                    self::CUSTOMS_ITEM_ITEM_VALUE     => 40000,
                    self::WEIGHT                      => 2000,
                ],
            ],
        ];
    }

    /**
     * @param array               $testData
     * @param AbstractConsignment $consignment
     * @param string[]|null       $only
     */
    protected static function validateConsignmentOptions(
        array               $testData,
        AbstractConsignment $consignment,
        ?array              $only = null
    ): void {
        $testData = self::normalizeTestData($testData);

        foreach ($testData as $testConsignment) {
            if ($only) {
                $testConsignment = self::filterTestData($testConsignment, $only);
            }

            foreach ($testConsignment as $property => $expectedValue) {
                $originalProperty = self::getOriginalProperty($property);

                // Don't check for keys used to change the expectations.
                if ($property !== $originalProperty && array_key_exists($originalProperty, $testConsignment)) {
                    continue;
                }

                // Don't check the reference identifier if it's null as a random one will be generated for each shipment.
                if (self::REFERENCE_IDENTIFIER === $originalProperty && null === $expectedValue) {
                    continue;
                }

                $finalExpectedValue = self::getExpectedValue($originalProperty, $testConsignment) ?? $expectedValue;
                $getter             = self::createGetter($originalProperty);

                if (method_exists($consignment, $getter)) {
                    $value = $consignment->{$getter}();

                    self::assertEquals($finalExpectedValue, $value, TestCase::createMessage($getter));
                }
            }
        }
    }

    /**
     * @param                                                            $data
     * @param AbstractConsignment                                        $consignment
     *
     * @return void
     * @throws MissingFieldException
     */
    private function addCustomsDeclaration($data, AbstractConsignment $consignment): void
    {
        $customsDeclaration = $data[self::CUSTOMS_DECLARATION] ?? null;
        if (!$customsDeclaration) {
            return;
        }

        Utils::fillObject($consignment, $customsDeclaration);

        foreach ($customsDeclaration[self::CUSTOMS_DECLARATION_ITEMS] ?? [] as $customsItem) {
            $item = new MyParcelCustomsItem();
            Utils::fillObject($item, $customsItem);
            $consignment->addItem($item);
        }
    }

    /**
     * @param                                                            $data
     * @param AbstractConsignment                                        $consignment
     *
     * @return void
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws MissingFieldException
     * @throws \Exception
     */
    private function addDropOffPoint($data, AbstractConsignment $consignment): void
    {
        if (!\array_key_exists(self::ADD_DROPOFF_POINT, $data)) {
            return;
        }

        $dropOffPoints = (new DropOffPointWebService($consignment->getCarrier()))
            ->setApiKey($this->getApiKey())
            ->getDropOffPoints($consignment->getPostalCode())
        ;

        self::assertNotEmpty($dropOffPoints);

        $consignment->setDropOffPoint($dropOffPoints->first());
    }

    /**
     * @param $property
     *
     * @return string
     */
    private static function createGetter($property): string
    {
        if (array_key_exists($property, self::ALTERNATIVE_GETTERS_MAP)) {
            $getter = self::ALTERNATIVE_GETTERS_MAP[$property];
        } else {
            $getter = Str::camel('get_' . $property);
        }
        return $getter;
    }

    /**
     * Filters given $testData array by removing all values that are not in given array of keys.
     *
     * @param array $testData
     * @param array $filters - Keys to keep.
     *
     * @return array
     */
    private static function filterTestData(array $testData, array $filters): array
    {
        $onlyKeys = [];

        // Transform filters into a key/value array.
        foreach ($filters as $key) {
            $onlyKeys[$key] = null;
        }

        return array_intersect_key($testData, $onlyKeys);
    }

    /**
     * If there is a <property>_expected key in the test data, expect the result to be that value instead of the
     * value of the original property.
     *
     * @param string $property
     * @param array  $testData
     *
     * @return mixed
     */
    private static function getExpectedValue(string $property, array $testData)
    {
        $expectedKey = self::expected($property);

        return $testData[$expectedKey] ?? null;
    }

    /**
     * @param string $property
     *
     * @return string
     */
    private static function getOriginalProperty(string $property): string
    {
        if (Str::endsWith($property, self::EXPECTED_SUFFIX)) {
            return str_replace(self::EXPECTED_SUFFIX, '', $property);
        }

        return $property;
    }

    /**
     * @param array $testData
     *
     * @return array
     */
    private static function normalizeTestData(array $testData): array
    {
        // If only one set of consignment data is passed, wrap it in an array.
        if (Arr::isAssoc($testData)) {
            $testData = [$testData];
        }

        return $testData;
    }
}
