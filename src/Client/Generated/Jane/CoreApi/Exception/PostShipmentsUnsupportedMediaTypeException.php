<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception;

class PostShipmentsUnsupportedMediaTypeException extends UnsupportedMediaTypeException
{
    public function __construct(
        /**
         * @var \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesProblem
         */
        private readonly \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesProblem $commonResponsesProblem,
        /**
         * @var \Symfony\Contracts\HttpClient\ResponseInterface
         */
        private readonly \Symfony\Contracts\HttpClient\ResponseInterface $response
    )
    {
        parent::__construct('This response is given when the request Content-Type is not supported.

The representation is selected via standard HTTP content negotiation
on the `Accept` header. By default the legacy MyParcel error envelope
is returned with `Content-Type: application/json`. When the caller
lists `application/problem+json` in `Accept`, the response is returned
with `Content-Type: application/problem+json` and the RFC 9457
problem-detail document instead.
');
    }
    public function getCommonResponsesProblem(): \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesProblem
    {
        return $this->commonResponsesProblem;
    }
    public function getResponse(): \Symfony\Contracts\HttpClient\ResponseInterface
    {
        return $this->response;
    }
}