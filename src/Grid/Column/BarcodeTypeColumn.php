<?php

namespace Gett\MyparcelBE\Grid\Column;

use PrestaShop\PrestaShop\Core\Grid\Column\AbstractColumn;

class BarcodeTypeColumn extends AbstractColumn
{
    public function getType()
    {
        return 'myparcel_barcode';
    }

    protected function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver)
    {
        $resolver
            ->setRequired([
                'barcode',
            ])
            ->setAllowedTypes('barcode', 'string')
        ;
    }
}
