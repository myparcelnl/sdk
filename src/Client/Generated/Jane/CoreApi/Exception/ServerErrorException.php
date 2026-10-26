<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception;

abstract class ServerErrorException extends \RuntimeException implements ServerException, WithResponseInterface
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}