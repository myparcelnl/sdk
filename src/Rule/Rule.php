<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Rule;

use MyParcelNL\Sdk\src\Support\Collection;

abstract class Rule implements RuleInterface
{
    /**
     * @var array
     */
    private $errors;

    public function __construct()
    {
        $this->errors = new Collection();
    }

    /**
     * @param  string $string
     */
    public function addError(string $string): void
    {
        $this->errors->push($string);
    }

    /**
     * @return array|\MyParcelNL\Sdk\src\Support\Collection
     */
    public function getErrors(): Collection
    {
        return $this->errors;
    }
}
