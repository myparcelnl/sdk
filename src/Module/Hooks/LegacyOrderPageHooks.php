<?php

namespace Gett\MyparcelBE\Module\Hooks;

use Address;
use AddressFormat;
use Carrier;
use Configuration;
use Currency;
use Customer;
use Dispatcher;
use Gett\MyparcelBE\Constant;
use Gett\MyparcelBE\Label\LabelOptionsResolver;
use Gett\MyparcelBE\Module\Carrier\Provider\CarrierSettingsProvider;
use Gett\MyparcelBE\Module\Carrier\Provider\DeliveryOptionsProvider;
use Gett\MyparcelBE\OrderLabel;
use Gett\MyparcelBE\Provider\OrderLabelProvider;
use Order;
use PrestaShop\PrestaShop\Adapter\Presenter\Object\ObjectPresenter;
use Validate;

trait LegacyOrderPageHooks
{
    protected $carrierList = [];

    public function hookDisplayAdminListBefore()
    {
        if ($this->context->controller instanceof \AdminOrdersController) {
            \Media::addJsDefL('print_labels_text', $this->l('Print labels', 'legacyorderpagehooks'));
            \Media::addJsDefL('refresh_labels_text', $this->l('Refresh labels', 'legacyorderpagehooks'));
            \Media::addJsDefL('export_labels_text', $this->l('Export labels', 'legacyorderpagehooks'));
            \Media::addJsDefL(
                'export_and_print_label_text',
                $this->l('Export and print labels', 'legacyorderpagehooks')
            );
            $this->context->controller->addJS(
                $this->_path . 'views/js/admin/order.js'
            );

            $link = $this->context->link;
            $this->context->smarty->assign([
                'action' => $link->getAdminLink('AdminMyParcelBELabel', true, [], ['action' => 'createLabel']),
                'download_action' => $link->getAdminLink('AdminMyParcelBELabel', true, [], ['action' => 'downloadLabel']),
                'print_bulk_action' => $link->getAdminLink('AdminMyParcelBELabel', true, [], ['action' => 'print']),
                'export_print_bulk_action' => $link->getAdminLink('AdminMyParcelBELabel', true, [], ['action' => 'exportPrint']),
                'isBE' => $this->isBE(),
                'labelConfiguration' => $this->getLabelDefaultConfiguration(),
                'PACKAGE_TYPE' => Constant::PACKAGE_TYPE_CONFIGURATION_NAME,
                'ONLY_RECIPIENT' => Constant::ONLY_RECIPIENT_CONFIGURATION_NAME,
                'AGE_CHECK' => Constant::AGE_CHECK_CONFIGURATION_NAME,
                'PACKAGE_FORMAT' => Constant::PACKAGE_FORMAT_CONFIGURATION_NAME,
                'RETURN_PACKAGE' => Constant::RETURN_PACKAGE_CONFIGURATION_NAME,
                'SIGNATURE_REQUIRED' => Constant::SIGNATURE_REQUIRED_CONFIGURATION_NAME,
                'INSURANCE' => Constant::INSURANCE_CONFIGURATION_NAME,
            ]);

            return $this->display($this->name, 'views/templates/admin/hook/orders_popups.tpl');
        }

        return '';
    }

    public function hookActionAdminOrdersListingFieldsModifier($params)
    {
        if (!isset($params['select'])) {
            $params['select'] = '';
        }
        $params['select'] .= ',1 as `myparcel_void_1` ,1 as `myparcel_void_2`, a.id_carrier';

        $params['fields']['myparcel_void_1'] = [
            'title' => $this->l('Labels', 'legacyorderpagehooks'),
            'class' => 'pointer-myparcel-labels text-center',
            'callback' => 'printMyParcelLabel',
            'search' => false,
            'orderby' => false,
            'remove_onclick' => true,
            'callback_object' => $this,
        ];

        $params['fields']['myparcel_void_2'] = [
            'title' => '',
            'class' => 'text-nowrap',
            'callback' => 'printMyParcelIcon',
            'search' => false,
            'orderby' => false,
            'remove_onclick' => true,
            'callback_object' => $this,
        ];
    }

    public function printMyParcelLabel($id, $params)
    {
        if (!$this->searchMyParcelCarrier((int) $params['id_carrier'])) {
            return '';
        }
        $sql = new \DbQuery();
        $sql->select('*');
        $sql->from('myparcel_order_label');
        $sql->where('id_order = "' . pSQL($params['id_order']) . '" ');
        $result = \Db::getInstance()->executeS($sql);
        $link = $this->context->link;

        $this->context->smarty->assign([
            'labels' => $result,
            'link' => $link,
            'promptForLabelPosition' => Configuration::get(Constant::LABEL_PROMPT_POSITION_CONFIGURATION_NAME),
        ]);

        return $this->display($this->name, 'views/templates/admin/icon-labels.tpl');
    }

