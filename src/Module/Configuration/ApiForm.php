<?php

namespace Gett\MyParcel\Module\Configuration;

use Tools;
use Configuration;
use Gett\MyParcel\Constant;
use Gett\MyParcel\Sdk\src\Services\WebhookService;
use Gett\MyParcel\Sdk\src\Model\Webhook\Subscription;

class ApiForm extends AbstractForm
{
    protected $icon = 'cog';

    public function getButtons()
    {
        $buttons = [
            'reset' => [
                'title' => $this->module->l('Refresh'),
                'name' => 'resetHook',
                'type' => 'submit',
                'class' => 'btn btn-default pull-left',
                'icon' => 'process-icon-reset',
            ],
        ];

        if (Configuration::get('MY_PARCEL_WEBHOOK_ID')) {
            $buttons['delete'] = [
                'title' => $this->module->l('Delete'),
                'name' => 'deleteHook',
                'type' => 'submit',
                'class' => 'btn btn-default pull-left',
                'icon' => 'process-icon-delete',
            ];
        }

        return $buttons;
    }

    protected function getLegend(): string
    {
        return $this->module->l('API Settings');
    }

    protected function getFields(): array
    {
        return [
            Constant::MY_PARCEL_API_KEY_CONFIGURATION_NAME => [
                'type' => 'text',
                'label' => $this->module->l('Your API key'),
                'name' => Constant::MY_PARCEL_API_KEY_CONFIGURATION_NAME,
                'required' => false,
            ],
            Constant::MY_PARCEL_API_LOGGING_CONFIGURATION_NAME => [
                'type' => 'switch',
                'label' => $this->module->l('Api logging'),
                'name' => Constant::MY_PARCEL_API_LOGGING_CONFIGURATION_NAME,
                'required' => false,
                'is_bool' => true,
                'values' => [
                    [
                        'id' => 'active_on',
                        'value' => 1,
                        'label' => $this->module->l('Enabled'),
                    ],
                    [
                        'id' => 'active_off',
                        'value' => 0,
                        'label' => $this->module->l('Disabled'),
                    ],
                ],
            ],
        ];
    }

    protected function update(): string
    {
        $parent = parent::update();

        if ((Tools::isSubmit('MY_PARCEL_API_KEY') && Tools::getValue('MY_PARCEL_API_KEY') != Configuration::get('MY_PARCEL_API_KEY')) || Tools::isSubmit('resetHook')) {
            $service = new WebhookService(Tools::getValue('MY_PARCEL_API_KEY'));
            $result = $service->addSubscription(
                new Subscription(
                    Subscription::SHIPMENT_STATUS_CHANGE_HOOK_NAME,
                    rtrim(
                        (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://') . Tools::getShopDomainSsl() . __PS_BASE_URI__,
                        '/'
                    ) . "/index.php?fc=module&module={$this->module->name}&controller=hook"
                )
            );

            if (isset($result['data']['ids'][0]['id'])) {
                Configuration::updateValue(
                    'MY_PARCEL_WEBHOOK_ID',
                    Tools::getValue($result['data']['ids'][0]['id'])
                );
            }
        }
        if (Tools::isSubmit('deleteHook')) {
            $service = new WebhookService(Tools::getValue('MY_PARCEL_API_KEY'));
            $service->deleteSubscription(Tools::getValue('MY_PARCEL_WEBHOOK_ID'));
            Configuration::updateValue('MY_PARCEL_WEBHOOK_ID', '');
        }

        return $parent;
    }
}
