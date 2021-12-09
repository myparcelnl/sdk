<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Shipment;

use MyParcelNL\Sdk\src\Concerns\Model\Initializable\HasDeliveryOptionsAttribute;
use MyParcelNL\Sdk\src\Model\BaseModel;
use MyParcelNL\Sdk\src\Model\Recipient;

/**
 * @property string                              $apiKey
 * @property \MyParcelNL\Sdk\src\Model\Recipient $recipient
 */
class AbstractShipment extends BaseModel
{
    use HasDeliveryOptionsAttribute;

    /**
     * @var string[]
     */
    protected $attributes = [
        'apiKey'          => null,
        'deliveryOptions' => DeliveryOptionsAdapter::class,
        'recipient'       => Recipient::class,
    ];

    //    public function __construct(array $data = [])
    //    {
    //        //        $data['delivery_options'] = $data['delivery_options']?? new DeliveryOptionsV3Adapter();
    //        //        $data['carrier'] = $data['carrier']?? new DeliveryOptionsV3Adapter();
    //        $data['delivery_options'] = $data['delivery_options'] ?? new DeliveryOptionsV3Adapter();
    //        //        $data['delivery_type'] = $data['delivery_type']?? new DeliveryOptionsV3Adapter();
    //        //        $data['package_type'] = $data['package_type']?? new DeliveryOptionsV3Adapter();
    //        //        $data['pickup_location'] = $data['pickup_location']?? new DeliveryOptionsV3Adapter();
    //        $data['recipient'] = $data['recipient'] ?? new Recipient();
    //
    //        parent::__construct($data);
    //    }
}
