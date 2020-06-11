<?php

namespace Gett\MyparcelBE\Grid\Action\Bulk;

use PrestaShop\PrestaShop\Core\Grid\Action\Bulk\AbstractBulkAction;

class CreateLabelBulkAction extends AbstractBulkAction
{
    public function getType()
    {
        return 'create_label';
    }

    protected function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'submit_route' => 'admin_myparcel_orders_label_bulk_create',
            ])
        ;
    }
}
