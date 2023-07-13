<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Rule\Order;

use Exception;
use MyParcelNL\Sdk\src\Model\Fulfilment\OrderNote;
use MyParcelNL\Sdk\src\Rule\Rule;

class OrderNoteAuthorRule extends Rule
{
    private const AUTHOR_CUSTOMER = 'customer';
    private const AUTHOR_WEBSHOP  = 'webshop';
    private const AUTHORS_ALLOWED = [
        self::AUTHOR_CUSTOMER,
        self::AUTHOR_WEBSHOP,
    ];

    /**
     * @param $validationSubject OrderNote
     *
     * @return void
     * @throws \Exception
     */
    public function validate($validationSubject): void
    {
        if (! in_array($validationSubject->getAuthor(), self::AUTHORS_ALLOWED, true)) {
            throw new Exception(sprintf('Author must be one of %s', implode(', ', self::AUTHORS_ALLOWED)));
        }
    }
}
