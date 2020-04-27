<?php

class LabelController extends ModuleAdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->id_lang = $this->context->language->id;
        $this->default_form_language = $this->context->language->id;
    }

    public function initContent()
    {
        parent::initContent();
        
    }
}