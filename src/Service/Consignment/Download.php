<?php

namespace Gett\MyparcelBE\Service\Consignment;

use Gett\MyparcelBE\Constant;
use Gett\MyparcelBE\Logger\Logger;
use Symfony\Component\HttpFoundation\Request;
use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use PrestaShop\PrestaShop\Core\ConfigurationInterface;

class Download
{
    private $api_key;
    private $request;
    private $configuration;

    public function __construct(string $api_key, Request $request, ConfigurationInterface $configuration)
    {
        $this->api_key = $api_key;
        $this->request = $request;
        $this->configuration = $configuration;
    }

    public function downloadLabel(array $id_labels)
    {

        $myParcelCollection = (new MyParcelCollection())
            ->setUserAgent('prestashop', '1.0')
        ;
        if (\Configuration::get(Constant::ORDER_NOTIFICATION_AFTER_CONFIGURATION_NAME == 'printed')) {
            //TODO send notification
        }

        try {
            $collection = MyParcelCollection::findMany($id_labels, $this->api_key);
            if (!empty($collection->getConsignments())) {
                $collection
                    ->setPdfOfLabels($this->fetchPositions())
                    ->downloadPdfOfLabels($this->configuration->get(Constant::LABEL_OPEN_DOWNLOAD_CONFIGURATION_NAME, false));
                Logger::addLog($collection->toJson());
            } else {
                \Tools::redirectAdmin(\Context::getContext()->link->getAdminLink('AdminOrders'));
            }
        } catch (\Exception $e) {
            Logger::addLog($e->getMessage(), true);
        }
    }

    private function fetchPositions()
    {
        if ($this->request->get('format') == 'a6') {
            return false;
        }

        return $this->request->get('position');
    }
}
