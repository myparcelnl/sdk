<?php

namespace MyParcelNL\Sdk\Model\Account;

use MyParcelNL\Sdk\Model\BaseModel;

class GeneralSettings extends BaseModel
{
    private bool $allowPrinterlessReturn;

    private bool $hasCarrierCbsContract;

    private bool $hasCarrierContract;

    private bool $hasCarrierSmallPackageContract;

    private bool $isTest;

    private bool $myReturns;

    private bool $orderMode;

    private bool $postnlMailboxInternational;

    /**
     * @param  null|array $options
     */
    public function __construct(?array $options = array())
    {
        $this->allowPrinterlessReturn         = (bool) $options['allow_printerless_return'];
        $this->hasCarrierCbsContract          = (bool) $options['has_carrier_cbs_contract'];
        $this->hasCarrierContract             = (bool) $options['has_carrier_contract'];
        $this->hasCarrierSmallPackageContract = (bool) $options['has_carrier_small_package_contract'];
        $this->isTest                         = (bool) $options['is_test'];
        $this->myReturns                      = 'active' === $options['my_returns'];
        $this->orderMode                      = (bool) $options['order_mode'];
        $this->postnlMailboxInternational     = (bool) $options['postnl_mailbox_international'];
    }

    public function allowPrinterlessReturn(): bool
    {
        return $this->allowPrinterlessReturn;
    }

    public function hasCarrierCbsContract(): bool
    {
        return $this->hasCarrierCbsContract;
    }

    public function hasCarrierContract(): bool
    {
        return $this->hasCarrierContract;
    }

    public function hasCarrierSmallPackageContract(): bool
    {
        return $this->hasCarrierSmallPackageContract;
    }

    public function isTest(): bool
    {
        return $this->isTest;
    }

    public function hasMyReturns(): bool
    {
        return $this->myReturns;
    }

    public function isOrderMode(): bool
    {
        return $this->orderMode;
    }

    public function hasPostnlMailboxInternational(): bool
    {
        return $this->postnlMailboxInternational;
    }

    public function toArray(): array
    {
        return [
            'allow_printerless_return'           => $this->allowPrinterlessReturn,
            'has_carrier_cbs_contract'           => $this->hasCarrierCbsContract,
            'has_carrier_contract'               => $this->hasCarrierContract,
            'has_carrier_small_package_contract' => $this->hasCarrierSmallPackageContract,
            'is_test'                            => $this->isTest,
            'my_returns'                         => $this->myReturns,
            'order_mode'                         => $this->orderMode,
            'postnl_mailbox_international'       => $this->postnlMailboxInternational,
        ];
    }
}