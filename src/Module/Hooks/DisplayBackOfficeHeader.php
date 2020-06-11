<?php

namespace Gett\MyParcelBE\Module\Hooks;

trait DisplayBackOfficeHeader
{
    public function hookDisplayBackOfficeHeader()
    {
        $this->context->controller->addCss($this->_path . 'views/css/myparceladmin.css');
        $this->context->controller->addJs($this->_path . 'views/js/admin/myparcelbo.js');
    }
}
