<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Exception;

class GetWhoamiInternalServerErrorException extends InternalServerErrorException
{
    public function __construct(
        /**
         * @var \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\ProblemDetailsInternalServerError
         */
        private readonly \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\ProblemDetailsInternalServerError $problemDetailsInternalServerError,
        /**
         * @var \Symfony\Contracts\HttpClient\ResponseInterface
         */
        private readonly \Symfony\Contracts\HttpClient\ResponseInterface $response
    )
    {
        parent::__construct('Internal server error');
    }
    public function getProblemDetailsInternalServerError(): \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\ProblemDetailsInternalServerError
    {
        return $this->problemDetailsInternalServerError;
    }
    public function getResponse(): \Symfony\Contracts\HttpClient\ResponseInterface
    {
        return $this->response;
    }
}