    public function printMyParcelIcon($id, $params)
    {
        if (!$this->searchMyParcelCarrier((int) $params['id_carrier'])) {
            return '';
        }

        $label_options_resolver = new LabelOptionsResolver();
        $carrierReference = (int) $this->carrierList[(int) $params['id_carrier']];
        $allowSetSignature = true;
        $allowSetOnlyRecipient = true;
        switch ($carrierReference) {
            case (int) Configuration::get(Constant::DPD_CONFIGURATION_NAME):
                $allowSetSignature = false;
                $allowSetOnlyRecipient = false;
                break;
            case (int) Configuration::get(Constant::BPOST_CONFIGURATION_NAME):
                $allowSetOnlyRecipient = false;
                break;
            case (int) Configuration::get(Constant::POSTNL_CONFIGURATION_NAME):
            default:
                break;
        }

        $this->context->smarty->assign([
            'label_options' => $label_options_resolver->getLabelOptions($params),
            'allowSetSignature' => $allowSetSignature,
            'allowSetOnlyRecipient' => $allowSetOnlyRecipient,
        ]);

        return $this->display($this->name, 'views/templates/admin/icon-concept.tpl');
    }

    public function hookActionAdminControllerSetMedia()
    {
        if ($this->context->controller instanceof \AdminOrdersController) {
            $link = new \Link();
            \Media::addJsDef(
                [
                    'default_label_size' => Configuration::get(Constant::LABEL_SIZE_CONFIGURATION_NAME) == false ? 'a4' : Configuration::get(Constant::LABEL_SIZE_CONFIGURATION_NAME),
                    'default_label_position' => Configuration::get(Constant::LABEL_POSITION_CONFIGURATION_NAME) == false ? '1' : Configuration::get(Constant::LABEL_POSITION_CONFIGURATION_NAME),
                    'prompt_for_label_position' => Configuration::get(Constant::LABEL_PROMPT_POSITION_CONFIGURATION_NAME) == false ? '0' : Configuration::get(Constant::LABEL_PROMPT_POSITION_CONFIGURATION_NAME),
                    'create_labels_bulk_route' => $link->getAdminLink('AdminMyParcelBELabel', true, [], ['action' => 'createb']),
                    'refresh_labels_bulk_route' => $link->getAdminLink('AdminMyParcelBELabel', true, [], ['action' => 'refresh']),
                    'create_label_action' => $link->getAdminLink('AdminMyParcelBELabel', true, [], ['action' => 'createLabel', 'listingPage' => true]),
                    'create_label_error' => $this->l('Cannot create label for orders', 'legacyorderpagehooks'),
                    'no_order_selected_error' => $this->l('Please select at least one order first.', 'legacyorderpagehooks'),
                ]
            );

            $this->context->controller->addJS(
                $this->_path . 'views/js/admin/order.js'
            );
        } elseif ($this->context->controller->php_self == 'AdminOrders') { //symfony controller
            \Media::addJsDef(
                [
                    'default_label_size' => Configuration::get(Constant::LABEL_SIZE_CONFIGURATION_NAME) == false ? 'a4' : Configuration::get(Constant::LABEL_SIZE_CONFIGURATION_NAME),
                    'default_label_position' => Configuration::get(Constant::LABEL_POSITION_CONFIGURATION_NAME) == false ? '1' : Configuration::get(Constant::LABEL_POSITION_CONFIGURATION_NAME),
                    'prompt_for_label_position' => Configuration::get(Constant::LABEL_PROMPT_POSITION_CONFIGURATION_NAME) == false ? '0' : Configuration::get(Constant::LABEL_PROMPT_POSITION_CONFIGURATION_NAME),
                ]
            );

            $this->context->controller->addJS(
                $this->_path . 'views/js/admin/symfony/orders-list.js'
            );
        }
    }

    public function searchMyParcelCarrier($idCarrier)
    {
        $carrier = $this->carrierList[$idCarrier] ?? new \Carrier($idCarrier);
        if (empty($this->carrierList[$idCarrier])) {
            $this->carrierList[$idCarrier] = $carrier->id_reference;
        }

        return in_array($this->carrierList[$idCarrier], [
            Configuration::get(Constant::DPD_CONFIGURATION_NAME),
            Configuration::get(Constant::BPOST_CONFIGURATION_NAME),
            Configuration::get(Constant::POSTNL_CONFIGURATION_NAME),
        ]);
    }
    
    public function getLabelDefaultConfiguration(): array
    {
        return Configuration::getMultiple([
            Constant::LABEL_SIZE_CONFIGURATION_NAME,
            Constant::LABEL_POSITION_CONFIGURATION_NAME,
        ]);
    }

