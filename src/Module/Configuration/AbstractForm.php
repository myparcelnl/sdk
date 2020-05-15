<?php

namespace Gett\MyParcel\Module\Configuration;

use Tools;
use Module;
use HelperForm;
use Configuration;
use AdminController;

abstract class AbstractForm
{
    /** @var Module */
    protected $module;

    /** @var string */
    protected $legend;

    /** @var string */
    protected $icon = 'cog';

    /** @var string */
    private $name;

    public function __construct(Module $module)
    {
        $this->module = $module;
        $this->name = str_replace(' ', '', $module->displayName) . self::class;
    }

    public function __invoke(): string
    {
        $helper = new HelperForm();

        // Process prev form
        $resultProcess = $this->process();

        // Module, token and current index
        $helper->module = $this->module;
        $helper->name_controller = $this->module->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->module->name . '&menu=' . Tools::getValue(
            'menu',
            0
        );

        // Language
        $helper->default_form_language = (int) Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ?: 0;

        // Field values
        $helper->fields_value = $this->getValues();

        // Title and toolbar
        $helper->title = $this->module->displayName;
        $helper->show_toolbar = false;
        $helper->toolbar_scroll = false;
        $helper->submit_action = $this->name . 'Submit';

        return $resultProcess . $helper->generateForm($this->form());
    }

    protected function update(): string
    {
        $result = true;

        foreach (array_keys($this->getFields()) as $name) {
            if ($name == 'MY_PARCEL_IGNORE_ORDER_STATUS') {
                $ignored = [];
                foreach (Tools::getAllValues() as $key => $value) {
                    if (stripos($key, 'MY_PARCEL_IGNORE_ORDER_STATUS') !== false) {
                        $temp = explode('_', $key);
                        $ignored[] = end($temp);
                    }
                }
                Configuration::updateValue($name, implode(',', $ignored));
            }
            $value = Tools::getValue($name, Configuration::get($name));
            $result = $result && Configuration::updateValue($name, trim($value));
        }

        if (!$result) {
            return $this->module->displayError(
                $this->module->l('Could not update configuration!', 'Modules.Myparcel.Configuration')
            );
        }

        return $this->module->displayConfirmation(
            $this->module->l('Configuration was successfully updated!', 'Modules.Myparcel.Configuration')
        );
    }

    abstract protected function getFields(): array;

    protected function validate(): bool
    {
        $result = true;

        foreach ($this->getFields() as $name => $field) {
            $value = Tools::getValue($name, Configuration::get($name));

            if (!$field['required'] && empty($value)) {
                continue; // Not required
            }

            $result &= $result
                && !is_null($value)
                && $value !== ''
                && call_user_func([Validate::class, $field['validate']], $value);
        }

        return $result;
    }

    protected function getValues(): array
    {
        $values = [];
        foreach ($this->getFields() as $name => $field) {
            $values[$name] = Configuration::get(
                $name,
                null,
                null,
                null,
                isset($field['default']) ? $field['default'] : null
            );
            if ($name == 'MY_PARCEL_IGNORE_ORDER_STATUS') {
                $temp = explode(',', $values[$name]);
                foreach ($temp as $value) {
                    $values["MY_PARCEL_IGNORE_ORDER_STATUS_{$value}"] = 1;
                }
            }
        }

        return $values;
    }

    abstract protected function getLegend(): string;

    private function process(): string
    {
        if (!Tools::isSubmit($this->name . 'Submit')) {
            return '';
        }

        return $this->update();
        if ($this->validate()) {
        }

        $text = implode(' ', [
            $this->module->l(
                'Form field values doesn\'t meet the validation criteria.',
                [],
                'Modules.Myparcel.Configuration'
            ),
            $this->module->l('Are you sure all values are correct?', 'Modules.Myparcel.Configuration'),
        ]);

        return $this->module->displayError($text);
    }

    private function form(): array
    {
        $form = [
            'form' => [
                'id_form' => strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $this->name)),
                'legend' => [
                    'title' => $this->getLegend(),
                    'icon' => 'icon-' . $this->icon,
                ],
                'input' => [],
                'submit' => [
                    'title' => $this->module->l('Save', 'Modules.Myparcel.Configuration'),
                ],
                'buttons' => [],
            ],
        ];

        foreach ($this->getFields() as $name => $field) {
            $field['name'] = $name;
            $field['desc'] = isset($field['desc']) ? implode(' ', (array) $field['desc']) : null;

            $form['form']['input'][] = $field;
        }

        if (method_exists($this, 'getButtons')) {
            foreach ($this->getButtons() as $button) {
                $form['form']['buttons'][] = $button;
            }
        }

        return ['form' => $form];
    }
}
