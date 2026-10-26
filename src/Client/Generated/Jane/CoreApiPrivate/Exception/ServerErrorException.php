<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Exception;

abstract class ServerErrorException extends \RuntimeException implements ServerException, WithResponseInterface
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}