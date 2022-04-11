<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Concerns;

use MyParcelNL\Sdk\src\Model\Address;

trait HasRecipient
{
    /**
     * @var \MyParcelNL\Sdk\src\Model\Address
     */
    protected $recipient;

    /**
     * @return \MyParcelNL\Sdk\src\Model\Address
     */
    public function getRecipient(): Address
    {
        return $this->recipient;
    }

    /**
     * @param  \MyParcelNL\Sdk\src\Model\Address $recipient
     *
     * @return static
     */
    public function setRecipient(Address $recipient)
    {
        $this->recipient = $recipient;
        return $this;
    }
}
