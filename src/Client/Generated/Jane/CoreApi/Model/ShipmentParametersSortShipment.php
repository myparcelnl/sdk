<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

enum ShipmentParametersSortShipment : string
{
    case Amount = 'amount';
    case Created = 'created';
    case CreatedBy = 'created_by';
    case Currency = 'currency';
    case DropOffPoint = 'drop_off_point';
    case Hidden = 'hidden';
    case Id = 'id';
    case MarkDelivered = 'mark_delivered';
    case Modified = 'modified';
    case ModifiedBy = 'modified_by';
    case OrderShipmentIdentifier = 'order_shipment_identifier';
    case ParentId = 'parent_id';
    case Recipient = 'recipient';
    case ReferenceIdentifier = 'reference_identifier';
    case Sender = 'sender';
    case ShipmentType = 'shipment_type';
    case ShopId = 'shop_id';
    case Status = 'status';
    case TransactionStatus = 'transaction_status';
}