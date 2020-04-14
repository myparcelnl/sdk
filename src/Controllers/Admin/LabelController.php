<?php

namespace Gett\MyParcel\Controllers\Admin;

use Gett\MyParcel\Service\ConsignmentService;
use Symfony\Component\HttpFoundation\Request;
use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use Gett\MyParcel\Repository\OrderLabelRepository;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;

class LabelController extends FrameworkBundleAdminController
{
    public function createLabelsBulk(Request $request)
    {
        /** @var OrderLabelRepository $repository */
        $repository = $this->get('gett.myparcel.repository.order_label_repository');

        /** @var ConsignmentService $service */
        $service = $this->get('gett.myparcel.service.consignment_service');

        $orders = $repository->getOrdersForLabelCreate($request->get('create_label')['order_ids']);

        $service->createLabels($orders);

//        $myParcelCollection = (new MyParcelCollection())
//            ->addConsignment($consignment)
//            ->setPdfOfLabels()
//        ;
//
//        //$consignmentId = $myParcelCollection->first()->getConsignmentId();
//
//        $myParcelCollection->downloadPdfOfLabels();
    }
}
