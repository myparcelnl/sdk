<?php

namespace Gett\MyparcelBE\Grid\Action\Bulk;

use PrestaShop\PrestaShop\Core\Grid\Action\Bulk\AbstractBulkAction;

class IconModalBulkAction extends AbstractBulkAction
{
    public function getType(): string
    {
        return 'icon_modal';
    }

    protected function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver)
    {
        $resolver
            ->setRequired([
                'submit_route',
                'modal_id',
                'material_icon',
            ])
            ->setAllowedTypes('submit_route', ['string', 'null'])
            ->setAllowedTypes('modal_id', 'string')
            ->setAllowedTypes('material_icon', 'string')
        ;
    }
}
