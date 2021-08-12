<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Concerns;

trait HasDebugLabels
{
    /**
     * @param $myParcelCollection
     * @param $message
     *
     * @return void
     */
    public function debugLinkOfLabels($myParcelCollection, $message): void
    {
        if (! getenv('CI')) {
            echo "\033[32mGenerated " . $message . ": \033[0m";
            print_r($myParcelCollection->getLinkOfLabels());
            echo "\n\033[0m";
        }
    }
}
