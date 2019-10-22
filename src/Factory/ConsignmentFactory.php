<?php
declare(strict_types=1);
/**
 * If you want to add improvements, please create a fork in our GitHub:
 * https://github.com/myparcelnl
 *
 * @author      Richard Perdaan <support@myparcel.nl>
 * @copyright   2010-2019 MyParcel
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
 * @link        https://github.com/myparcelnl/sdk
 * @since       File available since Release v3.0.0
 */

namespace MyParcelNL\Sdk\src\Factory;

use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\BpostConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\DPDConsignment;

class ConsignmentFactory
{
    public static function createByCarrierId(int $carrierId): AbstractConsignment
    {
        switch ($carrierId) {
            case PostNLConsignment::CARRIER_ID:
                return new PostNLConsignment();
            case BpostConsignment::CARRIER_ID:
                return new BpostConsignment();
            case DPDConsignment::CARRIER_ID:
                return new DPDConsignment();
        }

        throw new \BadMethodCallException("Carrier id $carrierId not found");
    }

    public static function createByCarrierName(string $carrierName): AbstractConsignment
    {
        switch ($carrierName) {
            case PostNLConsignment::CARRIER_NAME:
                return new PostNLConsignment();
            case BpostConsignment::CARRIER_NAME:
                return new BpostConsignment();
            case DPDConsignment::CARRIER_NAME:
                return new DPDConsignment();
        }

        throw new \BadMethodCallException("Carrier name $carrierName not found");
    }
}
