<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception;

class PostShipmentsClientErrorException extends ClientErrorException
{
    public function __construct(
        /**
         * @var \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesUserError|\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesProblem
         */
        private readonly \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesUserError|\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesProblem $commonResponsesProblem,
        /**
         * @var \Symfony\Contracts\HttpClient\ResponseInterface
         */
        private readonly \Symfony\Contracts\HttpClient\ResponseInterface $response
    )
    {
        parent::__construct('Client-side error response. The representation is selected via
standard HTTP content negotiation on the `Accept` header:

- **Default** — when the caller does **not** list
  `application/problem+json` in `Accept`, the response is returned
  with `Content-Type: application/json` and the legacy MyParcel
  error envelope (`{ message, request_id, status_code, errors[] }`).
- **Opt-in** — when the caller lists `application/problem+json` in
  `Accept`, the response is returned with `Content-Type: application/problem+json`
  and the RFC 9457 problem-detail document with the strict §3.1
  fields. Sub-errors carry a JSON Pointer in `instance` and a
  narrowed `type` URI derived from the underlying UserException
  constant.
');
    }
    public function getCommonResponsesProblem(): \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesUserError|\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesProblem
    {
        return $this->commonResponsesProblem;
    }
    public function getResponse(): \Symfony\Contracts\HttpClient\ResponseInterface
    {
        return $this->response;
    }
}