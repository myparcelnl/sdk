<?php

require_once __DIR__ . '/vendor/autoload.php';

if (file_exists(__DIR__ . '/.env')) {
    foreach (file(__DIR__ . '/.env') as $line) {
        if (strpos(trim($line), '#') === 0 || trim($line) === '') {
            continue;
        }

        list($name, $value) = explode('=', trim($line), 2);
        $_ENV[$name] = $value;
    }
}

