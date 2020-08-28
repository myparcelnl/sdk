<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\Tests\ReturnLabelTest;

use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;

class SendMultipleReturnLabelTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return $this
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     */
    public function testSendMultipleReturnLabel()
    {
        if (getenv('API_KEY') == null) {
            echo "\033[31m Set MyParcel API-key in 'Environment variables' before running UnitTest. Example: API_KEY=f8912fb260639db3b1ceaef2730a4b0643ff0c31. PhpStorm example: http://take.ms/sgpgU5\n\033[0m";

            return $this;
        }

        $myParcelCollection = new MyParcelCollection();

        $consignment = (new PostNLConsignment())
            ->setApiKey(getenv('API_KEY'))
            ->setCountry('NL')
            ->setPerson('Richard')
            ->setCompany('MyParcel')
            ->setFullStreet('Hoofdweg 679')
            ->setPostalCode('2131BC')
            ->setCity('Hoofddorp')
            ->setPackageType(PostNLConsignment::PACKAGE_TYPE_PACKAGE)
            ->setLabelDescription('first consignment');
        $myParcelCollection->addConsignment($consignment);

        $consignment = (new PostNLConsignment())
            ->setApiKey(getenv('API_KEY'))
            ->setCountry('NL')
            ->setPerson('Richard')
            ->setCompany('MyParcel')
            ->setFullStreet('Hoofdweg 679')
            ->setPostalCode('2131BC')
            ->setCity('Hoofddorp')
            ->setPackageType(PostNLConsignment::PACKAGE_TYPE_PACKAGE)
            ->setLabelDescription('second consignment');
        $myParcelCollection->addConsignment($consignment);

        $myParcelCollection
            ->generateReturnConsignments(
                false,
                function (
                    AbstractConsignment $returnConsignment,
                    AbstractConsignment $parent
                ): AbstractConsignment {
                    $returnConsignment->setLabelDescription(
                        'Return: ' . $parent->getLabelDescription()
                    );

                    return $returnConsignment;
                }
            );

        $this->assertEquals('first consignment', $myParcelCollection[0]->getLabelDescription());
        $this->assertEquals('Return: first consignment', $myParcelCollection[1]->getLabelDescription());
        $this->assertEquals('second consignment', $myParcelCollection[2]->getLabelDescription());
        $this->assertEquals('Return: second consignment', $myParcelCollection[3]->getLabelDescription());

//            ->setLinkOfLabels();

//        $this->assertContains('myparcel.nl/pdfs', $myParcelCollection->getLinkOfLabels());
    }
}
