<?php

namespace Gett\MyparcelBE\Module\Hooks\Helpers;

use Configuration;
use Gett\MyparcelBE\Carrier\PackageTypeCalculator;
use Gett\MyparcelBE\Constant;
use Media;

abstract class AbstractAdminOrder
{
    public function getLabelDefaultConfiguration(): array
    {
        return Configuration::getMultiple([
            Constant::LABEL_SIZE_CONFIGURATION_NAME,
            Constant::LABEL_POSITION_CONFIGURATION_NAME,
        ]);
    }

    public function allowSetSignature(int $idCarrierReference): bool
    {
        $allowSetSignature = true;
        switch ($idCarrierReference) {
            case (int) Configuration::get(Constant::DPD_CONFIGURATION_NAME):
                $allowSetSignature = false;
                break;
            case (int) Configuration::get(Constant::BPOST_CONFIGURATION_NAME):
            case (int) Configuration::get(Constant::POSTNL_CONFIGURATION_NAME):
            default:
                break;
        }

        return $allowSetSignature;
    }

    public function allowSetOnlyRecipient(int $idCarrierReference): bool
    {
        $allowSetOnlyRecipient = true;
        switch ($idCarrierReference) {
            case (int) Configuration::get(Constant::DPD_CONFIGURATION_NAME):
            case (int) Configuration::get(Constant::BPOST_CONFIGURATION_NAME):
                $allowSetOnlyRecipient = false;
                break;
            case (int) Configuration::get(Constant::POSTNL_CONFIGURATION_NAME):
            default:
                break;
        }

        return $allowSetOnlyRecipient;
    }

    public function isMyParcelCarrier(int $idCarrier): bool
    {
        return (new PackageTypeCalculator())->isMyParcelCarrier($idCarrier);
    }
}
