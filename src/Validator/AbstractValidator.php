<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Validator;

use MyParcelNL\Sdk\src\Exception\ValidationException;
use MyParcelNL\Sdk\src\Rule\Rule;
use MyParcelNL\Sdk\src\Support\Collection;

abstract class AbstractValidator
{
    /**
     * @var array
     */
    private $errors;

    /**
     * @var int
     */
    private $validationCode = 0;

    public function __construct()
    {
        $this->errors = new Collection();
    }

    /**
     * @return \MyParcelNL\Sdk\src\Rule\Rule[]
     */
    abstract protected function getRules(): array;

    /**
     * @throws \Exception
     */
    public function report(): void
    {
        if ($this->errors->isNotEmpty()) {
            $exception = new ValidationException('Validation failed', $this->validationCode);
            $exception->setErrors($this->errors->toArray());

            throw $exception;
        }
    }

    /**
     * @param  mixed $validationSubject
     */
    public function validateAll($validationSubject): AbstractValidator
    {
        $rules = new Collection($this->getRules());

        $rules->map(function (Rule $rule) use ($validationSubject) {
            $rule->validate($validationSubject);
            $this->errors->push(...$rule->getErrors());
        });

        return $this;
    }
}

