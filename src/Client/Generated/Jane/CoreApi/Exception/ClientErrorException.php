<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception;

abstract class ClientErrorException extends \RuntimeException implements ClientException, WithResponseInterface
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}