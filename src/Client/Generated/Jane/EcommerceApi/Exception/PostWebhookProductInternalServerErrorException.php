<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception;

class PostWebhookProductInternalServerErrorException extends InternalServerErrorException
{
    public function __construct(
        /**
         * @var \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ProblemDetailsInternalServerError
         */
        private readonly \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ProblemDetailsInternalServerError $problemDetailsInternalServerError,
        /**
         * @var \Symfony\Contracts\HttpClient\ResponseInterface
         */
        private readonly \Symfony\Contracts\HttpClient\ResponseInterface $response
    )
    {
        parent::__construct('Internal server error');
    }
    public function getProblemDetailsInternalServerError(): \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ProblemDetailsInternalServerError
    {
        return $this->problemDetailsInternalServerError;
    }
    public function getResponse(): \Symfony\Contracts\HttpClient\ResponseInterface
    {
        return $this->response;
    }
}