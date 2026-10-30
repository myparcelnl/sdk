<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model;

class CommonResponsesUserError
{
    /**
     * @var string
     */
    public string $message;
    /**
     * Internal request ID, useful for debugging purposes.
     *
     * @var string
     */
    public string $requestId;
    /**
     * The request contains bad syntax or cannot be fulfilled
     *
     * @var int
     */
    public int $statusCode;
    /**
     * @var list<CommonErrorUser>
     */
    public array $errors;
}