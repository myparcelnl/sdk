<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Exception;

class GetWhoamiUnauthorizedException extends UnauthorizedException
{
    public function __construct(
        /**
         * @var \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\UnauthorizedResponse
         */
        private readonly \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\UnauthorizedResponse $unauthorizedResponse,
        /**
         * @var \Symfony\Contracts\HttpClient\ResponseInterface
         */
        private readonly \Symfony\Contracts\HttpClient\ResponseInterface $response
    )
    {
        parent::__construct('Invalid or missing credentials');
    }
    public function getUnauthorizedResponse(): \MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model\UnauthorizedResponse
    {
        return $this->unauthorizedResponse;
    }
    public function getResponse(): \Symfony\Contracts\HttpClient\ResponseInterface
    {
        return $this->response;
    }
}