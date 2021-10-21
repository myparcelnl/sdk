<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Account;

use MyParcelNL\Sdk\src\Model\BaseModel;
use MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier;
use MyParcelNL\Sdk\src\Model\Carrier\CarrierFactory;
use MyParcelNL\Sdk\src\Model\Consignment\DropOffPoint;
use MyParcelNL\Sdk\src\Services\Web\DropOffPointWebService;

class CarrierConfiguration extends BaseModel
{
    /**
     * @var \MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier
     */
    private $carrier;

    /**
     * @var null|string
     */
    private $default_cutoff_time;

    /**
     * @var \MyParcelNL\Sdk\src\Model\Consignment\DropOffPoint
     */
    private $default_drop_off_point;

    /**
     * @var null|string
     */
    private $default_drop_off_point_identifier;

    /**
     * @var null|string
     */
    private $monday_cutoff_time;

    /**
     * @param  array $data
     *
     * @throws \Exception
     */
    public function __construct(array $data)
    {
        $this->carrier                           = CarrierFactory::create($data['carrier']);
        $this->default_drop_off_point_identifier = $data['default_drop_off_point_identifier'] ?? null;
        $this->default_drop_off_point            = $this->createDropOffPoint($data['default_drop_off_point'] ?? null);

        $this->default_cutoff_time = $data['default_cutoff_time'] ?? null;
        $this->monday_cutoff_time  = $data['monday_cutoff_time'] ?? null;
    }

    /**
     * @param  string $apiKey
     *
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     */
    public function fetchDefaultDropOffPoint(string $apiKey): void
    {
        $dropOffPointWebService = (new DropOffPointWebService($this->carrier))->setApiKey($apiKey);

        if (! $this->getDefaultDropOffPointIdentifier()) {
            return;
        }

        $dropOffPoint = $dropOffPointWebService
            ->getDropOffPoint($this->getDefaultDropOffPointIdentifier());

        if ($dropOffPoint) {
            $this->default_drop_off_point = $dropOffPoint;
        }
    }

    /**
     * @return \MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier
     */
    public function getCarrier(): AbstractCarrier
    {
        return $this->carrier;
    }

    /**
     * @return null|string
     */
    public function getDefaultCutoffTime(): ?string
    {
        return $this->default_cutoff_time;
    }

    /**
     * @return \MyParcelNL\Sdk\src\Model\Consignment\DropOffPoint
     */
    public function getDefaultDropOffPoint(): ?DropOffPoint
    {
        return $this->default_drop_off_point ?? null;
    }

    /**
     * @return string|null
     */
    public function getDefaultDropOffPointIdentifier(): ?string
    {
        return $this->default_drop_off_point_identifier;
    }

    /**
     * @return null|string
     */
    public function getMondayCutoffTime(): ?string
    {
        return $this->monday_cutoff_time;
    }

    /**
     * @param  mixed $defaultDropOffPoint
     *
     * @return null|\MyParcelNL\Sdk\src\Model\Consignment\DropOffPoint
     */
    private function createDropOffPoint($defaultDropOffPoint): ?DropOffPoint
    {
        if (is_a($defaultDropOffPoint, DropOffPoint::class)) {
            return $defaultDropOffPoint;
        }

        return is_array($defaultDropOffPoint)
            ? new DropOffPoint($defaultDropOffPoint)
            : null;
    }
}
