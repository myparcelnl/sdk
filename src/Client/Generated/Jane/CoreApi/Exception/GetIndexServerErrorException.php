<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception;

class GetIndexServerErrorException extends ServerErrorException
{
    public function __construct(
        /**
         * @var \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesSystemError
         */
        private readonly \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesSystemError $commonResponsesSystemError,
        /**
         * @var \Symfony\Contracts\HttpClient\ResponseInterface
         */
        private readonly \Symfony\Contracts\HttpClient\ResponseInterface $response
    )
    {
        parent::__construct('This response is given when the server fails to fulfill a valid request due to a server-side issue.');
    }
    public function getCommonResponsesSystemError(): \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesSystemError
    {
        return $this->commonResponsesSystemError;
    }
    public function getResponse(): \Symfony\Contracts\HttpClient\ResponseInterface
    {
        return $this->response;
    }
}