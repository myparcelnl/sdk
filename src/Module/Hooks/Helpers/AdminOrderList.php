<?php

namespace Gett\MyparcelBE\Module\Hooks\Helpers;

use Context;
use Module;

class AdminOrderList extends AbstractAdminOrder
{
    /**
     * @var Module
     */
    private $module;

    /**
     * @var int
     */
    private $idOrder;

    /**
     * @var Context
     */
    private $context;

    public function __construct(Module $module, int $idOrder = null, Context $context = null)
    {
        $this->module = $module;
        $this->idOrder = $idOrder;
        $this->context = $context ?? Context::getContext();
    }
}
