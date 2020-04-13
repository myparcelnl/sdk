<?php

namespace Gett\MyParcel\Controllers\Admin;

use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Factory\ConsignmentFactory;
use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;

class LabelController extends FrameworkBundleAdminController
{
    public function createLabelsBulk()
    {
        $consignment = (ConsignmentFactory::createByCarrierId(PostNLConsignment::CARRIER_ID))
            ->setApiKey('2ec2bfbd6f6ef4f5f744c71846d2d4333a8cd710')
            ->setReferenceId('Order Test')
            ->setCountry('NL')
            ->setPerson('Piet Hier')
            ->setFullStreet('Plein 1945 55b')
            ->setPostalCode('2231JE')
            ->setCity('Amsterdam')
            ->setEmail('piet.hier@test.nl')
        ;

        $myParcelCollection = (new MyParcelCollection())
            ->addConsignment($consignment)
            ->setPdfOfLabels()
        ;

        //$consignmentId = $myParcelCollection->first()->getConsignmentId();

        $myParcelCollection->downloadPdfOfLabels();
    }
}
