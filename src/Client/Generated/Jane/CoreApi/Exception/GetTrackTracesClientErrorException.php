<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception;

class GetTrackTracesClientErrorException extends ClientErrorException
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
        parent::__construct('Client-side error response. The representation is selected via
standard HTTP content negotiation on the `Accept` header.

- **Default:** when the caller does **not** list
  `application/problem+json` in `Accept`, the response is returned
  with `Content-Type: application/json` and the legacy MyParcel
  error envelope (`{ message, request_id, status_code, errors[] }`).
- **Opt-in:** when the caller lists `application/problem+json` in
  `Accept`, the response is returned with
  `Content-Type: application/problem+json` and the RFC 9457
  problem-detail document (the fields from section 3.1).
  Sub-errors carry a JSON Pointer in `instance` and a narrowed
  `type` URI derived from the error code.
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