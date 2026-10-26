<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Exception;

class BadResponseException extends UnexpectedStatusCodeException
{
    public function __construct($status, $message = '', ?\Symfony\Contracts\HttpClient\ResponseInterface $response = null)
    {
        parent::__construct($status, $message, $response);
    }
}