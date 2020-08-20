<?php

namespace Gett\MyparcelBE\Module\Configuration;

use Currency;
use Db;
use Configuration;
use Gett\MyparcelBE\Constant;
use Tools;

class Carriers extends AbstractForm
{
    private $context;

    public function __construct(\Module $module)
    {
        parent::__construct($module);
        $this->context = \Context::getContext();
    }

    protected function getLegend(): string
    {
        return '';
    }

    protected function getFields(): array
    {
        return [];
    }

    public function __invoke(): string
    {
        if (Tools::isSubmit('submitMyparcelCarrierSettings')) {
            $dropOff = [];
            foreach (Tools::getAllValues() as $key => $value) {
                if (stripos($key, 'dropOffDays') !== false) {
                    $temp = explode('_', $key);
                    $dropOff[] = end($temp);
                }
            }
            if (isset($dropOff)) {
                $_POST['dropOffDays'] = implode(';', $dropOff);
            }
            foreach (Constant::CARRIER_CONFIGURATION_FIELDS as $value) {
                if (stripos($value, 'price') === 0) {
                    $price = Tools::getValue($value);
                    if (!empty($price) && !\Validate::isFloat($price)) {

                        switch ($value) {
                            case 'priceMorningDelivery':
                                $label = $this->module->l('Delivery morning price', 'carriers');
                                break;
                            case 'priceStandardDelivery':
                                $label = $this->module->l('Delivery standard price', 'carriers');
                                break;
                            case 'priceEveningDelivery':
                                $label = $this->module->l('Delivery evening price', 'carriers');
                                break;
                            case 'priceSignature':
                                $label = $this->module->l('Signature price', 'carriers');
                                break;
                            case 'priceOnlyRecipient':
                                $label = $this->module->l('Only recipient price', 'carriers');
                                break;
                            case 'pricePickup':
                                $label = $this->module->l('Pickup price', 'carriers');
                                break;
                            default:
                                $label = $this->module->l('Price field', 'carriers');
                                break;
                        }
                        $this->context->controller->errors[] = sprintf(
                            $this->module->l('Wrong price format for %s', 'carriers'),
                            $label
                        );
                        continue;
                    }
                }
                Db::getInstance()->update(
                    'myparcel_carrier_configuration',
                    ['value' => pSQL(Tools::getValue($value))],
                    'id_carrier = "' . Tools::getValue('id_carrier') . '" AND name = "' . pSQL($value) . '" '
                );
            }
        }

        if (Tools::isSubmit('updatecarrier')) {
            return $this->getForm();
        }

        return $this->getList();
    }

