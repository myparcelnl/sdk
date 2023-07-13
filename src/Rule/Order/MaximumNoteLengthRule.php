<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Rule\Order;

use Exception;
use MyParcelNL\Sdk\src\Rule\Rule;

class MaximumNoteLengthRule extends Rule
{
    public const MAXIMUM_NOTE_LENGTH = 2500;

    /**
     * @throws \Exception
     */
    public function validate($validationSubject): void
    {
        if (null === $validationSubject->getNote()) {
            return;
        }

        if (strlen($validationSubject->getNote()) > self::MAXIMUM_NOTE_LENGTH) {
            throw new Exception(sprintf('The note may not be longer than %s characters', self::MAXIMUM_NOTE_LENGTH));
        }
    }
}
