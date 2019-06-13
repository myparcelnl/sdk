<?php

namespace MyparcelNL\Sdk\src\Model;


class BpostConsignment extends AbstractConsignment
{
    /**
     * @var array
     */
    protected $insurance_possibilities_local = [0, 50, 250, 500, 1000, 1500, 2000, 2500, 3000, 3500, 4000, 4500, 5000];

    /**
     * @var string
     */
    protected $local_cc = self::CC_BE;
}