<?php

class MyParcelCarrierController extends AdminController
{
    protected $position_identifier = 'id_carrier';

    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'carrier';
        $this->className = 'Carrier';
        $this->lang = false;
        $this->deleted = true;

        $this->addRowAction('edit');
        $this->addRowAction('delete');

        $this->_defaultOrderBy = 'position';

        parent::__construct();

        $this->bulk_actions = [
            'delete' => [
                'text' => $this->trans('Delete selected', [], 'Admin.Notifications.Info'),
                'confirm' => $this->trans('Delete selected items?', [], 'Admin.Notifications.Info'),
                'icon' => 'icon-trash',
            ],
        ];

        $this->fieldImageSettings = [
            'name' => 'logo',
            'dir' => 's',
        ];

        $this->fields_list = [
            'id_carrier' => [
                'title' => $this->trans('ID', [], 'Admin.Global'),
                'align' => 'center',
                'class' => 'fixed-width-xs',
            ],
            'name' => [
                'title' => $this->trans('Name', [], 'Admin.Global'),
            ],
            'image' => [
                'title' => $this->trans('Logo', [], 'Admin.Global'),
                'align' => 'center',
                'image' => 's',
                'class' => 'fixed-width-xs',
                'orderby' => false,
                'search' => false,
            ],
            'delay' => [
                'title' => $this->trans('Delay', [], 'Admin.Shipping.Feature'),
                'orderby' => false,
            ],
            'active' => [
                'title' => $this->trans('Status', [], 'Admin.Global'),
                'align' => 'center',
                'active' => 'status',
                'type' => 'bool',
                'class' => 'fixed-width-sm',
                'orderby' => false,
            ],
            'is_free' => [
                'title' => $this->trans('Free Shipping', [], 'Admin.Shipping.Feature'),
                'align' => 'center',
                'active' => 'isFree',
                'type' => 'bool',
                'class' => 'fixed-width-sm',
                'orderby' => false,
            ],
            'position' => [
                'title' => $this->trans('Position', [], 'Admin.Global'),
                'filter_key' => 'a!position',
                'align' => 'center',
                'class' => 'fixed-width-sm',
                'position' => 'position',
            ],
        ];
    }

    public function renderForm()
    {
        $this->fields_form = [
            'legend' => [
                'title' => $this->trans('Carriers', [], 'Admin.Shipping.Feature'),
                'icon' => 'icon-truck',
            ],
            'tabs' => [
                'form' => $this->l('Checkout delivery form'),
                'delivery' => $this->l('Delivery'),
                'return' => $this->l('Return'),
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('Delivery Title'),
                    'name' => 'deliveryTitle',
                    'tab' => 'form',
                    'hint' => [
                        $this->l('General delivery title'),
                    ],
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Drop off days'),
                    'name' => 'dropOffDays',
                    'tab' => 'form',
                    'hint' => [
                        $this->l('This option allows the Merchant to set the days she normally goes to PostNL to hand in her parcels. Monday is 1 and Saturday is 6.'),
                    ],
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Cutoff Time'),
                    'name' => 'cutoffTime',
                    'tab' => 'form',
                    'hint' => [
                        $this->l('This option allows the Merchant to indicate the latest cut-off time before an order will still be picked, packed and dispatched on the same/first set dropoff day, taking into account the dropoff-delay. Industry standard default time is 17:00. For example, if cutoff time is 17:00, Monday is a delivery day and there\'s no delivery delay; all orders placed Monday before 17:00 will be dropped of at PostNL on that same Monday in time for the Monday collection and delivery on Tuesday.'),
                    ],
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Delivery days window'),
                    'name' => 'deliverydaysWindow',
                    'tab' => 'form',
                    'hint' => [
                        $this->l('This option allows the Merchant to set the number of days into the future for which she wants to show her consumers delivery options. For example; If set to 3 (days) in her checkout, a consumer ordering on Monday will see possible delivery options for Tuesday, Wednesday and Thursday (provided there is no drop-off delay, it\'s before the cut-off time and she goes to PostNL on Mondays). Min. is 1 and max. is 14.'),
                    ],
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Drop off delay'),
                    'name' => 'dropoffDelay',
                    'tab' => 'form',
                    'hint' => [
                        $this->l('This option allows the Merchant to set the number of days it takes her to pick, pack and hand in her parcel at PostNL when ordered before the cutoff time. By default this is 0 and max. is 14.'),
                    ],
                ],
                [
                    'type' => 'radio',
                    'label' => $this->l('Allow monday delivery'),
                    'name' => 'allowMondayDelivery',
                    'tab' => 'form',
                    'hint' => [
                        $this->l('Monday delivery is only possible when the package is delivered before 15.00 on Saturday at the designated PostNL locations. Note: To activate Monday delivery value 6 must be given with dropOffDays and value 1 must be given by monday_delivery. On Saturday the cutoffTime must be before 15:00 (14:30 recommended) so that Monday will be shown.'),
                    ],
                    'values' => [
                        [
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled'),
                        ],
                        [
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled'),
                        ],
                    ],
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Saturday cutoff time'),
                    'name' => 'saturdayCutoffTime',
                    'tab' => 'form',
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Allow monday delivery'),
                    'name' => 'allowMondayDelivery',
                    'tab' => 'form',
                    'hint' => [
                        $this->l('Monday delivery is only possible when the package is delivered before 15.00 on Saturday at the designated PostNL locations. Note: To activate Monday delivery value 6 must be given with dropOffDays and value 1 must be given by monday_delivery. On Saturday the cutoffTime must be before 15:00 (14:30 recommended) so that Monday will be shown.'),
                    ],
                ],
                [
                    'type' => 'radio',
                    'values' => [
                        ['id' => 'no', 'value' => 0, 'label' => 'No'],
                        ['id' => 'Yes', 'value' => 0, 'label' => 'Yes'],
                    ],
                    'label' => $this->l('Allow morning delivery'),
                    'name' => 'allowMorningDelivery',
                    'tab' => 'form',
                    'hint' => [
                        $this->l('Monday delivery is only possible when the package is delivered before 15.00 on Saturday at the designated PostNL locations. Note: To activate Monday delivery value 6 must be given with dropOffDays and value 1 must be given by monday_delivery. On Saturday the cutoffTime must be before 15:00 (14:30 recommended) so that Monday will be shown.'),
                    ],
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Delivery morning title'),
                    'name' => 'deliveryMorningTitle',
                    'tab' => 'form',
                    'hint' => [
                        $this->l('When there is no title, the delivery time will automatically be visible.'),
                    ],
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Delivery morning price'),
                    'name' => 'priceMorningDelivery',
                    'tab' => 'form',
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Delivery standart title'),
                    'name' => 'deliveryStandardTitle',
                    'tab' => 'form',
                    'hint' => [
                        $this->l('When there is no title, the delivery time will automatically be visible.'),
                    ],
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Delivery standart price'),
                    'name' => 'priceStandardDelivery',
                    'tab' => 'form',
                ],
                [
                    'type' => 'radio',
                    'label' => $this->l('Allow evening delivery'),
                    'name' => 'allowEveningDelivery',
                    'tab' => 'form',
                    'values' => [
                        ['id' => 'no', 'value' => 0, 'label' => 'No'],
                        ['id' => 'Yes', 'value' => 0, 'label' => 'Yes'],
                    ],
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Delivery evening title'),
                    'name' => 'deliveryEveningTitle',
                    'tab' => 'form',
                    'hint' => [
                        $this->l('When there is no title, the delivery time will automatically be visible.'),
                    ],
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Price evening delivery'),
                    'name' => 'priceEveningDelivery',
                    'tab' => 'form',
                ],
                [
                    'type' => 'radio',
                    'label' => $this->l('Allow signature'),
                    'name' => 'allowSignature',
                    'tab' => 'form',
                    'values' => [
                        ['id' => 'no', 'value' => 0, 'label' => 'No'],
                        ['id' => 'Yes', 'value' => 0, 'label' => 'Yes'],
                    ],
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Signature title'),
                    'name' => 'signatureTitle',
                    'tab' => 'form',
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Price signature'),
                    'name' => 'priceSignature',
                    'tab' => 'form',
                ],
                [
                    'type' => 'radio',
                    'values' => [
                        ['id' => 'no', 'value' => 0, 'label' => 'No'],
                        ['id' => 'Yes', 'value' => 1, 'label' => 'Yes'],
                    ],
                    'label' => $this->l('Allow only recipient'),
                    'name' => 'allowOnlyRecipient',
                    'tab' => 'form',
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Only recipient title'),
                    'name' => 'onlyRecipientTitle',
                    'tab' => 'form',
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Price Only recipient'),
                    'name' => 'priceOnlyRecipient',
                    'tab' => 'form',
                ],
                [
                    'type' => 'radio',
                    'values' => [
                        ['id' => 'no', 'value' => 0, 'label' => 'No'],
                        ['id' => 'Yes', 'value' => 0, 'label' => 'Yes'],
                    ],
                    'label' => $this->l('Allow pickup points'),
                    'name' => 'allowPickupPoints',
                    'tab' => 'form',
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Pickup title'),
                    'name' => 'pickupTitle',
                    'tab' => 'form',
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Pickup price'),
                    'name' => 'pricePickup',
                    'tab' => 'form',
                    'hint' => [
                        $this->l('It\'s possible to fill in a positive or negative amount. Would you like to give a discount for the use of this feature or would you like to calculate extra costs? If the amount is negative the price will appear green in the checkout.'),
                    ],
                ],
                [
                    'type' => 'radio',
                    'values' => [
                        ['id' => 'no', 'value' => 0, 'label' => 'No'],
                        ['id' => 'Yes', 'value' => 0, 'label' => 'Yes'],
                    ],
                    'label' => $this->l('Allow pickup express'),
                    'name' => 'allowPickupExpress',
                    'tab' => 'form',
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Price pickup express'),
                    'name' => 'pricePickupExpress',
                    'tab' => 'form',
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('BE delivery title'),
                    'name' => 'BEdeliveryTitle',
                    'tab' => 'form',
                ],
                [
                    'tab' => 'delivery',
                    'type' => 'select',
                    'label' => $this->l('Default package type'),
                    'name' => \Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_TYPE_CONFIGURATION_NAME,
                    'options' => [
                        'query' => [
                            ['id' => 1, 'name' => 'Package'],
                            ['id' => 2, 'name' => 'Mailbox package'],
                            ['id' => 3, 'name' => 'Letter'],
                            ['id' => 4, 'name' => 'Digital stamp'],
                        ],
                        'id' => 'id',
                        'name' => 'name',
                        'default' => [
                            'label' => $this->l('Select'),
                            'value' => 0,
                        ],
                    ],
                ],
                [
                    'type' => 'radio',
                    'values' => [
                        ['id' => 'no', 'value' => 0, 'label' => 'No'],
                        ['id' => 'Yes', 'value' => 0, 'label' => 'Yes'],
                    ],
                    'label' => $this->l('Deliver only to recipient'),
                    'name' => \Gett\MyParcel\Constant::MY_PARCEL_ONLY_RECIPIENT_CONFIGURATION_NAME,
                    'tab' => 'delivery',
                ],
                [
                    'type' => 'radio',
                    'values' => [
                        ['id' => 'no', 'value' => 0, 'label' => 'No'],
                        ['id' => 'Yes', 'value' => 0, 'label' => 'Yes'],
                    ],
                    'label' => $this->l('Age check'),
                    'name' => \Gett\MyParcel\Constant::MY_PARCEL_AGE_CHECK_CONFIGURATION_NAME,
                    'tab' => 'delivery',
                ],
                [
                    'tab' => 'delivery',
                    'type' => 'select',
                    'label' => $this->l('Default package type'),
                    'name' => \Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_FORMAT_CONFIGURATION_NAME,
                    'options' => [
                        'query' => [
                            ['id' => 1, 'name' => 'Normal'],
                            ['id' => 2, 'name' => 'Large'],
                            ['id' => 3, 'name' => 'Automatic'],
                        ],
                        'id' => 'id',
                        'name' => 'name',
                        'default' => [
                            'label' => $this->l('Select'),
                            'value' => 0,
                        ],
                    ],
                ],
                [
                    'type' => 'radio',
                    'values' => [
                        ['id' => 'no', 'value' => 0, 'label' => 'No'],
                        ['id' => 'Yes', 'value' => 0, 'label' => 'Yes'],
                    ],
                    'label' => $this->l('Return package when recipient is not home'),
                    'name' => \Gett\MyParcel\Constant::MY_PARCEL_RETURN_PACKAGE_CONFIGURATION_NAME,
                    'tab' => 'delivery',
                ],
                [
                    'type' => 'radio',
                    'values' => [
                        ['id' => 'no', 'value' => 0, 'label' => 'No'],
                        ['id' => 'Yes', 'value' => 0, 'label' => 'Yes'],
                    ],
                    'label' => $this->l('Recipient need to sign'),
                    'name' => \Gett\MyParcel\Constant::MY_PARCEL_SIGNATURE_REQUIRED_CONFIGURATION_NAME,
                    'tab' => 'delivery',
                ],
                [
                    'type' => 'radio',
                    'values' => [
                        ['id' => 'no', 'value' => 0, 'label' => 'No'],
                        ['id' => 'Yes', 'value' => 0, 'label' => 'Yes'],
                    ],
                    'label' => $this->l('Package with insurance'),
                    'name' => \Gett\MyParcel\Constant::MY_PARCEL_INSURANCE_CONFIGURATION_NAME,
                    'tab' => 'delivery',
                ],

                [
                    'tab' => 'return',
                    'type' => 'select',
                    'label' => $this->l('Default package type'),
                    'name' => 'return_' . \Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_TYPE_CONFIGURATION_NAME,
                    'options' => [
                        'query' => [
                            ['id' => 1, 'name' => 'Package'],
                            ['id' => 2, 'name' => 'Mailbox package'],
                            ['id' => 3, 'name' => 'Letter'],
                            ['id' => 4, 'name' => 'Digital stamp'],
                        ],
                        'id' => 'id',
                        'name' => 'name',
                        'default' => [
                            'label' => $this->l('Select'),
                            'value' => 0,
                        ],
                    ],
                ],
                [
                    'type' => 'radio',
                    'values' => [
                        ['id' => 'no', 'value' => 0, 'label' => 'No'],
                        ['id' => 'Yes', 'value' => 0, 'label' => 'Yes'],
                    ],
                    'label' => $this->l('Deliver only to recipient'),
                    'name' => 'return_' . \Gett\MyParcel\Constant::MY_PARCEL_ONLY_RECIPIENT_CONFIGURATION_NAME,
                    'tab' => 'return',
                ],
                [
                    'type' => 'radio',
                    'values' => [
                        ['id' => 'no', 'value' => 0, 'label' => 'No'],
                        ['id' => 'Yes', 'value' => 0, 'label' => 'Yes'],
                    ],
                    'label' => $this->l('Age check'),
                    'name' => 'return_' . \Gett\MyParcel\Constant::MY_PARCEL_AGE_CHECK_CONFIGURATION_NAME,
                    'tab' => 'return',
                ],
                [
                    'tab' => 'return',
                    'type' => 'select',
                    'label' => $this->l('Default package type'),
                    'name' => 'return_' . \Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_FORMAT_CONFIGURATION_NAME,
                    'options' => [
                        'query' => [
                            ['id' => 1, 'name' => 'Normal'],
                            ['id' => 2, 'name' => 'Large'],
                            ['id' => 3, 'name' => 'Automatic'],
                        ],
                        'id' => 'id',
                        'name' => 'name',
                        'default' => [
                            'label' => $this->l('Select'),
                            'value' => 0,
                        ],
                    ],
                ],
                [
                    'type' => 'radio',
                    'values' => [
                        ['id' => 'no', 'value' => 0, 'label' => 'No'],
                        ['id' => 'Yes', 'value' => 0, 'label' => 'Yes'],
                    ],
                    'label' => $this->l('Return package when recipient is not home'),
                    'name' => 'return_' . \Gett\MyParcel\Constant::MY_PARCEL_RETURN_PACKAGE_CONFIGURATION_NAME,
                    'tab' => 'return',
                ],
                [
                    'type' => 'radio',
                    'values' => [
                        ['id' => 'no', 'value' => 0, 'label' => 'No'],
                        ['id' => 'Yes', 'value' => 0, 'label' => 'Yes'],
                    ],
                    'label' => $this->l('Recipient need to sign'),
                    'name' => 'return_' . \Gett\MyParcel\Constant::MY_PARCEL_SIGNATURE_REQUIRED_CONFIGURATION_NAME,
                    'tab' => 'return',
                ],
                [
                    'type' => 'radio',
                    'values' => [
                        ['id' => 'no', 'value' => 0, 'label' => 'No'],
                        ['id' => 'Yes', 'value' => 0, 'label' => 'Yes'],
                    ],
                    'label' => $this->l('Package with insurance'),
                    'name' => 'return_' . \Gett\MyParcel\Constant::MY_PARCEL_INSURANCE_CONFIGURATION_NAME,
                    'tab' => 'return',
                ],
            ],
        ];

        $this->fields_form['submit'] = [
            'title' => $this->l('Save'),
        ];

        return parent::renderForm();
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitAddcarrier')) {
            DB::getInstance()->execute('DELETE FROM ' . _DB_PREFIX_ . "myparcel_carrier_configuration WHERE id_carrier = '" . Tools::getValue('id_carrier') . "' ");

            foreach (Tools::getAllValues() as $key => $value) {
                DB::getInstance()->insert('myparcel_carrier_configuration', ['id_carrier' => Tools::getValue('id_carrier'), 'name' => pSQL($key), 'value' => pSQL($value)]);
            }
        }

        return true;
    }

    public function ajaxProcessUpdatePositions()
    {
        $way = (int) (Tools::getValue('way'));
        $id_carrier = (int) (Tools::getValue('id'));
        $positions = Tools::getValue($this->table);

        foreach ($positions as $position => $value) {
            $pos = explode('_', $value);

            if (isset($pos[2]) && (int) $pos[2] === $id_carrier) {
                if ($carrier = new Carrier((int) $pos[2])) {
                    if (isset($position) && $carrier->updatePosition($way, $position)) {
                        echo json_encode(['status' => 'ok', 'position' => $position, 'carrier' => $id_carrier]);
                    } else {
                        echo json_encode(['status' => 'error',  'position' => $position, 'carrier' => $id_carrier]);
                    }
                } else {
                    echo json_encode(['status' => 'error',  'message' => 'Carrier can not be loaded', 'carrier' => $id_carrier]);
                }

                break;
            }
        }
    }
}
