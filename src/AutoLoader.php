<?php

declare(strict_types=1);

// Require all php files
foreach (getPhpFiles() as $phpFile) {
    require_once($phpFile);
}

/**
 * @param string $dir
 *
 * @return array
 */
function getPhpFiles(string $dir = '.'): array
{
    $root = scandir($dir);

    // Loop in all folders and select the PHP files
    foreach ($root as $value)
    {
        if ($value === '.' || $value === '..') {
            continue;
        }

        // Find if there is a php file in the directory
        if (is_file("$dir/$value")) {
            if (strpos($value, '.php')) {
                $result[]="$dir/$value";
            }
            continue;
        }

        // find PHP files in sub-folders  with a recursive foreach
        foreach (getPhpFiles("$dir/$value") as $path)
        {
            $result[] = $path;
        }
    }
    return $result;
}


