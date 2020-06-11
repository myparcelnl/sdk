<?php

namespace Gett\MyparcelBE\Service;

class MyparcelStatusProvider
{
    private $module;

    public function __construct()
    {
        $this->module = \Module::getInstanceByName('myparcelbe');
    }

    public function getStatus(int $id_status)
    {
        return $this->getStatuses()[$id_status];
    }

    private function getStatuses()
    {
        return [
            1 => $this->module->l('ending - concept', 'myparcelstatusprovider'),
            2 => $this->module->l('pending - registered', 'myparcelstatusprovider'),
            3 => $this->module->l('enroute - handed to carrier', 'myparcelstatusprovider'),
            4 => $this->module->l('enroute - sorting', 'myparcelstatusprovider'),
            5 => $this->module->l('enroute - distribution', 'myparcelstatusprovider'),
            6 => $this->module->l('enroute - customs', 'myparcelstatusprovider'),
            7 => $this->module->l('delivered - at recipient', 'myparcelstatusprovider'),
            8 => $this->module->l('delivered - ready for pickup', 'myparcelstatusprovider'),
            9 => $this->module->l('delivered - package picked up', 'myparcelstatusprovider'),
            10 => $this->module->l('delivered - return shipment ready for pickup', 'myparcelstatusprovider'),
            11 => $this->module->l('delivered - return shipment package picked up', 'myparcelstatusprovider'),
            12 => $this->module->l('printed - letter', 'myparcelstatusprovider'),
            13 => $this->module->l('inactive - credited', 'myparcelstatusprovider'),
            14 => $this->module->l('printed - digital stamp', 'myparcelstatusprovider'),
            30 => $this->module->l('inactive - concept', 'myparcelstatusprovider'),
            31 => $this->module->l('inactive - registered', 'myparcelstatusprovider'),
            32 => $this->module->l('inactive - enroute - handed to carrier', 'myparcelstatusprovider'),
            33 => $this->module->l('inactive - enroute - sorting', 'myparcelstatusprovider'),
            34 => $this->module->l('inactive - enroute - distribution', 'myparcelstatusprovider'),
            35 => $this->module->l('inactive - enroute - customs', 'myparcelstatusprovider'),
            36 => $this->module->l('inactive - delivered - at recipient', 'myparcelstatusprovider'),
            37 => $this->module->l('inactive - delivered - ready for pickup', 'myparcelstatusprovider'),
            38 => $this->module->l('inactive - delivered - package picked up', 'myparcelstatusprovider'),
            99 => $this->module->l('inactive - unknown', 'myparcelstatusprovider'),
        ];
    }
}
