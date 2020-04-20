<?php

namespace Gett\MyParcel\Service;

class MyparcelStatusProvider
{
    private $module;

    public function __construct()
    {
        $this->module = \Module::getInstanceByName('myparcel');
    }

    public function getStatus(int $id_status)
    {
        return $this->getStatuses()[$id_status];
    }

    private function getStatuses()
    {
        return [
            1 => $this->module->l('ending - concept'),
            2 => $this->module->l('pending - registered'),
            3 => $this->module->l('enroute - handed to carrier'),
            4 => $this->module->l('enroute - sorting'),
            5 => $this->module->l('enroute - distribution'),
            6 => $this->module->l('enroute - customs'),
            7 => $this->module->l('delivered - at recipient'),
            8 => $this->module->l('delivered - ready for pickup'),
            9 => $this->module->l('delivered - package picked up'),
            10 => $this->module->l('delivered - return shipment ready for pickup'),
            11 => $this->module->l('delivered - return shipment package picked up'),
            12 => $this->module->l('printed - letter'),
            13 => $this->module->l('inactive - credited'),
            14 => $this->module->l('printed - digital stamp'),
            30 => $this->module->l('inactive - concept'),
            31 => $this->module->l('inactive - registered'),
            32 => $this->module->l('inactive - enroute - handed to carrier'),
            33 => $this->module->l('inactive - enroute - sorting'),
            34 => $this->module->l('inactive - enroute - distribution'),
            35 => $this->module->l('inactive - enroute - customs'),
            36 => $this->module->l('inactive - delivered - at recipient'),
            37 => $this->module->l('inactive - delivered - ready for pickup'),
            38 => $this->module->l('inactive - delivered - package picked up'),
            99 => $this->module->l('inactive - unknown'),
        ];
    }
}
