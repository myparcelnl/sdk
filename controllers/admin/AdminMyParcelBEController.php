<?php

if (file_exists(_PS_MODULE_DIR_ . 'myparcelbe/vendor/autoload.php')) {
    require_once _PS_MODULE_DIR_ . 'myparcelbe/vendor/autoload.php';
}

class AdminMyParcelBEController extends ModuleAdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function init()
    {
        Tools::redirectAdmin($this->module->baseUrl);
    }
}
