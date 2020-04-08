<?php

if (!defined('_PS_VERSION_')) {
    return;
}

require_once dirname(__FILE__) . '/../../MyParcel.php';

class MyParcelHookModuleFrontController extends ModuleFrontController
{
    /**
     * Initialize content and block unauthorized calls.
     *
     * @throws Adapter_Exception
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws ErrorException
     *
     * @since 2.0.0
     */
    public function initContent()
    {
        $this->processWebhook();
    }

    protected function processWebhook()
    {
    }

    protected function displayMaintenancePage()
    {
        // Disable the maintenance page
    }
}
