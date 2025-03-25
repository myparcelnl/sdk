<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Rule;

interface RuleInterface
{
    /**
     * @param  mixed $validationSubject
     */
    public function validate($validationSubject): void;
}
