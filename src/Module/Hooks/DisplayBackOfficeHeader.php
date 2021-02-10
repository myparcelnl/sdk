<?php

namespace Gett\MyparcelBE\Module\Hooks;

use Gett\MyparcelBE\Constant;

trait DisplayBackOfficeHeader
{
    public function hookDisplayBackOfficeHeader()
    {
        $this->context->controller->addCss($this->_path . 'views/css/myparceladmin.css');
        $this->context->controller->addJs($this->_path . 'views/js/admin/myparcelbo.js');
    }

    public function hookDisplayAdminAfterHeader(): string
    {
        // TODO: test compatibility with < PS1.7.7
        if (!$this->isSymfonyContext() || $this->context->controller->php_self != 'AdminOrders') {
            return '';
        }
        $this->context->controller->addJqueryPlugin(['scrollTo']);

        \Media::addJsDefL('print_labels_text', $this->l('Print labels', 'displaybackofficeheader'));
        \Media::addJsDefL('refresh_labels_text', $this->l('Refresh labels', 'displaybackofficeheader'));
        \Media::addJsDefL('export_labels_text', $this->l('Export labels', 'displaybackofficeheader'));
        \Media::addJsDefL(
            'export_and_print_label_text',
            $this->l('Export and print labels', 'displaybackofficeheader')
        );
        $this->context->controller->addJS(
            $this->getLocalPath() . 'views/js/admin/order.js'
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
}
