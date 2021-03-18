<?php

namespace Gett\MyparcelBE\Service;

class ErrorMessage
{
    private $module;

    public function __construct($module)
    {
        $this->module = $module;
    }

    public function get(string $message = ''): string
    {
        if (empty($message)) {
            return '';
        }

        $parsedMessage = '';
        switch (true) {
            case strpos($message, 'cc not supported') !== false:
                $parsedMessage = $this->module->l(
                    'Shipment validation error: Country not supported.',
                    'errormessage'
                );
                break;
        }

        return $parsedMessage;
    }
}
