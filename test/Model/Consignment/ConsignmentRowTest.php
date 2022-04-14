<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Consignment;

use MyParcelNL\Sdk\Test\Bootstrap\ConsignmentTestCase;

class ConsignmentRowTest extends ConsignmentTestCase
{
    /**
     * @return array
     * @throws \Exception
     */
    public function provideRowCountriesData(): array
    {
        return $this->createConsignmentProviderDataset([
            'Send to Canada' => $this->getDefaultAddress('CA')
                + [
                    self::CUSTOMS_DECLARATION       => $this->getDefaultCustomsDeclaration(),
                    self::expected(self::INSURANCE) => 200,
                ],
        ]);
    }

    /**
     * @throws \Exception
     */
    public function provideRowCountriesExceptionData(): array
    {
        return $this->createConsignmentProviderDataset([
            'Send to Canada without customs declaration'   => $this->getDefaultAddress('CA') + [
                    self::EXCEPTION => 'Product data must be set for international MyParcel shipments. Use addItem().',
                ],
            'Send to Canada with incomplete customs items' => $this->getDefaultAddress('CA') + [
                    self::CUSTOMS_DECLARATION => [
                        self::INVOICE                   => '12345',
                        self::CUSTOMS_DECLARATION_ITEMS => [[]],
                    ],
                    self::EXCEPTION           => 'Validation failed: "Field amount must be set. Field classification must be set. Field country must be set. Field description must be set. Field item_value must be set. Field weight must be set.',
                ],
        ]);
    }

    /**
     * @param  array $testData
     *
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     * @dataProvider provideRowCountriesData
     */
    public function testRowCountries(array $testData): void
    {
        $this->doConsignmentTest($testData);
    }

    /**
     * @param  array $testData
     *
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     * @dataProvider provideRowCountriesExceptionData
     */
    public function testRowCountriesException(array $testData): void
    {
        $this->doConsignmentTest($testData);
    }
}
