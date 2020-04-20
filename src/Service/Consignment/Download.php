<?php

namespace Gett\MyParcel\Service\Consignment;

use Gett\MyParcel\Constant;
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

        $collection = MyParcelCollection::findMany($id_labels, $this->api_key);
        $collection
            ->setPdfOfLabels($this->fetchPositions())
            ->downloadPdfOfLabels($this->configuration->get(Constant::MY_PARCEL_LABEL_OPEN_DOWNLOAD_CONFIGURATION_NAME, false))
        ;
    }

    private function fetchPositions()
    {
        if ($this->request->get('format') == 'a6') {
            return false;
        }

        return $this->request->get('position');
    }
}
