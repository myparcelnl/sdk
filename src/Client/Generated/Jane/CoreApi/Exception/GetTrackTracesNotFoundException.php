<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception;

class GetTrackTracesNotFoundException extends NotFoundException
{
    public function __construct(
        /**
         * @var \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesUserErrorNotFound
         */
        private readonly \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesUserErrorNotFound $commonResponsesUserErrorNotFound,
        /**
         * @var \Symfony\Contracts\HttpClient\ResponseInterface
         */
        private readonly \Symfony\Contracts\HttpClient\ResponseInterface $response
    )
    {
        parent::__construct('The requested resource could not be found or its existence may not be
disclosed. This may be temporary or permanent.
');
    }
    public function getCommonResponsesUserErrorNotFound(): \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesUserErrorNotFound
    {
        return $this->commonResponsesUserErrorNotFound;
    }
    public function getResponse(): \Symfony\Contracts\HttpClient\ResponseInterface
    {
        return $this->response;
    }
}