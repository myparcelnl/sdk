<?php

namespace Gett\MyparcelBE\Module\Hooks\Helpers;

use Address;
use Configuration;
use Context;
use Currency;
use Customer;
use Gett\MyparcelBE\Constant;
use Gett\MyparcelBE\Label\LabelOptionsResolver;
use Gett\MyparcelBE\Module\Carrier\Provider\CarrierSettingsProvider;
use Gett\MyparcelBE\Module\Carrier\Provider\DeliveryOptionsProvider;
use Gett\MyparcelBE\Provider\OrderLabelProvider;
use Module;
use Order;
use Validate;

class AdminOrderView extends AbstractAdminOrder
{
    /**
     * @var Module
     */
    private $module;

    /**
     * @var int
     */
    private $idOrder;

    /**
     * @var Context
     */
    private $context;

    public function __construct(Module $module, int $idOrder, Context $context = null)
    {
        $this->module = $module;
        $this->idOrder = $idOrder;
        $this->context = $context ?? Context::getContext();
    }

    public function display(): string
    {
        $order = new Order($this->idOrder);
        if (!Validate::isLoadedObject($order) || !$this->isMyParcelCarrier((int) $order->id_carrier)) {
            return '';
        }
        $psVersion = '';
        if (version_compare(_PS_VERSION_, '1.7.7.0', '>=')) {
            $psVersion = '-177';
        }

        $currency = Currency::getDefaultCurrency();

        $link = $this->context->link;
        $labelUrl = $link->getAdminLink('AdminMyParcelBELabel', true, [], ['id_order' => $order->id]);
        $deliveryAddress = new Address($order->id_address_delivery);
        $deliveryAddressFormatted = \AddressFormat::generateAddress($deliveryAddress, [], '<br />');
        $bulk_actions = [
            'refreshLabels' => [
                'text' => $this->module->l('Refresh', 'adminorderview'),
                'icon' => 'icon-refresh',
                'ajax' => 1,
            ],
            'printLabels' => [
                'text' => $this->module->l('Print', 'adminorderview'),
                'icon' => 'icon-print',
            ],
        ];
        $deliveryOptionsProvider = new DeliveryOptionsProvider();
        $deliveryOptions = $deliveryOptionsProvider->provide($order->id);
        $labelList = $this->getLabels();

        $labelListHtml = $this->context->smarty->createData($this->context->smarty);
        $labelListHtml->assign([
            'labelList' => $labelList,
            'promptForLabelPosition' => Configuration::get(Constant::LABEL_PROMPT_POSITION_CONFIGURATION_NAME),
        ]);
        $labelListHtmlTpl = $this->context->smarty->createTemplate(
            $this->module->getTemplatePath('views/templates/admin/hook/label-list' . $psVersion . '.tpl'),
            $labelListHtml
        );

        $labelConceptHtml = $this->context->smarty->createData($this->context->smarty);
        $labelReturnHtml = $this->context->smarty->createData($this->context->smarty);

        $carrierSettingsProvider = new CarrierSettingsProvider($this->module);
        $deliveryAddress = new Address($order->id_address_delivery);
        $customer = new Customer($order->id_customer);
        $labelOptionsResolver = new LabelOptionsResolver();

        $carrierSettings = $carrierSettingsProvider->provide($order->id_carrier);
        $currencySign = $currency->getSign();

        $labelConceptHtml->assign([
            'deliveryOptions' => json_decode(json_encode($deliveryOptions), true),
            'carrierSettings' => $carrierSettings,
            'date_warning_display' => $deliveryOptionsProvider->provideWarningDisplay($order->id),
            'isBE' => $this->module->isBE(),
            'currencySign' => $currencySign,
            'labelOptions' => json_decode($labelOptionsResolver->getLabelOptions([
                'id_order' => (int) $order->id,
                'id_carrier' => (int) $order->id_carrier,
            ]), true),
        ]);
        $labelReturnHtml->assign([
            'deliveryOptions' => json_decode(json_encode($deliveryOptions), true),
            'carrierSettings' => $carrierSettings,
            'isBE' => $this->module->isBE(),
            'currencySign' => $currencySign,
            'customerName' => trim($deliveryAddress->firstname . ' ' . $deliveryAddress->lastname),
            'customerEmail' => $customer->email,
            'labelUrl' => $labelUrl,
        ]);
        $labelConceptHtmlTpl = $this->context->smarty->createTemplate(
            $this->module->getTemplatePath('views/templates/admin/hook/label-concept' . $psVersion . '.tpl'),
            $labelConceptHtml
        );
        $labelReturnHtmlTpl = $this->context->smarty->createTemplate(
            $this->module->getTemplatePath('views/templates/admin/hook/label-return-form' . $psVersion . '.tpl'),
            $labelReturnHtml
        );

        $this->context->smarty->assign([
            'modulePathUri' => $this->module->getPathUri(),
            'id_order' => $order->id,
            'id_carrier' => $order->id_carrier,
            'addressEditUrl' => $link->getAdminLink('AdminAddresses', true, [], [
                'id_order' => $order->id,
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
            'carrierLabels' => Constant::SINGLE_LABEL_CREATION_OPTIONS,
            'deliveryOptions' => json_decode(json_encode($deliveryOptions), true),
            'currencySign' => $currencySign,
            'labelConfiguration' => $this->getLabelDefaultConfiguration(),
            'promptForLabelPosition' => Configuration::get(Constant::LABEL_PROMPT_POSITION_CONFIGURATION_NAME),
        ]);

        return $this->module->display(
            $this->module->name,
            'views/templates/admin/hook/order-label-block' . $psVersion . '.tpl'
        );
    }

    public function getLabels()
    {
        return (new OrderLabelProvider($this->module))->provideLabels($this->idOrder, []);
    }
}
