<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Exception;

class PostConnectTokenUnauthorizedException extends UnauthorizedException
{
    public function __construct(
        /**
         * @var \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectTokenPostResponse401
         */
        private readonly \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectTokenPostResponse401 $connectTokenPostResponse401,
        /**
         * @var \Symfony\Contracts\HttpClient\ResponseInterface
         */
        private readonly \Symfony\Contracts\HttpClient\ResponseInterface $response
    )
    {
        parent::__construct('The DPoP proof failed verification.');
    }
    public function getConnectTokenPostResponse401(): \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectTokenPostResponse401
    {
        return $this->connectTokenPostResponse401;
    }
    public function getResponse(): \Symfony\Contracts\HttpClient\ResponseInterface
    {
        return $this->response;
    }
}