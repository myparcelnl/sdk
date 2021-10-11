<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Validator;

use MyParcelNL\Sdk\src\Support\Classes;

class ValidatorFactory
{
    /**
     * @param  null|string $validatorClass
     *
     * @return null|\MyParcelNL\Sdk\src\Validator\AbstractValidator
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
