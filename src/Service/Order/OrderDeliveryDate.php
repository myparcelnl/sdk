<?php


namespace Gett\MyparcelBE\Service\Order;


use DateTime;
use Gett\MyparcelBE\Constant;
use Gett\MyparcelBE\Service\CarrierConfigurationProvider;

class OrderDeliveryDate
{
    public function get(int $idCarrier): string
    {
        $now = new DateTime('now');
        $dropOffDateObj = new DateTime('today');
        $deliveryDateObj = new DateTime('tomorrow'); // Delivery is next day
        $deliveryDaysWindow = (int) (CarrierConfigurationProvider::get($idCarrier, 'deliveryDaysWindow') ?? 1);
        $dropOffDelay = (int) CarrierConfigurationProvider::get($idCarrier, 'dropOffDelay');
        $weekDayNumber = $dropOffDateObj->format('N');
        $dayName = Constant::WEEK_DAYS[$weekDayNumber];
        $cutoffTimeToday = CarrierConfigurationProvider::get($idCarrier, $dayName . 'CutoffTime');
        $this->updateDatesByDropOffDelay($dropOffDelay, $deliveryDateObj);

        $cutoffExceptions = CarrierConfigurationProvider::get($idCarrier, Constant::CUTOFF_EXCEPTIONS);
        $cutoffExceptions = @json_decode(
            $cutoffExceptions,
            true
        );
        if (!is_array($cutoffExceptions)) {
            $cutoffExceptions = [];
        }

        // Update the dropOffDateObj with the cutoff time. Ex. 17:00 hour
        $this->updateDeliveryDateByCutoffTime(
            $cutoffTimeToday,
            $deliveryDateObj,
            $dropOffDateObj,
            $now,
            $cutoffExceptions
        );

        for ($i = 1; $i <= ($deliveryDaysWindow > 1 ? $deliveryDaysWindow : 1); $i++) {
            if (!isset($cutoffExceptions[$deliveryDateObj->format('d-m-Y')]['cutoff'])
                && isset($cutoffExceptions[$deliveryDateObj->format('d-m-Y')]['nodispatch'])) {
                $deliveryDateObj->modify('+1 day');
            } else {
                // first available day found
                break;
            }
        }

        return $deliveryDateObj->format(DateTime::ATOM);
    }

    private function updateDatesByDropOffDelay($dropOffDelay, $deliveryDateObj): void
    {
        if ($dropOffDelay > 0) {
            $deliveryDateObj->modify('+' . $dropOffDelay . ' day');
        }
    }

    private function updateDeliveryDateByCutoffTime(
        $cutoffTime,
        $deliveryDateObj,
        $dropOffDateObj,
        $now,
        $cutoffExceptions
    ): void {
        if ($cutoffTime !== false) {
            if (isset($cutoffExceptions[$dropOffDateObj->format('d-m-Y')]['cutoff'])) {
                $cutoffTime = $cutoffExceptions[$dropOffDateObj->format('d-m-Y')]['cutoff'];
            }
        }
        if (empty($cutoffTime)) {
            $cutoffTime = Constant::DEFAULT_CUTOFF_TIME;
        }
        list($hour, $minute) = explode(':', $cutoffTime);
        $dropOffDateObj->setTime((int) $hour, (int) $minute, 0, 0);

        if ($now > $dropOffDateObj) {
            $deliveryDateObj->modify('+1 day');
        }
    }
}
