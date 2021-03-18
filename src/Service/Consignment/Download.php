<?php

namespace Gett\MyparcelBE\Service\Consignment;

use Configuration;
use Context;
use Gett\MyparcelBE\Constant;
use Gett\MyparcelBE\Logger\Logger;
use Symfony\Component\HttpFoundation\Request;
use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use PrestaShop\PrestaShop\Core\ConfigurationInterface;
use Tools;

class Download
{
    private $api_key;
    private $request;
    private $configuration;

    public function __construct(string $api_key, array $request, Configuration $configuration)
    {
        $this->api_key = $api_key;
        $this->request = $request;
        $this->configuration = $configuration;
    }

    public function downloadLabel(array $id_labels)
    {
        if (\Configuration::get(Constant::ORDER_NOTIFICATION_AFTER_CONFIGURATION_NAME == 'printed')) {
            //TODO send notification
        }

        try {
            $collection = MyParcelCollection::findMany($id_labels, $this->api_key);
            if (!empty($collection->getConsignments())) {
                $collection->setUserAgents(['prestashop' => _PS_VERSION_])
                    ->setPdfOfLabels($this->fetchPositions());
                $isPdf = is_string($collection->getLabelPdf());
                if ($isPdf) {
                    $collection->downloadPdfOfLabels($this->configuration::get(
                        Constant::LABEL_OPEN_DOWNLOAD_CONFIGURATION_NAME,
                        false,
                        null,
                        null,
                        false
                    ));
                }
                Logger::addLog($collection->toJson());
                if (!$isPdf) {
                    Tools::redirectAdmin(Context::getContext()->link->getAdminLink('AdminOrders'));
                }
            } else {
                Tools::redirectAdmin(Context::getContext()->link->getAdminLink('AdminOrders'));
            }
        } catch (\Exception $e) {
            Logger::addLog($e->getMessage(), true, true);
        }
    }

    private function fetchPositions()
    {
        if ($this->request['format'] == 'a6') {
            return false;
        }

        return $this->request['position'];
    }
}
