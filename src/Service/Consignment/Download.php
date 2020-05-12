<?php

namespace Gett\MyParcel\Service\Consignment;

use Gett\MyParcel\Constant;
use Gett\MyParcel\Logger\Logger;
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
        if (\Configuration::get(Constant::MY_PARCEL_ORDER_NOTIFICATION_AFTER_CONFIGURATION_NAME == 'printed')) {
            //TODO send notification
        }

        try {
            $collection = MyParcelCollection::findMany($id_labels, $this->api_key);
            $collection
                ->setPdfOfLabels($this->fetchPositions())
                ->downloadPdfOfLabels($this->configuration->get(Constant::MY_PARCEL_LABEL_OPEN_DOWNLOAD_CONFIGURATION_NAME, false))
            ;
            Logger::log($collection->toJson());
        } catch (\Exception $e) {
            Logger::log($e->getMessage(), true);
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