    private function getForm()
    {
        $currency = Currency::getDefaultCurrency();
        $carrier = $this->module->l('Carriers', 'carriers');
        $id_carrier = (int) Tools::getValue('id_carrier');
        if ($id_carrier){
            $carrier = (new \Carrier($id_carrier))->name;
        }

        $deliveryDaysOptions = array(
            array(
                'id'   => -1,
                'name' => $this->module->l('Hide days', 'carriers'),
            ),
        );
        for ($i = 1; $i < 15; $i++) {
            $deliveryDaysOptions[] = array(
                'id'   => $i,
                'name' => sprintf($this->module->l('%d days', 'carriers'), $i),
            );
        }

        $dropOffDelayOptions = array(
            array(
                'id'   => 0,
                'name' => $this->module->l('No delay', 'carriers'),
            ),
            array(
                'id'   => 1,
                'name' => $this->module->l('1 day', 'carriers'),
            ),
        );
        for ($i = 2; $i < 15; $i++) {
            $dropOffDelayOptions[] = array(
                'id'   => $i,
                'name' => sprintf($this->module->l('%d days', 'carriers'), $i),
            );
        }

        $fields = [
            'form' => [
                'legend' => [
                    'title' => $carrier,
                    'icon' => 'icon-truck',
                ],
                'tabs' => [
                    'form' => $this->module->l('Checkout delivery form', 'carriers'),
                    'delivery' => $this->module->l('Delivery', 'carriers'),
                    'return' => $this->module->l('Return', 'carriers'),
                ],
                'input' => [
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Delivery Title', 'carriers'),
                        'name' => 'deliveryTitle',
                        'tab' => 'form',
                        'desc' => $this->module->l('General delivery title', 'carriers'),
                    ],
                    [
                        'type' => 'checkbox',
                        'multiple' => true,
                        'label' => $this->module->l('Drop off days', 'carriers'),
                        'name' => 'dropOffDays',
                        'tab' => 'form',
                        'values' => [
                            'query' => [
                                ['day_number' => 1, 'name' => $this->module->l('Monday', 'carriers')],
                                ['day_number' => 2, 'name' => $this->module->l('Tuesday', 'carriers')],
                                ['day_number' => 3, 'name' => $this->module->l('Wednesday', 'carriers')],
                                ['day_number' => 4, 'name' => $this->module->l('Thursday', 'carriers')],
                                ['day_number' => 5, 'name' => $this->module->l('Friday', 'carriers')],
                                ['day_number' => 6, 'name' => $this->module->l('Saturday', 'carriers')],
                                ['day_number' => 7, 'name' => $this->module->l('Sunday', 'carriers')],
                            ],
                            'id' => 'day_number',
                            'name' => 'name',
                        ],
                        'desc' => $this->module->l('This option allows the Merchant to set the days she normally goes to PostNL to hand in her parcels. Monday is 1 and Saturday is 6.', 'carriers'),
                    ],
                    [
                        'type' => 'time',
                        'label' => $this->module->l('Cutoff Time', 'carriers'),
                        'name' => 'cutoffTime',
                        'tab' => 'form',
                        'desc' => $this->module->l('This option allows the Merchant to indicate the latest cut-off time before an order will still be picked, packed and dispatched on the same/first set dropoff day, taking into account the dropoff-delay. Industry standard default time is 17:00. For example, if cutoff time is 17:00, Monday is a delivery day and there\'s no delivery delay; all orders placed Monday before 17:00 will be dropped of at PostNL on that same Monday in time for the Monday collection and delivery on Tuesday.', 'carriers'),
                    ],
                    [
                        'type' => 'select',
                        'label' => $this->module->l('Delivery days window', 'carriers'),
                        'name' => 'deliveryDaysWindow',
                        'tab' => 'form',
                        'options'  => array(
                            'query' => $deliveryDaysOptions,
                            'id'    => 'id',
                            'name'  => 'name',
                        ),
                        'desc' => $this->module->l('This option allows the Merchant to set the number of days into the future for which she wants to show her consumers delivery options. For example; If set to 3 (days) in her checkout, a consumer ordering on Monday will see possible delivery options for Tuesday, Wednesday and Thursday (provided there is no drop-off delay, it\'s before the cut-off time and she goes to PostNL on Mondays). Min. is 1 and max. is 14.', 'carriers'),
                    ],
                    [
                        'type' => 'select',
                        'label' => $this->module->l('Drop off delay', 'carriers'),
                        'name' => 'dropOffDelay',
                        'tab' => 'form',
                        'options'  => array(
                            'query' => $dropOffDelayOptions,
                            'id'    => 'id',
                            'name'  => 'name',
                        ),
                        'desc' => $this->module->l('This option allows the Merchant to set the number of days it takes her to pick, pack and hand in her parcel at PostNL when ordered before the cutoff time. By default this is 0 and max. is 14.', 'carriers'),
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Delivery standard title', 'carriers'),
                        'name' => 'deliveryStandardTitle',
                        'tab' => 'form',
                        'desc' => $this->module->l('When there is no title, the delivery time will automatically be visible.', 'carriers'),
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Delivery standard price', 'carriers'),
                        'name' => 'priceStandardDelivery',
                        'suffix' => $currency->getSign(),
                        'class' => 'col-lg-2',
                        'tab' => 'form',
                    ],
                    [
                        'type' => 'switch',
                        'is_bool' => true,
                        'label' => $this->module->l('Allow monday delivery', 'carriers'),
                        'name' => 'allowMondayDelivery',
                        'tab' => 'form',
                        'desc' => $this->module->l('Monday delivery is only possible when the package is delivered before 15.00 on Saturday at the designated PostNL locations. Note: To activate Monday delivery value 6 must be given with dropOffDays and value 1 must be given by monday_delivery. On Saturday the cutoffTime must be before 15:00 (14:30 recommended) so that Monday will be shown.', 'carriers'),
                        'values' => [
                            [
                                'id' => 'allowMondayDelivery_on',
                                'value' => 1,
                                'label' => $this->module->l('Yes', 'carriers')
                            ],
                            [
                                'id' => 'allowMondayDelivery_off',
                                'value' => 0,
                                'label' => $this->module->l('No', 'carriers')
                            ],
                        ],
                    ],
                    [
                        'type' => 'time',
                        'label' => $this->module->l('Saturday cutoff time', 'carriers'),
                        'name' => 'saturdayCutoffTime',
                        'tab' => 'form',
                    ],
                    [
                        'type' => 'switch',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'allowMorningDelivery_on',
                                'value' => 1,
                                'label' => $this->module->l('Yes', 'carriers')
                            ],
                            [
                                'id' => 'allowMorningDelivery_off',
                                'value' => 0,
                                'label' => $this->module->l('No', 'carriers')
                            ],
                        ],
                        'label' => $this->module->l('Allow morning delivery', 'carriers'),
                        'name' => 'allowMorningDelivery',
                        'tab' => 'form',
                        'desc' => $this->module->l('Monday delivery is only possible when the package is delivered before 15.00 on Saturday at the designated PostNL locations. Note: To activate Monday delivery value 6 must be given with dropOffDays and value 1 must be given by monday_delivery. On Saturday the cutoffTime must be before 15:00 (14:30 recommended) so that Monday will be shown.', 'carriers'),

                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Delivery morning title', 'carriers'),
                        'name' => 'deliveryMorningTitle',
                        'tab' => 'form',
                        'desc' => $this->module->l('When there is no title, the delivery time will automatically be visible.', 'carriers'),
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Delivery morning price', 'carriers'),
                        'name' => 'priceMorningDelivery',
                        'suffix' => $currency->getSign(),
                        'class' => 'col-lg-2',
                        'tab' => 'form',
                    ],
                    [
                        'type' => 'switch',
                        'is_bool' => true,
                        'label' => $this->module->l('Allow evening delivery', 'carriers'),
                        'name' => 'allowEveningDelivery',
                        'tab' => 'form',
                        'values' => [
                            [
                                'id' => 'allowEveningDelivery_on',
                                'value' => 1,
                                'label' => $this->module->l('Yes', 'carriers')
                            ],
                            [
                                'id' => 'allowEveningDelivery_off',
                                'value' => 0,
                                'label' => $this->module->l('No', 'carriers')
                            ],
                        ],
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Delivery evening title', 'carriers'),
                        'name' => 'deliveryEveningTitle',
                        'tab' => 'form',
                        'desc' => $this->module->l('When there is no title, the delivery time will automatically be visible.', 'carriers'),
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Delivery evening price', 'carriers'),
                        'name' => 'priceEveningDelivery',
                        'suffix' => $currency->getSign(),
                        'class' => 'col-lg-2',
                        'tab' => 'form',
                    ],
                    [
                        'type' => 'switch',
                        'is_bool' => true,
                        'label' => $this->module->l('Allow signature', 'carriers'),
                        'name' => 'allowSignature',
                        'tab' => 'form',
                        'values' => [
                            [
                                'id' => 'allowSignature_on',
                                'value' => 1,
                                'label' => $this->module->l('Yes', 'carriers')
                            ],
                            [
                                'id' => 'allowSignature_off',
                                'value' => 0,
                                'label' => $this->module->l('No', 'carriers')
                            ],
                        ],
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Signature title', 'carriers'),
                        'name' => 'signatureTitle',
                        'tab' => 'form',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Signature price', 'carriers'),
                        'name' => 'priceSignature',
                        'suffix' => $currency->getSign(),
                        'class' => 'col-lg-2',
                        'tab' => 'form',
                    ],
                    [
                        'type' => 'switch',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'allowOnlyRecipient_on',
                                'value' => 1,
                                'label' => $this->module->l('Yes', 'carriers')
                            ],
                            [
                                'id' => 'allowOnlyRecipient_off',
                                'value' => 0,
                                'label' => $this->module->l('No', 'carriers')
                            ],
                        ],
                        'label' => $this->module->l('Allow only recipient', 'carriers'),
                        'name' => 'allowOnlyRecipient',
                        'tab' => 'form',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Only recipient title', 'carriers'),
                        'name' => 'onlyRecipientTitle',
                        'tab' => 'form',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Only recipient price', 'carriers'),
                        'name' => 'priceOnlyRecipient',
                        'suffix' => $currency->getSign(),
                        'class' => 'col-lg-2',
                        'tab' => 'form',
                    ],
                    [
                        'type' => 'switch',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'allowPickupPoints_on',
                                'value' => 1,
                                'label' => $this->module->l('Yes', 'carriers')
                            ],
                            [
                                'id' => 'allowPickupPoints_off',
                                'value' => 0,
                                'label' => $this->module->l('No', 'carriers')
                            ],
                        ],
                        'label' => $this->module->l('Allow pickup points', 'carriers'),
                        'name' => 'allowPickupPoints',
                        'tab' => 'form',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Pickup title', 'carriers'),
                        'name' => 'pickupTitle',
                        'tab' => 'form',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Pickup price', 'carriers'),
                        'name' => 'pricePickup',
                        'suffix' => $currency->getSign(),
                        'class' => 'col-lg-2',
                        'tab' => 'form',
                        'desc' => $this->module->l('It\'s possible to fill in a positive or negative amount. Would you like to give a discount for the use of this feature or would you like to calculate extra costs? If the amount is negative the price will appear green in the checkout.', 'carriers'),
                    ],
                    [
                        'type' => 'switch',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'allowPickupExpress_on',
                                'value' => 1,
                                'label' => $this->module->l('Yes', 'carriers')
                            ],
                            [
                                'id' => 'allowPickupExpress_off',
                                'value' => 0,
                                'label' => $this->module->l('No', 'carriers')
                            ],
                        ],
                        'label' => $this->module->l('Allow pickup express', 'carriers'),
                        'name' => 'allowPickupExpress',
                        'tab' => 'form',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Price pickup express', 'carriers'),
                        'name' => 'pricePickupExpress',
                        'tab' => 'form',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('BE delivery title', 'carriers'),
                        'name' => 'BEdeliveryTitle',
                        'tab' => 'form',
                    ],
                    [
                        'tab' => 'delivery',
                        'type' => 'select',
                        'label' => $this->module->l('Default package type', 'carriers'),
                        'name' => Constant::PACKAGE_TYPE_CONFIGURATION_NAME,
                        'options' => [
                            'query' => [
                                ['id' => 1, 'name' => $this->module->l('Parcel', 'carriers')],
                                ['id' => 2, 'name' => $this->module->l('Mailbox package', 'carriers')],
                                ['id' => 3, 'name' => $this->module->l('Letter', 'carriers')],
                                ['id' => 4, 'name' => $this->module->l('Digital stamp', 'carriers')],
                            ],
                            'id' => 'id',
                            'name' => 'name',
                        ],
                    ],
                    [
                        'tab' => 'delivery',
                        'type' => 'select',
                        'label' => $this->module->l('Default package format', 'carriers'),
                        'name' => Constant::PACKAGE_FORMAT_CONFIGURATION_NAME,
                        'options' => [
                            'query' => [
                                ['id' => 1, 'name' => $this->module->l('Normal', 'carriers')],
                                ['id' => 2, 'name' => $this->module->l('Large', 'carriers')],
                                ['id' => 3, 'name' => $this->module->l('Automatic', 'carriers')],
                            ],
                            'id' => 'id',
                            'name' => 'name',
                            'default' => [
                                'label' => $this->module->l('Select', 'carriers'),
                                'value' => 0,
                            ],
                        ],
                    ],
                    [
                        'type' => 'switch',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => Constant::ONLY_RECIPIENT_CONFIGURATION_NAME.'_on',
                                'value' => 1,
                                'label' => $this->module->l('Yes', 'carriers')
                            ],
                            [
                                'id' => Constant::ONLY_RECIPIENT_CONFIGURATION_NAME.'_off',
                                'value' => 0,
                                'label' => $this->module->l('No', 'carriers')
                            ],
                        ],
                        'label' => $this->module->l('Deliver only to recipient', 'carriers'),
                        'name' => Constant::ONLY_RECIPIENT_CONFIGURATION_NAME,
                        'tab' => 'delivery',
                    ],
                    [
                        'type' => $this->getExclusiveFieldType(Constant::AGE_CHECK_CONFIGURATION_NAME),
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => Constant::AGE_CHECK_CONFIGURATION_NAME.'_on',
                                'value' => 1,
                                'label' => $this->module->l('Yes', 'carriers')
                            ],
                            [
                                'id' => Constant::AGE_CHECK_CONFIGURATION_NAME.'_off',
                                'value' => 0,
                                'label' => $this->module->l('No', 'carriers')
                            ],
                        ],
                        'label' => $this->module->l('Age check', 'carriers'),
                        'name' => Constant::AGE_CHECK_CONFIGURATION_NAME,
                        'tab' => 'delivery',
                    ],
                    [
                        'type' => $this->getExclusiveFieldType(Constant::RETURN_PACKAGE_CONFIGURATION_NAME),
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => Constant::RETURN_PACKAGE_CONFIGURATION_NAME.'_on',
                                'value' => 1,
                                'label' => $this->module->l('Yes', 'carriers')
                            ],
                            [
                                'id' => Constant::RETURN_PACKAGE_CONFIGURATION_NAME.'_off',
                                'value' => 0,
                                'label' => $this->module->l('No', 'carriers')
                            ],
                        ],
                        'label' => $this->module->l('Return package when recipient is not home', 'carriers'),
                        'name' => Constant::RETURN_PACKAGE_CONFIGURATION_NAME,
                        'tab' => 'delivery',
                    ],
                    [
                        'type' => 'switch',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => Constant::SIGNATURE_REQUIRED_CONFIGURATION_NAME.'_on',
                                'value' => 1,
                                'label' => $this->module->l('Yes', 'carriers')
                            ],
                            [
                                'id' => Constant::SIGNATURE_REQUIRED_CONFIGURATION_NAME.'_off',
                                'value' => 0,
                                'label' => $this->module->l('No', 'carriers')
                            ],
                        ],
                        'label' => $this->module->l('Recipient need to sign', 'carriers'),
                        'name' => Constant::SIGNATURE_REQUIRED_CONFIGURATION_NAME,
                        'tab' => 'delivery',
                    ],
                    [
                        'type' => 'switch',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => Constant::INSURANCE_CONFIGURATION_NAME.'_on',
                                'value' => 1,
                                'label' => $this->module->l('Yes', 'carriers')
                            ],
                            [
                                'id' => Constant::INSURANCE_CONFIGURATION_NAME.'_off',
                                'value' => 0,
                                'label' => $this->module->l('No', 'carriers')
                            ],
                        ],
                        'label' => $this->module->l('Package with insurance', 'carriers'),
                        'name' => Constant::INSURANCE_CONFIGURATION_NAME,
                        'tab' => 'delivery',
                    ],

                    [
                        'tab' => 'return',
                        'type' => 'select',
                        'label' => $this->module->l('Default package type', 'carriers'),
                        'name' => 'return_' . Constant::PACKAGE_TYPE_CONFIGURATION_NAME,
                        'options' => [
                            'query' => [
                                ['id' => 1, 'name' => $this->module->l('Parcel', 'carriers')],
                                ['id' => 2, 'name' => $this->module->l('Mailbox package', 'carriers')],
                                ['id' => 3, 'name' => $this->module->l('Letter', 'carriers')],
                                ['id' => 4, 'name' => $this->module->l('Digital stamp', 'carriers')],
                            ],
                            'id' => 'id',
                            'name' => 'name',
                        ],
                    ],
                    [
                        'tab' => 'return',
                        'type' => 'select',
                        'label' => $this->module->l('Default package format', 'carriers'),
                        'name' => 'return_' . Constant::PACKAGE_FORMAT_CONFIGURATION_NAME,
                        'options' => [
                            'query' => [
                                ['id' => 1, 'name' => $this->module->l('Normal', 'carriers')],
                                ['id' => 2, 'name' => $this->module->l('Large', 'carriers')],
                                ['id' => 3, 'name' => $this->module->l('Automatic', 'carriers')],
                            ],
                            'id' => 'id',
                            'name' => 'name',
                            'default' => [
                                'label' => $this->module->l('Select', 'carriers'),
                                'value' => 0,
                            ],
                        ],
                    ],
                    [
                        'type' => 'switch',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'return_' . Constant::ONLY_RECIPIENT_CONFIGURATION_NAME.'_on',
                                'value' => 1,
                                'label' => $this->module->l('Yes', 'carriers')
                            ],
                            [
                                'id' => 'return_' . Constant::ONLY_RECIPIENT_CONFIGURATION_NAME.'_off',
                                'value' => 0,
                                'label' => $this->module->l('No', 'carriers')
                            ],
                        ],
                        'label' => $this->module->l('Deliver only to recipient', 'carriers'),
                        'name' => 'return_' . Constant::ONLY_RECIPIENT_CONFIGURATION_NAME,
                        'tab' => 'return',
                    ],
                    [
                        'type' => $this->getExclusiveFieldType('return_' . Constant::AGE_CHECK_CONFIGURATION_NAME),
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'return_' . Constant::AGE_CHECK_CONFIGURATION_NAME.'_on',
                                'value' => 1,
                                'label' => $this->module->l('Yes', 'carriers')
                            ],
                            [
                                'id' => 'return_' . Constant::AGE_CHECK_CONFIGURATION_NAME.'_off',
                                'value' => 0,
                                'label' => $this->module->l('No', 'carriers')
                            ],
                        ],
                        'label' => $this->module->l('Age check', 'carriers'),
                        'name' => 'return_' . Constant::AGE_CHECK_CONFIGURATION_NAME,
                        'tab' => 'return',
                    ],
                    [
                        'type' => $this->getExclusiveFieldType('return_' . Constant::RETURN_PACKAGE_CONFIGURATION_NAME),
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'return_' . Constant::RETURN_PACKAGE_CONFIGURATION_NAME.'_on',
                                'value' => 1,
                                'label' => $this->module->l('Yes', 'carriers')
                            ],
                            [
                                'id' => 'return_' . Constant::RETURN_PACKAGE_CONFIGURATION_NAME.'_off',
                                'value' => 0,
                                'label' => $this->module->l('No', 'carriers')
                            ],
                        ],
                        'label' => $this->module->l('Return package when recipient is not home', 'carriers'),
                        'name' => 'return_' . Constant::RETURN_PACKAGE_CONFIGURATION_NAME,
                        'tab' => 'return',
                    ],
                    [
                        'type' => 'switch',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'return_' . Constant::SIGNATURE_REQUIRED_CONFIGURATION_NAME.'_on',
                                'value' => 1,
                                'label' => $this->module->l('Yes', 'carriers')
                            ],
                            [
                                'id' => 'return_' . Constant::SIGNATURE_REQUIRED_CONFIGURATION_NAME.'_off',
                                'value' => 0,
                                'label' => $this->module->l('No', 'carriers')
                            ],
                        ],
                        'label' => $this->module->l('Recipient need to sign', 'carriers'),
                        'name' => 'return_' . Constant::SIGNATURE_REQUIRED_CONFIGURATION_NAME,
                        'tab' => 'return',
                    ],
                    [
                        'type' => 'switch',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'return_' . Constant::INSURANCE_CONFIGURATION_NAME.'_on',
                                'value' => 1,
                                'label' => $this->module->l('Yes', 'carriers')
                            ],
                            [
                                'id' => 'return_' . Constant::INSURANCE_CONFIGURATION_NAME.'_off',
                                'value' => 0,
                                'label' => $this->module->l('No', 'carriers')
                            ],
                        ],
                        'label' => $this->module->l('Package with insurance', 'carriers'),
                        'name' => 'return_' . Constant::INSURANCE_CONFIGURATION_NAME,
                        'tab' => 'return',
                    ],
                    [
                        'type' => 'hidden',
                        'name' => 'id_carrier',
                    ],
                ],
                'submit' => [
                    'title' => $this->module->l('Save', 'carriers'),
                ],
            ],
        ];

        $helper = new \HelperForm();

        $helper->show_toolbar = false;
        $helper->module = $this->module;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->submit_action = 'submitMyparcelCarrierSettings';
        $helper->currentIndex = \AdminController::$currentIndex
            . '&configure='
            . $this->module->name
            . '&menu=' . Tools::getValue('menu', 0);
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $result = Db::getInstance()->executeS('SELECT *
            FROM ' . _DB_PREFIX_ . 'myparcel_carrier_configuration
            WHERE id_carrier = "' . Tools::getValue('id_carrier') . '"  ');
        $vars = [];
        foreach ($result as $item) {
            if ($item['name'] == 'dropOffDays') {
                $temp = explode(';', $item['value']);
                foreach ($temp as $value) {
                    $vars["dropOffDays_{$value}"] = 1;
                }
            }
            $vars[$item['name']] = $item['value'];
        }

        $vars['id_carrier'] = Tools::getValue('id_carrier');
        $this->setExclusiveFieldsValues($vars);
        $helper->tpl_vars = [
            'fields_value' => $vars,
        ];

        return $helper->generateForm([$fields]);
    }

    private function getList()
    {
        $fieldsList = [
            'id_carrier' => [
                'title' => $this->module->l('ID', 'carriers'),
                'align' => 'center',
                'class' => 'fixed-width-xs',
            ],
            'name' => [
                'title' => $this->module->l('Name', 'carriers'),
            ],
        ];

        $helper = new \HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = true;
        $helper->actions = ['edit'];
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->identifier = 'id_carrier';
        $helper->title = $this->module->l('Delivery options', 'carriers');
        $helper->table = 'carrier';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = \AdminController::$currentIndex . '&configure=' . $this->module->name . '&menu=' . Tools::getValue(
            'menu',
            0
        );
        $helper->colorOnBackground = true;
        $helper->no_link = true;

        $list = Db::getInstance()->executeS('SELECT a.*
            FROM `' . _DB_PREFIX_ . "carrier` a
            WHERE a.external_module_name = '" . $this->module->name . "'
                AND a.`deleted` = 0
            ORDER BY a.`position` ASC
            LIMIT 0, 50");

        return $helper->generateList($list, $fieldsList);
    }
}
