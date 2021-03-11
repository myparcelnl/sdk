<?php

namespace Gett\MyparcelBE\Module\Hooks\Helpers;

use Configuration;
use Context;
use Currency;
use Gett\MyparcelBE\Constant;
use Media;
use Module;
use Tools;

class AdminOrderList extends AbstractAdminOrder
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

    public function __construct(Module $module, int $idOrder = null, Context $context = null)
    {
        $this->module = $module;
        $this->idOrder = $idOrder;
        $this->context = $context ?? Context::getContext();
    }

    public function getAdminAfterHeader(): string
    {
        $link = $this->context->link;
        $currency = Currency::getDefaultCurrency();
        $currencySign = $currency->getSign();
        $this->context->smarty->assign([
            'action' => $link->getAdminLink('AdminMyParcelBELabel', true, [], ['action' => 'createLabel']),
            'download_action' => $link->getAdminLink('AdminMyParcelBELabel', true, [], ['action' => 'downloadLabel']),
            'print_bulk_action' => $link->getAdminLink('AdminMyParcelBELabel', true, [], ['action' => 'print']),
            'export_print_bulk_action' => $link->getAdminLink('AdminMyParcelBELabel', true, [], ['action' => 'exportPrint']),
            'isBE' => $this->module->isBE(),
            'labelConfiguration' => $this->getLabelDefaultConfiguration(),
            'PACKAGE_TYPE' => Constant::PACKAGE_TYPE_CONFIGURATION_NAME,
            'ONLY_RECIPIENT' => Constant::ONLY_RECIPIENT_CONFIGURATION_NAME,
            'AGE_CHECK' => Constant::AGE_CHECK_CONFIGURATION_NAME,
            'PACKAGE_FORMAT' => Constant::PACKAGE_FORMAT_CONFIGURATION_NAME,
            'RETURN_PACKAGE' => Constant::RETURN_PACKAGE_CONFIGURATION_NAME,
            'SIGNATURE_REQUIRED' => Constant::SIGNATURE_REQUIRED_CONFIGURATION_NAME,
            'INSURANCE' => Constant::INSURANCE_CONFIGURATION_NAME,
            'currencySign' => $currencySign,
        ]);

        return $this->module->display($this->module->name, 'views/templates/admin/hook/orders_popups.tpl');
    }

    public function setHeaderContent(): void
    {
        $link = $this->context->link;
        Media::addJsDef(
            [
                'default_label_size' => Configuration::get(Constant::LABEL_SIZE_CONFIGURATION_NAME) == false ? 'a4' : Configuration::get(Constant::LABEL_SIZE_CONFIGURATION_NAME),
                'default_label_position' => Configuration::get(Constant::LABEL_POSITION_CONFIGURATION_NAME) == false ? '1' : Configuration::get(Constant::LABEL_POSITION_CONFIGURATION_NAME),
                'prompt_for_label_position' => Configuration::get(Constant::LABEL_PROMPT_POSITION_CONFIGURATION_NAME) == false ? '0' : Configuration::get(Constant::LABEL_PROMPT_POSITION_CONFIGURATION_NAME),
                'delivery_settings_route' => $link->getAdminLink('AdminMyParcelBELabel', true, [], [
                    'action' => 'getDeliverySettings',
                    'id_order' => Tools::getValue('id_order'),
                ]),
                'create_labels_bulk_route' => $link->getAdminLink('AdminMyParcelBELabel', true, [], ['action' => 'createb']),
                'refresh_labels_bulk_route' => $link->getAdminLink('AdminMyParcelBELabel', true, [], ['action' => 'refresh']),
                'create_label_action' => $link->getAdminLink('AdminMyParcelBELabel', true, [], ['action' => 'createLabel', 'listingPage' => true]),
                'create_label_error' => $this->module->l('Cannot create label for orders', 'adminorderlist'),
                'no_order_selected_error' => $this->module->l('Please select at least one order that has MyParcel carrier.', 'adminorderlist'),
            ]
        );
        $this->context->controller->addJqueryPlugin(['scrollTo']);

        Media::addJsDefL('print_labels_text', $this->module->l('Print labels', 'adminorderlist'));
        Media::addJsDefL('refresh_labels_text', $this->module->l('Refresh labels', 'adminorderlist'));
        Media::addJsDefL('export_labels_text', $this->module->l('Export labels', 'adminorderlist'));
        Media::addJsDefL(
            'export_and_print_label_text',
            $this->module->l('Export and print labels', 'adminorderlist')
        );

        $this->context->controller->addCss($this->module->getPathUri() . 'views/css/myparcel.css');
        $this->context->controller->addJs($this->module->getPathUri() . 'views/dist/myparcel.js');
        $this->context->controller->addJS($this->module->getPathUri() . 'views/js/admin/order.js');
    }
}
