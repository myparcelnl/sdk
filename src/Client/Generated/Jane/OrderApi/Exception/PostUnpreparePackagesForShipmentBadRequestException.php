<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Exception;

class PostUnpreparePackagesForShipmentBadRequestException extends BadRequestException
{
    public function __construct(
        /**
         * @var \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ProblemDetailsInvalidRequestSyntax
         */
        private readonly \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ProblemDetailsInvalidRequestSyntax $problemDetailsInvalidRequestSyntax,
        /**
         * @var \Symfony\Contracts\HttpClient\ResponseInterface
         */
        private readonly \Symfony\Contracts\HttpClient\ResponseInterface $response
    )
    {
        parent::__construct('Invalid request syntax');
    }
    public function getProblemDetailsInvalidRequestSyntax(): \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ProblemDetailsInvalidRequestSyntax
    {
        return $this->problemDetailsInvalidRequestSyntax;
    }
    public function getResponse(): \Symfony\Contracts\HttpClient\ResponseInterface
    {
        return $this->response;
    }
}