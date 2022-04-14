<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Exception;

use Exception;

class ValidationException extends Exception
{
    /**
     * @var string[]
     */
    private $errors;

    /**
     * @param  string $message
     * @param  int    $code
     * @param  array  $errors
     */
    public function __construct(string $message = "", int $code = 0, array $errors = [])
    {
        $fullMessage  = sprintf('%s: "%s', $message, implode(' ', $errors));
        $this->errors = $errors;
        parent::__construct($fullMessage, $code);
    }

    /**
     * Array of error messages.
     *
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
