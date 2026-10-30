<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model;

enum Feature : string
{
    case OrderManagement = 'ORDER_MANAGEMENT';
    case NotificationCenter = 'NOTIFICATION_CENTER';
    case LegacyOrderManagement = 'LEGACY_ORDER_MANAGEMENT';
    case AutoOrderImport = 'AUTO_ORDER_IMPORT';
    case BolDotComVvbShipments = 'BOL_DOT_COM_VVB_SHIPMENTS';
    case CustomContracts = 'CUSTOM_CONTRACTS';
    case DirectPrinting = 'DIRECT_PRINTING';
    case DisableLabelHeadline = 'DISABLE_LABEL_HEADLINE';
    case ExtendedShippingRules = 'EXTENDED_SHIPPING_RULES';
    case NestedShipmentLabelOnPackingSlip = 'NESTED_SHIPMENT_LABEL_ON_PACKING_SLIP';
    case OrderInvoice = 'ORDER_INVOICE';
    case OrderNotes = 'ORDER_NOTES';
    case OrderPicklist = 'ORDER_PICKLIST';
    case ReturnLinkOnPackingSlip = 'RETURN_LINK_ON_PACKING_SLIP';
    case SaveOrderSortingSetting = 'SAVE_ORDER_SORTING_SETTING';
    case ScanMode = 'SCAN_MODE';
    case ShipmentBulkCreateFromAddressBook = 'SHIPMENT_BULK_CREATE_FROM_ADDRESS_BOOK';
    case ShipmentBulkEdits = 'SHIPMENT_BULK_EDITS';
    case ShipmentDiscount = 'SHIPMENT_DISCOUNT';
    case ShopLogoOnPackingSlip = 'SHOP_LOGO_ON_PACKING_SLIP';
    case Tier1Analytics = 'TIER1_ANALYTICS';
    case Tier2Analytics = 'TIER2_ANALYTICS';
    case Tier3Analytics = 'TIER3_ANALYTICS';
    case Tier4Analytics = 'TIER4_ANALYTICS';
    case TrackTraceEmailSecondaryRecipients = 'TRACK_TRACE_EMAIL_SECONDARY_RECIPIENTS';
    case UnlimitedShipmentsPerOrderLine = 'UNLIMITED_SHIPMENTS_PER_ORDER_LINE';
    case UnlimitedShippingRules = 'UNLIMITED_SHIPPING_RULES';
}