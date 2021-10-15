<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Exception;

use Exception;

class ValidationException extends Exception
{
    /**
     * @var string[]
     */
    private $errors = [];

    /**
     * Array of error messages.
     *
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return string
     */
    public function getHumanMessage(): string
    {
        return $this->getMessage() . ": '" . implode("', '", $this->getErrors()) . "'";
    }

    /**
     * @param  string[] $errors
     *
     * @return self
     */
    public function setErrors(array $errors): self
    {
        $this->errors = $errors;
        return $this;
    }
}
