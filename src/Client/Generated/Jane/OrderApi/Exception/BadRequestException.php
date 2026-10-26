<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception;

abstract class BadRequestException extends \RuntimeException implements ClientException, WithResponseInterface
{
    public function __construct(string $message)
    {
        parent::__construct($message, 400);
    }
}