<?php declare(strict_types=1);
/**
 * If you want to add improvements, please create a fork in our GitHub:
 * https://github.com/myparcelnl
 *
 * @author      Reindert Vetter <reindert@myparcel.nl>
 * @copyright   2010-2020 MyParcel
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
 * @link        https://github.com/myparcelnl/sdk
 * @since       File available since Release v0.1.0
 */

namespace MyParcelNL\Sdk\src\Helper;

class LabelHelper
{
    /**
     * Generating positions for A4 paper
     *
     * @param int $start
     *
     * @return string
     */
    public static function getPositions($start)
    {
        $aPositions = [];
        switch ($start) {
            /** @noinspection PhpMissingBreakStatementInspection */
            case 1:
                $aPositions[] = 1;
            /** @noinspection PhpMissingBreakStatementInspection */
            case 2:
                $aPositions[] = 2;
            /** @noinspection PhpMissingBreakStatementInspection */
            case 3:
                $aPositions[] = 3;
            /** @noinspection PhpMissingBreakStatementInspection */
            case 4:
                $aPositions[] = 4;
                break;
        }

        return implode(';', $aPositions);
    }
}
