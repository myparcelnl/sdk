<?php

declare(strict_types=1);

// Load environment variables from .env file
$envFile = __DIR__ . '/../.env';

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue; // Skip comments
        }
        
        [$name, $value] = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        
        if (!array_key_exists($name, $_ENV)) {
            $_ENV[$name] = $value;
        }
        
        if (!array_key_exists($name, $_SERVER)) {
            $_SERVER[$name] = $value;
        }
    }
}

require_once __DIR__ . '/../vendor/autoload.php';
