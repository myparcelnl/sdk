<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Fulfilment;

use DateTime;
use MyParcelNL\Sdk\src\Adapter\DeliveryOptions\AbstractDeliveryOptionsAdapter;
use MyParcelNL\Sdk\src\Model\BaseModel;
use MyParcelNL\Sdk\src\Model\Recipient;
use MyParcelNL\Sdk\src\Support\Collection;

class AbstractOrder extends BaseModel
{
    public const DATE_FORMAT_FULL = 'Y-m-d H:i:s';

    /**
     * @var \MyParcelNL\Sdk\src\Adapter\DeliveryOptions\DeliveryOptionsFromOrderAdapter
     */
    protected $delivery_options;

    /**
     * @var string|null
     */
    protected $external_identifier;

    /**
     * @var string|null
     */
    protected $fulfilment_partner_identifier;

    /**
     * @var \MyParcelNL\Sdk\src\Model\Recipient
     */
    protected $invoice_address;

    /**
     * @var string|null
     */
    protected $language;

    /**
     * @var DateTime|null
     */
    protected $order_date;

    /**
     * @var \MyParcelNL\Sdk\src\Support\Collection|\MyParcelNL\Sdk\src\Model\Fulfilment\OrderLine[]
     */
    protected $order_lines;

    /**
     * @var \MyParcelNL\Sdk\src\Model\Recipient
     */
    protected $recipient;

    /**
     * @var string|null
     */
    protected $status;

    /**
     * @var string|null
     */
    protected $type;

    /**
     * @var string|null
     */
    protected $uuid;

    /**
     * @return \MyParcelNL\Sdk\src\Adapter\DeliveryOptions\AbstractDeliveryOptionsAdapter
     */
    public function getDeliveryOptions(): AbstractDeliveryOptionsAdapter
    {
        return $this->delivery_options;
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
     * @return string|null
     */
    public function getOrderDateString(): ?string
    {
        $orderDate = $this->getOrderDate();

        if ($orderDate) {
            return $orderDate->format(self::DATE_FORMAT_FULL);
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
     * @return \MyParcelNL\Sdk\src\Model\Recipient
     */
    public function getRecipient(): Recipient
    {
        return $this->recipient;
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
     * @return array
     */
    public function toArray(): array
    {
        return [
            'external_identifier'           => $this->getExternalIdentifier(),
            'fulfilment_partner_identifier' => $this->getFulfilmentPartnerIdentifier(),
            'order_date'                    => $this->getOrderDateString(),
            'recipient'                     => $this->getRecipient()->toArray(),
            'invoice_address'               => $this->getInvoiceAddress()->toArray(),
            'order_lines'                   => $this->getOrderLines()->toArray(),
            'delivery_options'              => $this->getDeliveryOptions()->toArray(),
        ];
    }
}
