<?php

namespace Gett\MyParcel\Module\Configuration;

use Tools;
use Configuration;
use Gett\MyParcel\Constant;
use Gett\MyParcel\Service\WebhookService;
use Gett\MyParcel\Model\Webhook\Subscription;

class ApiForm extends AbstractForm
{
    protected $icon = 'cog';

    public function getButtons()
    {
        $buttons = [
            'reset' => [
                'title' => $this->module->l('Create webhook', 'apiform'),
                'name' => 'resetHook',
                'type' => 'submit',
                'class' => 'btn btn-default pull-left',
                'icon' => 'process-icon-reset',
            ],
        ];

        if (Configuration::get(Constant::MY_PARCEL_WEBHOOK_ID_CONFIGURATION_NAME)) {
            $buttons['reset']['title'] = $this->module->l('Refresh Webhook', 'apiform');
            $buttons['delete'] = [
                'title' => $this->module->l('Delete Webhook', 'apiform'),
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
        return $this->module->l('API Settings', 'apiform');
    }

    protected function getFields(): array
    {
        return [
            Constant::MY_PARCEL_API_KEY_CONFIGURATION_NAME => [
                'type' => 'text',
                'label' => $this->module->l('Your API key', 'apiform'),
                'name' => Constant::MY_PARCEL_API_KEY_CONFIGURATION_NAME,
                'required' => false,
            ],
            Constant::MY_PARCEL_API_LOGGING_CONFIGURATION_NAME => [
                'type' => 'switch',
                'label' => $this->module->l('Api logging', 'apiform'),
                'name' => Constant::MY_PARCEL_API_LOGGING_CONFIGURATION_NAME,
                'required' => false,
                'is_bool' => true,
                'values' => [
                    [
                        'id' => 'active_on',
                        'value' => 1,
                        'label' => $this->module->l('Enabled', 'apiform'),
                    ],
                    [
                        'id' => 'active_off',
                        'value' => 0,
                        'label' => $this->module->l('Disabled', 'apiform'),
                    ],
                ],
            ],
        ];
    }

    protected function update(): string
    {
        $parent = parent::update();

        try {
            if ((Tools::isSubmit(Constant::MY_PARCEL_API_KEY_CONFIGURATION_NAME) && Tools::getValue(Constant::MY_PARCEL_API_KEY_CONFIGURATION_NAME) != Configuration::get(Constant::MY_PARCEL_API_KEY_CONFIGURATION_NAME)) || Tools::isSubmit('resetHook')) {
                $service = new WebhookService(Tools::getValue(Constant::MY_PARCEL_API_KEY_CONFIGURATION_NAME));
                $result = $service->addSubscription(
                    new Subscription(
                        Subscription::SHIPMENT_STATUS_CHANGE_HOOK_NAME,
                        rtrim(
                            (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://')
                                . Tools::getShopDomainSsl()
                                . __PS_BASE_URI__,
                            '/'
                        ) . "/index.php?fc=module&module={$this->module->name}&controller=hook"
                    )
                );

                if (isset($result['data']['ids'][0]['id'])) {
                    Configuration::updateValue(
                        Constant::MY_PARCEL_WEBHOOK_ID_CONFIGURATION_NAME,
                        $result['data']['ids'][0]['id']
                    );
                }
            }
            if (Tools::isSubmit('deleteHook')) {
                $service = new WebhookService(Configuration::get(Constant::MY_PARCEL_API_KEY_CONFIGURATION_NAME));
                $result = $service->deleteSubscription(
                    (int) Configuration::get(Constant::MY_PARCEL_WEBHOOK_ID_CONFIGURATION_NAME)
                );
                if ($result === true) {
                    Configuration::updateValue(Constant::MY_PARCEL_WEBHOOK_ID_CONFIGURATION_NAME, '');
                }
            }
        } catch (\Exception $e) {
            return $this->module->displayError($e->getMessage());
        }

        return $parent;
    }
}
