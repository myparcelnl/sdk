<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Fulfilment;

use DateTime;
use Exception;
use MyParcelNL\Sdk\src\Adapter\DeliveryOptions\AbstractDeliveryOptionsAdapter;
use MyParcelNL\Sdk\src\Exception\ValidationException;
use MyParcelNL\Sdk\src\Model\BaseModel;
use MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier;
use MyParcelNL\Sdk\src\Model\Carrier\CarrierFactory;
use MyParcelNL\Sdk\src\Model\Consignment\DropOffPoint;
use MyParcelNL\Sdk\src\Model\CustomsDeclaration;
use MyParcelNL\Sdk\src\Model\PickupLocation;
use MyParcelNL\Sdk\src\Model\Recipient;
use MyParcelNL\Sdk\src\Support\Collection;
use MyParcelNL\Sdk\src\Validator\Order\OrderValidator;
use MyParcelNL\Sdk\src\Validator\ValidatorFactory;

class AbstractOrder extends BaseModel
{
    public const DATE_FORMAT_FULL = 'Y-m-d H:i:s';
    public const DATE_FORMAT_DATE = 'Y-m-d';

    /**
     * The selected delivery options for this order.
     *
     * @var \MyParcelNL\Sdk\src\Adapter\DeliveryOptions\DeliveryOptionsFromOrderAdapter
     */
    protected $delivery_options;

    /**
     * @var \MyParcelNL\Sdk\src\Model\Consignment\DropOffPoint
     */
    protected $dropOffPoint;

    /**
     * The unique identifier of the order in your webshop.
     *
     * @var string|null
     */
    protected $external_identifier;

    /**
     * @var string|null
     */
    protected $fulfilment_partner_identifier;

    /**
     * Invoice/billing address of the customer.
     *
     * @var \MyParcelNL\Sdk\src\Model\Recipient
     */
    protected $invoice_address;

    /**
     * @var string|null
     */
    protected $language;

    /**
     * The date when the order was placed. Can be a DateTime object or a string in Y-M-d H:i:s format.
     *
     * @var DateTime|null
     */
    protected $order_date;

    /**
     * @var \MyParcelNL\Sdk\src\Support\Collection|\MyParcelNL\Sdk\src\Model\Fulfilment\OrderLine[]
     */
    protected $order_lines;

    /**
     * @var array
     */
    protected $order_shipments;

    /**
     * Shipping address of the customer.
     *
     * @var \MyParcelNL\Sdk\src\Model\Recipient
     */
    protected $recipient;

    /**
     * Data from the pickup location.
     *
     * @var \MyParcelNL\Sdk\src\Model\PickupLocation|null
     */
    protected $pickupLocation;

    /**
     * @var string|null
     */
    protected $status;

    /**
     * @var string|null
     */
    protected $type;

    /**
     * Unique identifier from our API. Set after saving the order.
     *
     * @var string|null
     */
    protected $uuid;

    /**
     * @var
     */
    protected $validatorClass = OrderValidator::class;

    /**
     * @var int|null
     */
    private $weight;

    protected $customs_declaration;

    /**
     * @return \MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier
     * @throws \Exception
     */
    public function getCarrier(): AbstractCarrier
    {
        return CarrierFactory::createFromName($this->delivery_options->getCarrier());
    }

    /**
     * @return null|\MyParcelNL\Sdk\src\Model\CustomsDeclaration
     */
    public function getCustomsDeclaration(): ?CustomsDeclaration
    {
        return $this->customs_declaration;
    }

    /**
     * @return \MyParcelNL\Sdk\src\Adapter\DeliveryOptions\AbstractDeliveryOptionsAdapter
     */
    public function getDeliveryOptions(): AbstractDeliveryOptionsAdapter
    {
        return $this->delivery_options;
    }

    /**
     * @return null|\MyParcelNL\Sdk\src\Model\Consignment\DropOffPoint
     */
    public function getDropOffPoint(): ?DropOffPoint
    {
        return $this->dropOffPoint;
    }

    /**
     * @return string|null
     */
    public function getExternalIdentifier(): ?string
    {
        return $this->external_identifier;
    }

    /**
     * @return string|null
     */
    public function getFulfilmentPartnerIdentifier(): ?string
    {
        return $this->fulfilment_partner_identifier;
    }

    /**
     * @return \MyParcelNL\Sdk\src\Model\Recipient
     */
    public function getInvoiceAddress(): Recipient
    {
        return $this->invoice_address;
    }

    /**
     * @return string|null
     */
    public function getLanguage(): ?string
    {
        return $this->language;
    }

    /**
     * @return \DateTime|null
     */
    public function getOrderDate(): ?DateTime
    {
        return $this->order_date;
    }

    /**
     * Transform the order date to a string.
     *
     * @param string $format default DATE_FORMAT_FULL, must be a valid datetime format string
     *
     * @return string|null
     */
    public function getOrderDateString(string $format = self::DATE_FORMAT_FULL): ?string
    {
        $orderDate = $this->getOrderDate();

        if ($orderDate) {
            return $orderDate->format($format);
        }

        return null;
    }

