<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception;

class PatchShipmentsServerErrorException extends ServerErrorException
{
    public function __construct(
        /**
         * @var \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesSystemError|\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesProblem
         */
        private readonly \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesSystemError|\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesProblem $commonResponsesProblem,
        /**
         * @var \Symfony\Contracts\HttpClient\ResponseInterface
         */
        private readonly \Symfony\Contracts\HttpClient\ResponseInterface $response
    )
    {
        parent::__construct('Server-side error response. The representation is selected via
standard HTTP content negotiation on the `Accept` header.

- **Default:** when the caller does **not** list
  `application/problem+json` in `Accept`, the legacy MyParcel
  system-error envelope is returned with
  `Content-Type: application/json`.
- **Opt-in:** when the caller lists `application/problem+json` in
  `Accept`, the response is returned with
  `Content-Type: application/problem+json` and the RFC 9457
  problem-detail document. Internal error codes are not included
  in the type URI. A 5xx response on this path uses
  `type: urn:problem:server-error`.
');
    }
    public function getCommonResponsesProblem(): \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesSystemError|\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesProblem
    {
        return $this->commonResponsesProblem;
    }
    public function getResponse(): \Symfony\Contracts\HttpClient\ResponseInterface
    {
        return $this->response;
    }
}