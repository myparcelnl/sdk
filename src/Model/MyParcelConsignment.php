<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model;

/**
 * @deprecated Use \MyParcelNL\Sdk\src\Model\PostNLConsignment instead
 */
class MyParcelConsignment
{
    /** @noinspection PhpMissingParentConstructorInspection */
    public function __construct()
    {
        throw new \BadMethodCallException('The class MyParcelConsignment is deprecated use PostNLConsignment or BpostConsignment instead.');
    }
}