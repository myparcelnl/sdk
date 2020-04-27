<?php

namespace Gett\MyParcel\Controllers\Admin;

use Gett\MyParcel\Service\Consignment\Create;
use Gett\MyParcel\Service\Consignment\Update;
use Symfony\Component\HttpFoundation\Request;
use Gett\MyParcel\Service\Consignment\Download;
use Gett\MyParcel\Repository\OrderLabelRepository;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;

class LabelController extends FrameworkBundleAdminController
{
    public function createLabelsBulk(Request $request)
    {
        /** @var OrderLabelRepository $repository */
        $repository = $this->get('gett.myparcel.repository.order_label_repository');

        /** @var Create $service */
        $service = $this->get('gett.myparcel.service.consignment.create');

        $orders = $repository->getOrdersForLabelCreate($request->get('order_orders_bulk'));

        $service->createLabels($orders);

        return $this->redirectToRoute('admin_orders_index');
    }

    public function createLabel(Request $request)
    {
        /** @var OrderLabelRepository $repository */
        $repository = $this->get('gett.myparcel.repository.order_label_repository');

        /** @var Create $service */
        $service = $this->get('gett.myparcel.service.consignment.create');
        $order = $repository->getOrdersForLabelCreate($request->get('create_label')['order_ids']);

        $service->createLabels($order[0]);

//        return $this->json([
//            'status' => 'ok',  //TODO For presta bellow 1.7.7
//        ]);

        return $this->redirectToRoute('admin_orders_index');
    }

    public function downloadLabel(Request $request)
    {
        /** @var Download $service */
        $service = $this->get('gett.myparcel.service.consignment.download');

        $service->downloadLabel([$request->get('label_id')]);
    }

    public function downloadLabelsBulk(Request $request)
    {
        /** @var OrderLabelRepository $repository */
        $repository = $this->get('gett.myparcel.repository.order_label_repository');

        $labels = $repository->getOrderLabels($request->get('print_label')['order_ids']);

        /** @var Download $service */
        $service = $this->get('gett.myparcel.service.consignment.download');

        $service->downloadLabel($labels);
    }

    public function updateLabel(Request $request)
    {
        /** @var Update $service */
        $service = $this->get('gett.myparcel.service.consignment.update');

        $service->updateLabel([$request->get('labelId')]);

        return $this->redirectToRoute('admin_orders_index');
    }

    public function refreshLabelsBulk(Request $request)
    {
        /** @var OrderLabelRepository $repository */
        $repository = $this->get('gett.myparcel.repository.order_label_repository');
        $labels = $repository->getOrderLabels($request->get('order_orders_bulk'));

        /** @var Update $service */
        $service = $this->get('gett.myparcel.service.consignment.update');
        $service->updateLabel($labels);

        return $this->redirectToRoute('admin_orders_index');
    }

    public function createLabelReturn(Request $request)
    {
        /** @var OrderLabelRepository $repository */
        $repository = $this->get('gett.myparcel.repository.order_label_repository');

        /** @var Create $service */
        $service = $this->get('gett.myparcel.service.consignment.create');
        $order = $repository->getOrdersForLabelCreate($request->get('create_label')['order_ids']);

        $service->createReturnLabel($order[0]);

//        return $this->json([
//            'status' => 'ok',  //TODO For presta bellow 1.7.7
//        ]);

        return $this->redirectToRoute('admin_orders_index');
    }
}
