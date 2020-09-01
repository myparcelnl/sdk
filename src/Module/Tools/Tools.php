<?php

namespace Gett\MyparcelBE\Module\Tools;

use Tools as ToolsPresta;

class Tools extends ToolsPresta
{
    /**
     * Clean comma, spaces and dot signs from numbers
     * @param string|int|float $val
     * @return string
     */
    public static function normalizeFloat($val): string
    {
        $input = str_replace(' ', '', (string) $val);
        $number = str_replace(',', '.', $input);
        if (strpos($number, '.')) {
            $groups = explode('.', $number);
            $lastGroup = array_pop($groups);
            $number = implode('', $groups) . '.' . $lastGroup;
        }

        return $number;
    }
}
