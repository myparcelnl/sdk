<?php

namespace Gett\MyParcel\Module\Configuration;

use Gett\MyParcel\Sdk\src\Services\Tracktrace;

class LabelForm extends AbstractForm
{
    protected $icon = 'cog';

    protected function getLegend(): string
    {
        return $this->module->l('Label Settings');
    }

    protected function getFields(): array
    {
        $a = new Tracktrace(\Configuration::get(\Gett\MyParcel\Constant::MY_PARCEL_API_KEY_CONFIGURATION_NAME));
        echo '<pre>';
        var_dump($a->getTrackTrace(15002021));
        die();

        return [
            'MY_PARCEL_LABEL_DESCRIPTION' => [
                'type' => 'select',
                'label' => $this->module->l('Label description'),
                'name' => 'MY_PARCEL_LABEL_DESCRIPTION',
                'options' => [
                    'query' => [
                        ['id' => 'id_order', 'name' => 'Order ID'],
                        ['id' => 'reference', 'name' => 'Order Reference'],
                    ],
                    'id' => 'id',
                    'name' => 'name',
                ],
            ],
            'MY_PARCEL_LABEL_SIZE' => [
                'type' => 'select',
                'label' => $this->module->l('Default label size'),
                'name' => 'MY_PARCEL_LABEL_SIZE',
                'options' => [
                    'query' => [
                        ['id' => 'a4', 'name' => 'A4'],
                        ['id' => 'a6', 'name' => 'A6'],
                    ],
                    'id' => 'id',
                    'name' => 'name',
                ],
            ],
            'MY_PARCEL_LABEL_POSITION' => [
                'type' => 'select',
                'label' => $this->module->l('Default label position'),
                'name' => 'MY_PARCEL_LABEL_POSITION',
                'options' => [
                    'query' => [
                        ['id' => '1', 'name' => $this->module->l('Top left')],
                        ['id' => '3', 'name' => $this->module->l('Top right')],
                        ['id' => '2', 'name' => $this->module->l('Bottom left')],
                        ['id' => '4', 'name' => $this->module->l('Bottom right')],
                    ],
                    'id' => 'id',
                    'name' => 'name',
                ],
            ],
            'MY_PARCEL_LABEL_OPEN_DOWNLOAD' => [
                'type' => 'select',
                'label' => $this->module->l('Open or download label'),
                'name' => 'MY_PARCEL_LABEL_OPEN_DOWNLOAD',
                'options' => [
                    'query' => [
                        ['id' => 'true', 'name' => $this->module->l('Open')],
                        ['id' => 'false', 'name' => $this->module->l('Download')],
                    ],
                    'id' => 'id',
                    'name' => 'name',
                ],
            ],
            'MY_PARCEL_LABEL_PROMPT_POSITION' => [
                'type' => 'switch',
                'label' => $this->module->l('Prompt for label position'),
                'name' => 'MY_PARCEL_LABEL_PROMPT_POSITION',
                'required' => false,
                'is_bool' => true,
                'values' => [
                    [
                        'id' => 'active_on',
                        'value' => 1,
                        'label' => $this->module->l('Yes'),
                    ],
                    [
                        'id' => 'active_off',
                        'value' => 0,
                        'label' => $this->module->l('No'),
                    ],
                ],
            ],
        ];
    }
}
