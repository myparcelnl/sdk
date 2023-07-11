<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Rule\Order;

use Exception;
use MyParcelNL\Sdk\src\Rule\Rule;

class MaximumNoteLengthRule extends Rule
{
    public const MaximumNoteLength = 2500;

    /**
     * @throws \Exception
     */
    public function validate($validationSubject): void
    {
        if (null === $validationSubject->getNote()) {
            return;
        }

        if (strlen($validationSubject->getNote()) > self::MaximumNoteLength) {
            throw new Exception(sprintf('The note may not be longer than %s characters', self::MaximumNoteLength));
        }
    }
}
