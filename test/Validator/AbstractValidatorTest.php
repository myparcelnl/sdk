<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Validator;

use MyParcelNL\Sdk\src\Exception\ValidationException;
use MyParcelNL\Sdk\src\Rule\Rule;
use MyParcelNL\Sdk\src\Validator\AbstractValidator;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class TestRule extends Rule
{
    /**
     * @param  bool $validationSubject
     */
    public function validate($validationSubject): void
    {
        if ($validationSubject) {
            $this->addError('Error');
        }
    }
}

class AbstractValidatorTest extends TestCase
{
    /**
     * @return array
     */
    public function provideValidatorData(): array
    {
        return [
            [
                [
                    new TestRule(),
                    new TestRule(),
                    new TestRule(),
                ],
            ],
        ];
    }

    /**
     * @param  array $rules
     *
     * @throws \Exception
     * @dataProvider provideValidatorData
     */
    public function testValidator(array $rules): void
    {
        $testValidator               = $this->createAbstractValidator();
        $testValidator::$staticRules = $rules;

        // Expecting no error to be thrown
        (new $testValidator())
            ->validateAll(false)
            ->report();

        $this->expectException(ValidationException::class);
        (new $testValidator())
            ->validateAll(true)
            ->report();
    }

    /**
     * @return \MyParcelNL\Sdk\src\Validator\AbstractValidator
     */
    protected function createAbstractValidator(): AbstractValidator
    {
        return new class extends AbstractValidator {
            /**
             * @see https://stackoverflow.com/a/49038436/10225966
             * @var \MyParcelNL\Sdk\src\Rule\Rule[]
             */
            public static $staticRules;

            /**
             * @return \MyParcelNL\Sdk\src\Rule\Rule[]
             */
            protected function getRules(): array
            {
                return self::$staticRules;
            }
        };
    }
}
