<?php

namespace Gett\MyParcel\Service;

class CarrierConfigurationProvider
{
    private $id_carrier;
    private $params;

    public function __construct(int $id_carrier)
    {
        $this->id_carrier = $id_carrier;
    }

    public function get(string $name)
    {
        if (empty($this->params)) {
            $params = \Db::getInstance()->executeS('SELECT * FROM ' . _DB_PREFIX_ . "myparcel_carrier_configuration WHERE id_carrier = '" . $this->id_carrier . "' ");
            foreach ($params as $param) {
                $this->params[$param['name']] = $param['value'];
            }
        }

        return isset($this->params[$name]) ? $this->params[$name] : null;
    }
}
