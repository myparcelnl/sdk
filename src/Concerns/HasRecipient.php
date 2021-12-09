<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Concerns;

use MyParcelNL\Sdk\src\Model\Recipient;

trait HasRecipient
{
    /**
     * @var \MyParcelNL\Sdk\src\Model\Recipient
     */
    protected $recipient;

    /**
     * @return \MyParcelNL\Sdk\src\Model\Recipient
     */
    public function getRecipient(): Recipient
    {
        return $this->recipient;
    }

    /**
     * @param  \MyParcelNL\Sdk\src\Model\Recipient $recipient
     *
     * @return static
     */
    public function setRecipient(Recipient $recipient)
    {
        $this->recipient = $recipient;
        return $this;
    }
}
