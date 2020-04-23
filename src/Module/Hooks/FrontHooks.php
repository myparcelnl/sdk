<?php

namespace Gett\MyParcel\Module\Hooks;

trait FrontHooks
{
    public function hookActionCarrierProcess()
    {
//        var_dump($_POST);die();
    }

    public function hookDisplayHeader()
    {
      if ($this->context->controller instanceof \OrderController){
          $this->context->controller->addCss($this->_path . 'views/css/myparcel.css');
          $this->context->controller->addJs($this->_path . 'node_modules/@myparcel/delivery-options/dist/myparcel.js');
          $this->context->controller->addJs($this->_path . 'views/js/myparcelinit.js');
      }

    }

    public function hookDisplayCarrierExtraContent()
    {
        $address = new \Address($this->context->cart->id_address_delivery);
        if (\Validate::isLoadedObject($address)) {
            $address->address1 = preg_replace('/[^0-9]/', '', $address->address1);

            $this->context->smarty->assign([
                'address' => $address,
            ]);

            return $this->display($this->name, 'views/templates/hook/carrier.tpl');
        }
    }
}