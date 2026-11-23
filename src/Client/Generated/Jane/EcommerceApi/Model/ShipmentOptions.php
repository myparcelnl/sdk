<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalPropertiesInterface;
class ShipmentOptions implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * Provides enhanced insurance coverage beyond the standard amount.
     *
     * @var ShipmentOptionsAdditionalInsurance
     */
    public ShipmentOptionsAdditionalInsurance $additionalInsurance;
    /**
     * Indicates that the parcel will be delivered to a designated postal service point rather than the recipient's address, requiring the recipient to collect it from that location.
     *
     * @var ShipmentOptionsDeliverAtPostalPoint
     */
    public ShipmentOptionsDeliverAtPostalPoint $deliverAtPostalPoint;
    /**
     * Designates the shipment as containing perishable, non-frozen food items, ensuring appropriate handling procedures are applied to maintain freshness and prevent spoilage during transit.
     *
     * @var ShipmentOptionsFreshFood
     */
    public ShipmentOptionsFreshFood $freshFood;
    /**
     * Specifies that the shipment contains items that must remain frozen, necessitating strict, continuous temperature-controlled environments throughout the transit process to prevent thawing.
     *
     * @var ShipmentOptionsFrozen
     */
    public ShipmentOptionsFrozen $frozen;
    /**
     * Omits the sender's information from the shipping label, maintaining sender anonymity in the delivery process.
     *
     * @var ShipmentOptionsHideSender
     */
    public ShipmentOptionsHideSender $hideSender;
    /**
     * Provides standard insurance coverage up to the specified monetary amount.
     *
     * @var ShipmentOptionsInsurance
     */
    public ShipmentOptionsInsurance $insurance;
    /**
     * Disables all tracking and tracing capabilities for the shipment.
     *
     * @var ShipmentOptionsNoTracking
     */
    public ShipmentOptionsNoTracking $noTracking;
    /**
     * Designates the shipment as exceeding standard size limitations, requiring special handling procedures and potentially incurring additional fees.
     *
     * @var ShipmentOptionsOversizedPackage
     */
    public ShipmentOptionsOversizedPackage $oversizedPackage;
    /**
     * Provides the recipient with a digital identifier that can be presented at a drop-off location to print the return label on-site. This option is only applicable for inbound shipments.
     *
     * @var ShipmentOptionsPrintReturnLabelAtDropOff
     */
    public ShipmentOptionsPrintReturnLabelAtDropOff $printReturnLabelAtDropOff;
    /**
     * Designates the shipment for expedited processing and transport through the delivery network, often resulting in a faster transit time compared to standard services.
     *
     * @var ShipmentOptionsPriorityDelivery
     */
    public ShipmentOptionsPriorityDelivery $priorityDelivery;
    /**
     * Restricts delivery exclusively to the named recipient, requiring verification of recipient identity and prohibiting delivery to anyone else at the same address.
     *
     * @var ShipmentOptionsRecipientOnlyDelivery
     */
    public ShipmentOptionsRecipientOnlyDelivery $recipientOnlyDelivery;
    /**
     * Requires the recipient to provide valid identification to verify they meet the minimum age requirement before the package can be released, typically used for age-restricted items.
     *
     * @var ShipmentOptionsRequiresAgeVerification
     */
    public ShipmentOptionsRequiresAgeVerification $requiresAgeVerification;
    /**
     * Mandates that the carrier collect the full order amount in cash from the recipient upon delivery, ensuring payment is secured before the goods are handed over.
     *
     * @var ShipmentOptionsRequiresCashOnDelivery
     */
    public ShipmentOptionsRequiresCashOnDelivery $requiresCashOnDelivery;
    /**
     * Requires the recipient to provide a specific verification code at the time of delivery, adding an extra layer of security and confirmation for sensitive shipments.
     *
     * @var ShipmentOptionsRequiresReceiptCode
     */
    public ShipmentOptionsRequiresReceiptCode $requiresReceiptCode;
    /**
     * Mandates that the recipient or authorized representative must provide a signature upon delivery, creating proof of receipt and transfer of responsibility.
     *
     * @var ShipmentOptionsRequiresSignature
     */
    public ShipmentOptionsRequiresSignature $requiresSignature;
    /**
     * Specifies that the recipient must pay the designated contribution amount toward the cost of returning the item. This option is only applicable for inbound shipments.
     *
     * @var ShipmentOptionsReturnContributionFee
     */
    public ShipmentOptionsReturnContributionFee $returnContributionFee;
    /**
     * Instructs the carrier to return the package to the sender immediately after the first unsuccessful delivery attempt, bypassing the standard redelivery procedures or holding period.
     *
     * @var ShipmentOptionsReturnOnFirstFailedDelivery
     */
    public ShipmentOptionsReturnOnFirstFailedDelivery $returnOnFirstFailedDelivery;
    /**
     * Expedited delivery within the same calendar day that the shipping label is created.
     *
     * @var ShipmentOptionsSameDayDelivery
     */
    public ShipmentOptionsSameDayDelivery $sameDayDelivery;
    /**
     * Enables delivery on Saturday, which is typically outside standard delivery days, ensuring the package arrives before the start of the following work week.
     *
     * @var ShipmentOptionsSaturdayDelivery
     */
    public ShipmentOptionsSaturdayDelivery $saturdayDelivery;
    /**
     * Arranges for the carrier to pick up the package from the sender's location, eliminating the need for the sender to drop off the package.
     *
     * @var ShipmentOptionsScheduledCollection
     */
    public ShipmentOptionsScheduledCollection $scheduledCollection;
    /**
     * Defines a custom text to be printed on the shipping label.
     *
     * @var ShipmentOptionsCustomLabelText
     */
    public ShipmentOptionsCustomLabelText $customLabelText;
    public function definedProperties(): array
    {
        return ['additionalInsurance' => 'additionalInsurance', 'deliverAtPostalPoint' => 'deliverAtPostalPoint', 'freshFood' => 'freshFood', 'frozen' => 'frozen', 'hideSender' => 'hideSender', 'insurance' => 'insurance', 'noTracking' => 'noTracking', 'oversizedPackage' => 'oversizedPackage', 'printReturnLabelAtDropOff' => 'printReturnLabelAtDropOff', 'priorityDelivery' => 'priorityDelivery', 'recipientOnlyDelivery' => 'recipientOnlyDelivery', 'requiresAgeVerification' => 'requiresAgeVerification', 'requiresCashOnDelivery' => 'requiresCashOnDelivery', 'requiresReceiptCode' => 'requiresReceiptCode', 'requiresSignature' => 'requiresSignature', 'returnContributionFee' => 'returnContributionFee', 'returnOnFirstFailedDelivery' => 'returnOnFirstFailedDelivery', 'sameDayDelivery' => 'sameDayDelivery', 'saturdayDelivery' => 'saturdayDelivery', 'scheduledCollection' => 'scheduledCollection', 'customLabelText' => 'customLabelText'];
    }
}