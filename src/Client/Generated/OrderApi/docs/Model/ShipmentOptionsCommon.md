# # ShipmentOptionsCommon

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**additional_insurance** | **object** | Provides enhanced insurance coverage beyond the standard amount. | [optional]
**deliver_at_postal_point** | **object** | Indicates that the parcel will be delivered to a designated postal service point rather than the recipient&#39;s address, requiring the recipient to collect it from that location. | [optional]
**hide_sender** | **object** | Omits the sender&#39;s information from the shipping label, maintaining sender anonymity in the delivery process. | [optional]
**insurance** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\ShipmentOptionsCommonInsurance**](ShipmentOptionsCommonInsurance.md) |  | [optional]
**no_tracking** | **object** | Disables all tracking and tracing capabilities for the shipment. | [optional]
**oversized_package** | **object** | Designates the shipment as exceeding standard size limitations, requiring special handling procedures and potentially incurring additional fees. | [optional]
**print_return_label_at_drop_off** | **object** | Provides the recipient with a digital identifier that can be presented at a drop-off location to print the return label on-site. This option is only applicable for inbound shipments. | [optional]
**priority_delivery** | **object** | Designates the shipment for expedited processing and transport through the delivery network, often resulting in a faster transit time compared to standard services. | [optional]
**recipient_only_delivery** | **object** | Restricts delivery exclusively to the named recipient, requiring verification of recipient identity and prohibiting delivery to anyone else at the same address. | [optional]
**requires_age_verification** | **object** | Requires the recipient to provide valid identification to verify they meet the minimum age requirement before the package can be released, typically used for age-restricted items. | [optional]
**requires_cash_on_delivery** | **object** | Mandates that the carrier collect the full order amount in cash from the recipient upon delivery, ensuring payment is secured before the goods are handed over. | [optional]
**requires_receipt_code** | **object** | Requires the recipient to provide a specific verification code at the time of delivery, adding an extra layer of security and confirmation for sensitive shipments. | [optional]
**requires_signature** | **object** | Mandates that the recipient or authorized representative must provide a signature upon delivery, creating proof of receipt and transfer of responsibility. | [optional]
**return_contribution_fee** | [**\MyParcelNL\Sdk\Client\Generated\OrderApi\Model\ShipmentOptionsCommonReturnContributionFee**](ShipmentOptionsCommonReturnContributionFee.md) |  | [optional]
**return_on_first_failed_delivery** | **object** | Instructs the carrier to return the package to the sender immediately after the first unsuccessful delivery attempt, bypassing the standard redelivery procedures or holding period. | [optional]
**same_day_delivery** | **object** | Expedited delivery within the same calendar day that the shipping label is created. | [optional]
**saturday_delivery** | **object** | Enables delivery on Saturday, which is typically outside standard delivery days, ensuring the package arrives before the start of the following work week. | [optional]
**scheduled_collection** | **object** | Arranges for the carrier to pick up the package from the sender&#39;s location, eliminating the need for the sender to drop off the package. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
