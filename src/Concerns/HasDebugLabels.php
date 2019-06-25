<?php
/**
 * @author Richard Perdaan <support@myparcel.nl>
 */

namespace MyParcelNL\Sdk\src\Concerns;


trait HasDebugLabels
{
    /**
     * @param $myParcelCollection
     */
    public function debugLinkOfLabels($myParcelCollection, $massage)
    {
        if (! getenv('CI')) {
            echo "\033[32mGenerated " . $massage . ": \033[0m";
            print_r($myParcelCollection->getLinkOfLabels());
            echo "\n\033[0m";
        }
    }
}
