<?php

namespace Gett\MyparcelBE\Module\Hooks;

use Gett\MyparcelBE\Constant;

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
                $column->getOptions()['actions']->add((new \Gett\MyparcelBE\Grid\Action\Row\Type\CreateLabelAction('create_label'))
                    ->setName($this->l('Create Label', 'ordersgridhooks'))
                    ->setIcon('receipt')
                    ->setOptions([
                        'submit_route' => 'admin_myparcel_orders_label_bulk_create',
                    ]));
            }
        }

        $definition->getBulkActions()->add(
            (new \PrestaShop\PrestaShop\Core\Grid\Action\Bulk\Type\SubmitBulkAction('create_label'))
                ->setName($this->l('Create label', 'ordersgridhooks'))
                ->setOptions([
                    'submit_route' => 'admin_myparcel_orders_label_bulk_create',
                ])
        );
        $definition->getBulkActions()->add(
            (new \PrestaShop\PrestaShop\Core\Grid\Action\Bulk\Type\ModalFormSubmitBulkAction('print_label'))
                ->setName($this->l('Print labels', 'ordersgridhooks'))
                ->setOptions([
                    'submit_route' => 'admin_myparcel_orders_label_bulk_print',
                    'modal_id' => 'bulk-print-modal',
                ])
        );
        $definition->getBulkActions()->add(
            (new \PrestaShop\PrestaShop\Core\Grid\Action\Bulk\Type\SubmitBulkAction('refresh_labels'))
                ->setName($this->l('Refresh labels', 'ordersgridhooks'))
                ->setOptions([
                    'submit_route' => 'admin_myparcel_orders_label_bulk_refresh',
                ])
        );
        $definition
            ->getColumns()
            ->addAfter(
                'osname',
                (new \Gett\MyparcelBE\Grid\Column\BarcodeTypeColumn('barcode'))
                    ->setName($this->l('Barcode', 'ordersgridhooks'))
                    ->setOptions([
                        'barcode' => 'Barcode Example',
                    ])
            )
        ;
    }

    public function hookDisplayAdminOrderMainBottom($params)
    {
        $this->context->smarty->assign([
            'action' => $this->getAdminLink(
                'Label',
                true,
                ['action' => 'return', 'id_order' => $this->getOrderId()]
            ),
            'isBE' => $this->isBE(),
            'PACKAGE_TYPE' => Constant::PACKAGE_TYPE_CONFIGURATION_NAME,
            'ONLY_RECIPIENT' => Constant::ONLY_RECIPIENT_CONFIGURATION_NAME,
            'AGE_CHECK' => Constant::AGE_CHECK_CONFIGURATION_NAME,
            'PACKAGE_FORMAT' => Constant::PACKAGE_FORMAT_CONFIGURATION_NAME,
            'RETURN_PACKAGE' => Constant::RETURN_PACKAGE_CONFIGURATION_NAME,
            'SIGNATURE_REQUIRED' => Constant::SIGNATURE_REQUIRED_CONFIGURATION_NAME,
            'INSURANCE' => Constant::INSURANCE_CONFIGURATION_NAME,
        ]);

        return $this->display($this->name, 'views/templates/admin/order/return-form.tpl');
    }

    private function getOrderId(): int
    {
        $parts = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        foreach ($parts as $part) {
            if (is_numeric($part)) {
                return $part;
            }
        }

        return 0;
    }
}
