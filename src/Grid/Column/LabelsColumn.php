<?php

namespace Gett\MyparcelBE\Grid\Column;

use PrestaShop\PrestaShop\Core\Grid\Column\AbstractColumn;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class LabelsColumn extends AbstractColumn
{
    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'label';
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'sortable' => false,
                'clickable' => false,
            ])
        ;
    }
}