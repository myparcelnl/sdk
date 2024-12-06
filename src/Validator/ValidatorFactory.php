<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Validator;

use MyParcelNL\Sdk\Support\Classes;

class ValidatorFactory
{
    /**
     * @param  null|string $validatorClass
     *
     * @return null|\MyParcelNL\Sdk\Validator\AbstractValidator
     * @throws \Exception
     */
    public static function create(?string $validatorClass): ?AbstractValidator
    {
        if ($validatorClass) {
            return Classes::instantiateClass($validatorClass, AbstractValidator::class);
        }

        return null;
    }
}
