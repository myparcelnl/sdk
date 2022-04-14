<?php

namespace MyParcelNL\Sdk\src\Rule;

use Exception;
use MyParcelNL\Sdk\src\Helper\Utils;

/**
 * Require a field in $validationSubject.
 */
class RequiredRule extends Rule
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $requiredType;

    /**
     * @param  string $field
     */
    public function __construct(string $field)
    {
        parent::__construct();
        $this->field = $field;
    }

    /**
     * @return \MyParcelNL\Sdk\src\Rule\RequiredRule
     */
    public function integer(): self
    {
        $this->requiredType = 'integer';
        return $this;
    }

    /**
     * @param $validationSubject
     *
     * @return void
     */
    public function validate($validationSubject): void
    {
        $getter = Utils::createMethodName($this->field);

        try {
            $result = $validationSubject->{$getter}();

            if (! $result) {
                $this->addError("Field $this->field must be set.");
            }
        } catch (Exception $e) {
            $this->addError($e->getMessage());
        }
    }
}
