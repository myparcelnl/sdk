<?php

namespace Gett\MyParcelBE\Grid\Action\Type;

class DownloadLabelAction extends \PrestaShop\PrestaShop\Core\Grid\Action\Row\AbstractRowAction
{
    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'download_label';
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired([
                'route',
            ])
            ->setDefaults([
                'route_params' => [],
            ])
            ->setAllowedTypes('route', 'string')
            ->setAllowedTypes('route_params', 'array')
        ;
    }
}