    /**
     * @return \MyParcelNL\Sdk\src\Support\Collection|\MyParcelNL\Sdk\src\Model\Fulfilment\OrderLine[]
     */
    public function getOrderLines(): Collection
    {
        return $this->order_lines;
    }

    /**
     * @return array
     */
    public function getOrderShipments(): array
    {
        return $this->order_shipments;
    }

    /**
     * @return \MyParcelNL\Sdk\src\Model\Recipient
     */
    public function getRecipient(): Recipient
    {
        return $this->recipient;
    }

    /**
     * @return \MyParcelNL\Sdk\src\Model\PickupLocation|null
     */
    public function getPickupLocation(): ?PickupLocation
    {
        return $this->pickupLocation;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    /**
     * @return null|int
     */
    public function getWeight(): ?int
    {
        return $this->weight;
    }

    /**
     * @param  CustomsDeclaration $customs_declaration
     *
     * @return $this
     */
    public function setCustomsDeclaration(CustomsDeclaration $customs_declaration): self
    {
        $this->customs_declaration = $customs_declaration;
        return $this;
    }

    /**
     * @param  \MyParcelNL\Sdk\src\Adapter\DeliveryOptions\AbstractDeliveryOptionsAdapter $deliveryOptions
     *
     * @return self
     */
    public function setDeliveryOptions(AbstractDeliveryOptionsAdapter $deliveryOptions): self
    {
        $this->delivery_options = $deliveryOptions;
        return $this;
    }

    /**
     * @param  null|\MyParcelNL\Sdk\src\Model\Consignment\DropOffPoint $dropOffPoint
     *
     * @return $this
     * @throws \Exception
     */
    public function setDropOffPoint(?DropOffPoint $dropOffPoint): self
    {
        $this->dropOffPoint = $dropOffPoint;
        return $this;
    }

    /**
     * @param  string $externalIdentifier
     *
     * @return self
     */
    public function setExternalIdentifier(string $externalIdentifier): self
    {
        $this->external_identifier = $externalIdentifier;
        return $this;
    }

    /**
     * @param  string $fulfilmentPartnerIdentifier
     *
     * @return self
     */
    public function setFulfilmentPartnerIdentifier(string $fulfilmentPartnerIdentifier): self
    {
        $this->fulfilment_partner_identifier = $fulfilmentPartnerIdentifier;
        return $this;
    }

    /**
     * @param  \MyParcelNL\Sdk\src\Model\Recipient $invoiceAddress
     *
     * @return self
     */
    public function setInvoiceAddress(Recipient $invoiceAddress): self
    {
        $this->invoice_address = $invoiceAddress;
        return $this;
    }

    /**
     * @param  string $language
     *
     * @return self
     */
    public function setLanguage(string $language): self
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @param  string|\DateTime $orderDate
     *
     * @return self
     * @throws \Exception
     */
    public function setOrderDate($orderDate): self
    {
        if (is_string($orderDate)) {
            $orderDate = new DateTime($orderDate);
        }

        $this->order_date = $orderDate;
        return $this;
    }

    /**
     * @param  \MyParcelNL\Sdk\src\Model\Fulfilment\OrderLine[]|\MyParcelNL\Sdk\src\Support\Collection $orderLines
     *
     * @return self
     */
    public function setOrderLines(Collection $orderLines): self
    {
        $this->order_lines = $orderLines;
        return $this;
    }

    /**
     * @param  \MyParcelNL\Sdk\src\Model\Recipient $recipient
     *
     * @return self
     */
    public function setRecipient(Recipient $recipient): self
    {
        $this->recipient = $recipient;
        return $this;
    }

    /**
     * @param  \MyParcelNL\Sdk\src\Model\PickupLocation|null  $pickupLocation
     *
     * @return self
     */
    public function setPickupLocation(?PickupLocation $pickupLocation): self
    {
        $this->pickupLocation = $pickupLocation;
        return $this;
    }

    /**
     * @param  string $status
     *
     * @return self
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param  string|null $type
     *
     * @return self
     */
    public function setType(?string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param  int $weight
     *
     * @return \MyParcelNL\Sdk\src\Model\Fulfilment\AbstractOrder
     */
    public function setWeight(int $weight): self
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function validate(): bool
    {
        $validator = ValidatorFactory::create($this->validatorClass);

        if ($validator) {
            try {
                $validator
                    ->validateAll($this)
                    ->report();
            } catch (ValidationException $e) {
                throw new Exception($e->getHumanMessage(), $e->getCode(), $e);
            }
        }

        return true;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_merge(
            [
                'external_identifier'           => $this->getExternalIdentifier(),
                'fulfilment_partner_identifier' => $this->getFulfilmentPartnerIdentifier(),
                'order_date'                    => $this->getOrderDateString(),
                'recipient'                     => $this->getRecipient()->toArray(),
                'invoice_address'               => $this->getInvoiceAddress()->toArray(),
                'order_lines'                   => $this->getOrderLines()->toArray(),
                'delivery_options'              => $this->getDeliveryOptions()->toArray(),
            ],
            (null === $this->customs_declaration)
                ? []
                : ['customs_declaration' => $this->getCustomsDeclaration()->toArray()]
        );
    }
}
