<?php

namespace Gett\MyparcelBE\Grid\Action\Row\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateLabelAction extends \PrestaShop\PrestaShop\Core\Grid\Action\Row\AbstractRowAction
{
    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'create_label';
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'submit_route' => 'admin_myparcelbe_order_label_create',
                'use_inline_display' => true,
            ])
        ;
    }
}
