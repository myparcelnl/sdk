<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Rule\Order;

use Exception;
use MyParcelNL\Sdk\src\Model\Fulfilment\OrderNote;
use MyParcelNL\Sdk\src\Rule\Rule;

class MaximumNoteLengthRule extends Rule
{
    public const MAXIMUM_NOTE_LENGTH = 2500;

    /**
     * @param $validationSubject OrderNote
     *
     * @throws \Exception
     */
    public function validate($validationSubject): void
    {
        if (null === $validationSubject->getNote()) {
            return;
        }

        if (strlen($validationSubject->getNote()) > self::MAXIMUM_NOTE_LENGTH) {
            $this->addError(sprintf('The note may not be longer than %s characters', self::MAXIMUM_NOTE_LENGTH));
        }
    }
}
