<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception;

class PostConnectRefreshUnsupportedMediaTypeException extends UnsupportedMediaTypeException
{
    public function __construct(
        /**
         * @var \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ProblemDetailsUnsupportedMediaType
         */
        private readonly \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ProblemDetailsUnsupportedMediaType $problemDetailsUnsupportedMediaType,
        /**
         * @var \Symfony\Contracts\HttpClient\ResponseInterface
         */
        private readonly \Symfony\Contracts\HttpClient\ResponseInterface $response
    )
    {
        parent::__construct('Unsupported media type. Send the request with Content-Type application/json.');
    }
    public function getProblemDetailsUnsupportedMediaType(): \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ProblemDetailsUnsupportedMediaType
    {
        return $this->problemDetailsUnsupportedMediaType;
    }
    public function getResponse(): \Symfony\Contracts\HttpClient\ResponseInterface
    {
        return $this->response;
    }
}