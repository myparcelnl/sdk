<?php

namespace Gett\MyParcel\Module\Configuration;

use Gett\MyParcel\Constant;
use Module;

class Configure
{
    /** @var Module */
    private $module;

    /** @var array */
    private $forms = [
        Constant::MENU_API_SETTINGS => ApiForm::class,
        Constant::MENU_GENERAL_SETTINGS => GeneralForm::class,
        Constant::MENU_LABEL_SETTINGS => LabelForm::class,
        Constant::MENU_ORDER_SETTINGS => OrderForm::class,
        Constant::MENU_CUSTOMS_SETTINGS => CustomsForm::class,
    ];

    public function __construct(Module $module)
    {
        $this->module = $module;
    }

    public function __invoke(int $id_form): string
    {
        $output = (new $this->forms[$id_form]($this->module))();


        return $output;
    }

}