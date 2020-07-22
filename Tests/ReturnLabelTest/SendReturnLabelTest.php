<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\Tests\ReturnLabelTest;

use MyParcelNL\Sdk\src\Factory\ConsignmentFactory;
use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;

class SendReturnLabelTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return $this
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testSendReturnLabel()
    {
        if (getenv('API_KEY') == null) {
            echo "\033[31m Set MyParcel API-key in 'Environment variables' before running UnitTest. Example: API_KEY=f8912fb260639db3b1ceaef2730a4b0643ff0c31. PhpStorm example: http://take.ms/sgpgU5\n\033[0m";

            return $this;
        }

        foreach ($this->additionProviderNewConsignment() as $consignmentTest) {
            $myParcelCollection = new MyParcelCollection();

            $consignment = (ConsignmentFactory::createByCarrierId($consignmentTest['carrier_id']))
                ->setApiKey($consignmentTest['api_key'])
                ->setCountry($consignmentTest['cc'])
                ->setPerson($consignmentTest['person'])
                ->setCompany($consignmentTest['company'])
                ->setFullStreet($consignmentTest['full_street_input'])
                ->setPostalCode($consignmentTest['postal_code'])
                ->setCity($consignmentTest['city'])
                ->setEmail($consignmentTest['email'])
                ->setPhone($consignmentTest['phone'])
                ->setPackageType($consignmentTest['package_type'])
                ->setLabelDescription($consignmentTest['label_description']);

            if (key_exists('only_recipient', $consignmentTest)) {
                $consignment->setOnlyRecipient($consignmentTest['only_recipient']);
            }

            if (key_exists('signature', $consignmentTest)) {
                $consignment->setSignature($consignmentTest['signature']);
            }

            if (key_exists('age_check', $consignmentTest)) {
                $consignment->setAgeCheck($consignmentTest['age_check']);
            }

            if (key_exists('return', $consignmentTest)) {
                $consignment->setReturn($consignmentTest['return']);
            }

            if (key_exists('large_format', $consignmentTest)) {
                $consignment->setLargeFormat($consignmentTest['large_format']);
            }

            if (key_exists('insurance', $consignmentTest)) {
                $consignment->setInsurance($consignmentTest['insurance']);
            }

            $myParcelCollection
                ->addConsignment($consignment)
                ->generateReturnConsignments(
                    false,
                    function (
                        AbstractConsignment $returnConsignment,
                        AbstractConsignment $parent
                    ): AbstractConsignment {
                        $returnConsignment->setLabelDescription(
                            'Return: ' . $parent->getLabelDescription() .
                            ' This label is valid until: ' . date("d-m-Y", strtotime("+ 28 day"))
                        );
                        $returnConsignment->setOnlyRecipient($parent->isOnlyRecipient());
                        $returnConsignment->setSignature($parent->isSignature());
                        $returnConsignment->setAgeCheck($parent->hasAgeCheck());
                        $returnConsignment->setReturn($parent->isReturn());
                        $returnConsignment->setLargeFormat($parent->isLargeFormat());
                        $returnConsignment->setInsurance($parent->getInsurance());

                        return $returnConsignment;
                    }
                )
                ->setLinkOfLabels();

            $this->assertContains('myparcel.nl/pdfs', $myParcelCollection->getLinkOfLabels());
        }
    }

    /**
     * Data for the test
     *
     * @return array
     */
    private function additionProviderNewConsignment()
    {
        return [
            [
                'api_key'           => getenv('API_KEY'),
                'carrier_id'        => PostNLConsignment::CARRIER_ID,
                'cc'                => 'NL',
                'person'            => 'Piet',
                'email'             => 'richard@myparcel.nl',
                'company'           => 'Mega Store',
                'full_street_input' => 'Koestraat 55',
                'number_suffix'     => '',
                'postal_code'       => '2231JE',
                'city'              => 'Katwijk',
                'phone'             => '123-45-235-435',
                'package_type'      => 1,
                'label_description' => '1234',
                'only_recipient'    => true,
                'signature'         => true,
                'age_check'         => true,
                'return'            => true,
                'large_format'      => true,
                'insurance'         => 250
            ]
        ];
    }
}
