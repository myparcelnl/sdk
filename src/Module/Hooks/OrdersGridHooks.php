<?php

namespace Gett\MyParcel\Module\Hooks;

trait OrdersGridHooks
{
    public function hookActionOrderGridQueryBuilderModifier(array $params)
    {
        /** @var \Doctrine\DBAL\Query\QueryBuilder $searchQueryBuilder */
        $searchQueryBuilder = $params['search_query_builder'];

        $searchQueryBuilder->addSelect(
            'group_concat(mol.barcode ORDER BY mol.barcode) as barcode,
             group_concat(mol.track_link ORDER BY mol.barcode) as track_link,
             group_concat(status ORDER BY mol.barcode) as status,
             group_concat(id_label ORDER BY mol.barcode) as ids
             '
        );
        $searchQueryBuilder->leftJoin(
            'o',
            _DB_PREFIX_ . 'myparcel_order_label',
            'mol',
            'o.id_order = mol.id_order'
        );
        $searchQueryBuilder->addGroupBy('o.id_order');
    }

    public function hookActionOrderGridDefinitionModifier(array $params)
    {
        /** @var \PrestaShop\PrestaShop\Core\Grid\Definition\GridDefinitionInterface $definition */
        $definition = $params['definition'];

        foreach ($definition->getColumns() as $column) {
            if ($column->getName() == 'Actions') {
                $column->getOptions()['actions']->add((new \Gett\MyParcel\Grid\Action\Row\Type\CreateLabelAction('create_label'))
                    ->setName($this->l('Create Label'))
                    ->setIcon('receipt')
                    ->setOptions([
                        'submit_route' => 'admin_myparcel_orders_label_bulk_create',
                    ]));
            }
        }

        $definition->getBulkActions()->add(
            (new \PrestaShop\PrestaShop\Core\Grid\Action\Bulk\Type\SubmitBulkAction('create_label'))
                ->setName($this->l('Create label'))
                ->setOptions([
                    'submit_route' => 'admin_myparcel_orders_label_bulk_create',
                ])
        );
        $definition->getBulkActions()->add(
            (new \PrestaShop\PrestaShop\Core\Grid\Action\Bulk\Type\ModalFormSubmitBulkAction('print_label'))
                ->setName($this->l('Print labels'))
                ->setOptions([
                    'submit_route' => 'admin_myparcel_orders_label_bulk_print',
                    'modal_id' => 'bulk-print-modal',
                ])
        );
        $definition->getBulkActions()->add(
            (new \PrestaShop\PrestaShop\Core\Grid\Action\Bulk\Type\SubmitBulkAction('refresh_labels'))
                ->setName($this->l('Refresh labels'))
                ->setOptions([
                    'submit_route' => 'admin_myparcel_orders_label_bulk_refresh',
                ])
        );
        $definition
            ->getColumns()
            ->addAfter(
                'osname',
                (new \Gett\MyParcel\Grid\Column\BarcodeTypeColumn('barcode'))
                    ->setName($this->l('Barcode'))
                    ->setOptions([
                        'barcode' => 'Barcode Example',
                    ])
            )
        ;
    }

    public function hookDisplayAdminOrderMainBottom($params)
    {
        $this->context->smarty->assign(
            ['action' => $this->getAdminLink('Label', true, ['action' => 'return', 'id_order' => $this->getOrderId()])]
        );
        return $this->display($this->name, 'views/templates/admin/order/return-form.tpl');
    }

    private function getOrderId(): int
    {
        $parts = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        foreach ($parts as $part) {
            if (is_numeric($part)){
                return $part;
            }
        }

        return 0;
    }
}
