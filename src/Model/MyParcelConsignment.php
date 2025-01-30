<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\Model;

/**
 * @deprecated Use \MyParcelNL\Sdk\Model\Consignment\PostNLConsignment instead
 */
class MyParcelConsignment
{
    /** @noinspection PhpMissingParentConstructorInspection */
    public function __construct()
    {
        throw new \BadMethodCallException('The class MyParcelConsignment is deprecated use PostNLConsignment, BpostConsignment or DPDConsignment instead.');
    }
}