    public function hookDisplayInvoice($params)
    {
        $idOrder = (int) $params['id_order'];
        $controller = Dispatcher::getInstance()->getController();

        if (empty($idOrder) || $controller !== 'AdminOrders') {
            return '';
        }
        $order = new Order($idOrder);
        if (!Validate::isLoadedObject($order)) {
            return '';
        }

        $currency = Currency::getDefaultCurrency();

        $link = $this->context->link;
        $labelUrl = $link->getAdminLink('AdminMyParcelBELabel', true, [], ['id_order' => $idOrder]);
        $deliveryAddress = new Address($order->id_address_delivery);
        $deliveryAddressFormatted = AddressFormat::generateAddress($deliveryAddress, [], '<br />');
        $bulk_actions = [
            'refreshLabels' => [
                'text' => $this->l('Refresh', 'legacyorderpagehooks'),
                'icon' => 'icon-refresh',
                'ajax' => 1,
            ],
            'printLabels' => [
                'text' => $this->l('Print', 'legacyorderpagehooks'),
                'icon' => 'icon-print',
            ]
        ];
        $deliveryOptionsProvider = new DeliveryOptionsProvider();
        $deliveryOptions = $deliveryOptionsProvider->provide($order->id);
        $labelList = (new OrderLabelProvider($this))->provideLabels($order->id, []);

        $labelListHtml = $this->context->smarty->createData($this->context->smarty);
        $labelListHtml->assign(['labelList' => $labelList]);
        $labelListHtmlTpl = $this->context->smarty->createTemplate(
            $this->getTemplatePath('views/templates/admin/hook/label-list.tpl'),
            $labelListHtml
        );

        $labelConceptHtml = $this->context->smarty->createData($this->context->smarty);
        $labelReturnHtml = $this->context->smarty->createData($this->context->smarty);

        $carrierSettingsProvider = new CarrierSettingsProvider($this);
        $deliveryAddress = new Address($order->id_address_delivery);
        $customer = new Customer($order->id_customer);

        $labelConceptHtml->assign([
            'deliveryOptions' => json_decode(json_encode($deliveryOptions), true),
            'carrierSettings' => $carrierSettingsProvider->provide($order->id_carrier),
            'date_warning_display' => $deliveryOptionsProvider->provideWarningDisplay($order->id),
            'isBE' => $this->isBE(),
        ]);
        $labelReturnHtml->assign([
            'deliveryOptions' => json_decode(json_encode($deliveryOptions), true),
            'carrierSettings' => $carrierSettingsProvider->provide($order->id_carrier),
            'isBE' => $this->isBE(),
            'currencySign' => $currency->getSign(),
            'customerName' => trim($deliveryAddress->firstname . ' ' . $deliveryAddress->lastname),
            'customerEmail' => $customer->email,
            'labelUrl' => $labelUrl,
        ]);
        $labelConceptHtmlTpl = $this->context->smarty->createTemplate(
            $this->getTemplatePath('views/templates/admin/hook/label-concept.tpl'),
            $labelConceptHtml
        );
        $labelReturnHtmlTpl = $this->context->smarty->createTemplate(
            $this->getTemplatePath('views/templates/admin/hook/label-return-form.tpl'),
            $labelReturnHtml
        );

        $this->context->controller->addCss($this->_path . 'views/css/myparcel.css');
        $this->context->controller->addJs($this->_path . 'views/dist/myparcel.js');

        $this->context->smarty->assign([
            'modulePathUri' => $this->getPathUri(),
            'id_order' => $idOrder,
            'id_carrier' => $order->id_carrier,
            'addressEditUrl' => $link->getAdminLink('AdminAddresses', true, [], [
                'id_order' => $idOrder,
                'id_address' => $order->id_address_delivery,
                'addaddress' => '',
                'realedit' => 1,
                'address_type' => 1,
                'back' => urlencode(str_replace('&conf=4', '', $_SERVER['REQUEST_URI'])),
            ]),
            'delivery_address_formatted' => $deliveryAddressFormatted,
            'labelListHtml' => $labelListHtmlTpl->fetch(),
            'labelConceptHtml' => $labelConceptHtmlTpl->fetch(),
            'labelReturnHtml' => $labelReturnHtmlTpl->fetch(),
            'labelList' => $labelList,
            'bulk_actions' => $bulk_actions,
            'labelUrl' => $labelUrl,
            'labelAction' => $link->getAdminLink('AdminMyParcelBELabel', true, [], ['action' => 'createLabel']),
            'download_action' => $link->getAdminLink('AdminMyParcelBELabel', true, [], ['action' => 'downloadLabel']),
            'print_bulk_action' => $link->getAdminLink('AdminMyParcelBELabel', true, [], ['action' => 'print']),
            'export_print_bulk_action' => $link->getAdminLink('AdminMyParcelBELabel', true, [], ['action' => 'exportPrint']),
            //'isBE' => $this->isBE(),
            //'carrierSettings' => $carrierLabelSettings,
            'carrierLabels' => Constant::SINGLE_LABEL_CREATION_OPTIONS,
            //'date_warning_display' => ($nextDeliveryDate > $deliveryDate),
            'deliveryOptions' => json_decode(json_encode($deliveryOptions), true),
            'currencySign' => $currency->getSign(),
            'labelConfiguration' => $this->getLabelDefaultConfiguration(),
        ]);

        return $this->display($this->name, 'views/templates/admin/hook/order-label-block.tpl');
    }
}
