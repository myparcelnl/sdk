<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Exception;

class PatchShippingRulesServerErrorException extends ServerErrorException
{
    public function __construct(
        /**
         * @var \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\CommonResponsesSystemError
         */
        private readonly \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\CommonResponsesSystemError $commonResponsesSystemError,
        /**
         * @var \Symfony\Contracts\HttpClient\ResponseInterface
         */
        private readonly \Symfony\Contracts\HttpClient\ResponseInterface $response
    )
    {
        parent::__construct('This response is given when the server fails to fulfill a valid request due to a server-side issue.');
    }
    public function getCommonResponsesSystemError(): \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\CommonResponsesSystemError
    {
        return $this->commonResponsesSystemError;
    }
    public function getResponse(): \Symfony\Contracts\HttpClient\ResponseInterface
    {
        return $this->response;
    }
}