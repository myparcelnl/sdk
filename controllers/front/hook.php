<?php

if (!defined('_PS_VERSION_')) {
    return;
}

require_once dirname(__FILE__) . '/../../myparcelbe.php';

class MyParcelBEHookModuleFrontController extends ModuleFrontController
{
    public $module;

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
        if (!Module::isEnabled('myparcelbe')) {
            header('Content-Type: application/json; charset=utf8');
            die(json_encode(['data' => ['message' => 'Module is not enabled']]));
        }

        $this->processWebhook();

        die('1');
    }

    protected function processWebhook()
    {
        $content = file_get_contents('php://input');
        // @codingStandardsIgnoreEnd
        if (Configuration::get(\Gett\MyparcelBE\Constant::API_LOGGING_CONFIGURATION_NAME)) {
            $logContent = ($content);
            \Gett\MyparcelBE\Logger\Logger::addLog("MyParcel - incoming webhook\n{$logContent}");
        }

        $data = @json_decode($content, true);
        if (isset($data['data']['hooks']) && is_array($data['data']['hooks'])) {
            foreach ($data['data']['hooks'] as &$item) {
                if (isset($item['shipment_id'], $item['status'], $item['barcode'])
                ) {
                    \Gett\MyparcelBE\OrderLabel::updateStatus($item['shipment_id'], $item['barcode'], $item['status']);
                }
            }

            die('0');
        }

        die('1');
    }

    protected function displayMaintenancePage()
    {
        // Disable the maintenance page
    }
}
