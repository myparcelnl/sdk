<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Exception;

class GetShippingRuleImplicationsClientErrorException extends ClientErrorException
{
    public function __construct(
        /**
         * @var \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\CommonResponsesUserError
         */
        private readonly \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\CommonResponsesUserError $commonResponsesUserError,
        /**
         * @var \Symfony\Contracts\HttpClient\ResponseInterface
         */
        private readonly \Symfony\Contracts\HttpClient\ResponseInterface $response
    )
    {
        parent::__construct('This response is given when the request contains bad syntax or cannot be fulfilled due to a client-side issue.');
    }
    public function getCommonResponsesUserError(): \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\CommonResponsesUserError
    {
        return $this->commonResponsesUserError;
    }
    public function getResponse(): \Symfony\Contracts\HttpClient\ResponseInterface
    {
        return $this->response;
    }
}