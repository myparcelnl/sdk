<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class OrderToCreateShippingOptions implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * Provides enhanced insurance coverage beyond the standard amount.
     *
     * @var mixed
     */
    public $additionalInsurance;
    /**
     * Indicates that the parcel will be delivered to a designated postal service point rather than the recipient's address, requiring the recipient to collect it from that location.
     *
     * @var mixed
     */
    public $deliverAtPostalPoint;
    /**
     * Designates the shipment as containing perishable, non-frozen food items, ensuring appropriate handling procedures are applied to maintain freshness and prevent spoilage during transit.
     *
     * @var mixed
     */
    public $freshFood;
    /**
     * Specifies that the shipment contains items that must remain frozen, necessitating strict, continuous temperature-controlled environments throughout the transit process to prevent thawing.
     *
     * @var mixed
     */
    public $frozen;
    /**
     * Omits the sender's information from the shipping label, maintaining sender anonymity in the delivery process.
     *
     * @var mixed
     */
    public $hideSender;
    /**
     * Provides standard insurance coverage up to the specified monetary amount.
     *
     * @var OrderToCreateShippingOptionsInsurance
     */
    public OrderToCreateShippingOptionsInsurance $insurance;
    /**
     * Disables all tracking and tracing capabilities for the shipment.
     *
     * @var mixed
     */
    public $noTracking;
    /**
     * Designates the shipment as exceeding standard size limitations, requiring special handling procedures and potentially incurring additional fees.
     *
     * @var mixed
     */
    public $oversizedPackage;
    /**
     * Provides the recipient with a digital identifier that can be presented at a drop-off location to print the return label on-site. This option is only applicable for inbound shipments.
     *
     * @var mixed
     */
    public $printReturnLabelAtDropOff;
    /**
     * Designates the shipment for expedited processing and transport through the delivery network, often resulting in a faster transit time compared to standard services.
     *
     * @var mixed
     */
    public $priorityDelivery;
    /**
     * Restricts delivery exclusively to the named recipient, requiring verification of recipient identity and prohibiting delivery to anyone else at the same address.
     *
     * @var mixed
     */
    public $recipientOnlyDelivery;
    /**
     * Requires the recipient to provide valid identification to verify they meet the minimum age requirement before the package can be released, typically used for age-restricted items.
     *
     * @var mixed
     */
    public $requiresAgeVerification;
    /**
     * Mandates that the carrier collect the full order amount in cash from the recipient upon delivery, ensuring payment is secured before the goods are handed over.
     *
     * @var mixed
     */
    public $requiresCashOnDelivery;
    /**
     * Requires the recipient to provide a specific verification code at the time of delivery, adding an extra layer of security and confirmation for sensitive shipments.
     *
     * @var mixed
     */
    public $requiresReceiptCode;
    /**
     * Mandates that the recipient or authorized representative must provide a signature upon delivery, creating proof of receipt and transfer of responsibility.
     *
     * @var mixed
     */
    public $requiresSignature;
    /**
     * Specifies that the recipient must pay the designated contribution amount toward the cost of returning the item. This option is only applicable for inbound shipments.
     *
     * @var OrderToCreateShippingOptionsReturnContributionFee
     */
    public OrderToCreateShippingOptionsReturnContributionFee $returnContributionFee;
    /**
     * Instructs the carrier to return the package to the sender immediately after the first unsuccessful delivery attempt, bypassing the standard redelivery procedures or holding period.
     *
     * @var mixed
     */
    public $returnOnFirstFailedDelivery;
    /**
     * Expedited delivery within the same calendar day that the shipping label is created.
     *
     * @var mixed
     */
    public $sameDayDelivery;
    /**
     * Enables delivery on Saturday, which is typically outside standard delivery days, ensuring the package arrives before the start of the following work week.
     *
     * @var mixed
     */
    public $saturdayDelivery;
    /**
     * Arranges for the carrier to pick up the package from the sender's location, eliminating the need for the sender to drop off the package.
     *
     * @var mixed
     */
    public $scheduledCollection;
    /**
     * Defines a custom text to be printed on the shipping label.
     *
     * @var OrderToCreateShippingOptionsCustomLabelText
     */
    public OrderToCreateShippingOptionsCustomLabelText $customLabelText;
    public function definedProperties(): array
    {
        return ['additionalInsurance' => 'additionalInsurance', 'deliverAtPostalPoint' => 'deliverAtPostalPoint', 'freshFood' => 'freshFood', 'frozen' => 'frozen', 'hideSender' => 'hideSender', 'insurance' => 'insurance', 'noTracking' => 'noTracking', 'oversizedPackage' => 'oversizedPackage', 'printReturnLabelAtDropOff' => 'printReturnLabelAtDropOff', 'priorityDelivery' => 'priorityDelivery', 'recipientOnlyDelivery' => 'recipientOnlyDelivery', 'requiresAgeVerification' => 'requiresAgeVerification', 'requiresCashOnDelivery' => 'requiresCashOnDelivery', 'requiresReceiptCode' => 'requiresReceiptCode', 'requiresSignature' => 'requiresSignature', 'returnContributionFee' => 'returnContributionFee', 'returnOnFirstFailedDelivery' => 'returnOnFirstFailedDelivery', 'sameDayDelivery' => 'sameDayDelivery', 'saturdayDelivery' => 'saturdayDelivery', 'scheduledCollection' => 'scheduledCollection', 'customLabelText' => 'customLabelText'];
    }
}