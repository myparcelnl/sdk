<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception;

class PostUnrelatedReturnShipmentsNotFoundException extends NotFoundException
{
    public function __construct(
        /**
         * @var \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesUserErrorNotFound|\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesProblem
         */
        private readonly \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesUserErrorNotFound|\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesProblem $commonResponsesProblem,
        /**
         * @var \Symfony\Contracts\HttpClient\ResponseInterface
         */
        private readonly \Symfony\Contracts\HttpClient\ResponseInterface $response
    )
    {
        parent::__construct('If this option is not enables then `HTTP 404 Not Found` is returned.');
    }
    public function getCommonResponsesProblem(): \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesUserErrorNotFound|\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesProblem
    {
        return $this->commonResponsesProblem;
    }
    public function getResponse(): \Symfony\Contracts\HttpClient\ResponseInterface
    {
        return $this->response;
    }
}