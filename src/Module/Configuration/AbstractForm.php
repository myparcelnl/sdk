<?php

namespace Gett\MyParcel\Module\Configuration;

use AdminController;
use Configuration;
use HelperForm;
use Module;
use Tools;

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
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->module->name . '&menu=' . Tools::getValue('menu',
                0);

        // Language
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
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

    private function process(): string
    {
        if (!Tools::isSubmit($this->name . 'Submit')) {
            return '';
        }
        return $this->update();
        if ($this->validate()) {

        }

        $text = implode(' ', [
            $this->trans('Form field values doesn\'t meet the validation criteria.', [],
                'Modules.Productcombinator.Module'),
            $this->trans('Are you sure all values are correct?', [], 'Modules.Productcombinator.Module'),
        ]);

        return $this->module->displayError($text);
    }

    protected function update(): string
    {
        $result = true;

        foreach (array_keys($this->getFields()) as $name) {
            $value = Tools::getValue($name, Configuration::get($name));
            $result = $result && Configuration::updateValue($name, trim($value));
        }

        if (!$result) {
            return $this->module->displayError(
                $this->trans('Could not update configuration!', [], 'Modules.Productcombinator.Module')
            );
        }

        return $this->module->displayConfirmation(
            $this->trans('Configuration was successfully updated!', [], 'Modules.Productcombinator.Module')
        );
    }

    abstract protected function getFields(): array;

    protected function trans(string $key, array $parameters = [], $domain = null, $locale = null): string
    {
        return $this->module->getTranslator()->trans($key, $parameters, $domain, $locale);
    }

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
        }

        return $values;
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
                    'title' => $this->trans('Save', [], 'Modules.Productcombinator.Module'),
                ],
            ],
        ];

        foreach ($this->getFields() as $name => $field) {
            $field['name'] = $name;
            $field['desc'] = isset($field['desc']) ? implode(' ', (array)$field['desc']) : null;

            $form['form']['input'][] = $field;
        }

        return ['form' => $form];
    }

    abstract protected function getLegend(): string;
}