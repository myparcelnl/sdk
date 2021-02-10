<?php

namespace Gett\MyparcelBE\Grid\Action\Bulk;

use PrestaShop\PrestaShop\Core\Grid\Action\Bulk\AbstractBulkAction;

class IconBulkAction extends AbstractBulkAction
{
    public function getType(): string
    {
        return 'icon_button';
    }

    protected function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver)
    {
        $resolver
            ->setRequired([
                'submit_route',
                'material_icon',
            ])
            ->setAllowedTypes('submit_route', ['string', 'null'])
            ->setAllowedTypes('material_icon', 'string')
        ;
    }
}
