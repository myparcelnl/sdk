<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Fulfilment;

use Exception;
use MyParcelNL\Sdk\src\Exception\ValidationException;
use MyParcelNL\Sdk\src\Validator\Order\OrderNoteValidator;
use MyParcelNL\Sdk\src\Validator\ValidatorFactory;

class OrderNote
{
    /**
     * @var string Either 'customer' or 'webshop'.
     */
    private $author;

    /**
     * @var string The text with a max length of 2500 characters.
     */
    private $note;

    /**
     * @var string
     */
    private $orderUuid;

    /**
     * @var string The validator class that should be used for this model.
     */
    private $validatorClass = OrderNoteValidator::class;

    public function __construct(?array $data = []) {
        $this->note      = $data['note'] ?? null;
        $this->author    = $data['author'] ?? null;
        $this->orderUuid = $data['orderUuid'] ?? null;
    }

    /**
     * @return null|string
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * @return null|string
     */
    public function getNote(): ?string
    {
        return $this->note;
    }

    /**
     * @return null|string
     */
    public function getOrderUuid(): ?string
    {
        return $this->orderUuid;
    }

    /**
     * @param  null|string $author
     *
     * @return \MyParcelNL\Sdk\src\Model\Fulfilment\OrderNote
     */
    public function setAuthor(?string $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @param  null|string $note
     *
     * @return \MyParcelNL\Sdk\src\Model\Fulfilment\OrderNote
     */
    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    /**
     * @param  null|string $orderUuid
     *
     * @return \MyParcelNL\Sdk\src\Model\Fulfilment\OrderNote
     */
    public function setOrderUuid(?string $orderUuid): self
    {
        $this->orderUuid = $orderUuid;

        return $this;
    }

    /**
     * @return object
     */
    public function toApiObject(): \stdClass
    {
        return (object)[
            'note'   => $this->getNote(),
            'author' => $this->getAuthor(),
        ];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'orderUuid' => $this->getOrderUuid(),
            'note'      => $this->getNote(),
            'author'    => $this->getAuthor(),
        ];
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function validate(): bool
    {
        $validator = ValidatorFactory::create($this->validatorClass);

        try {
            $validator
                ->validateAll($this)
                ->report();
        } catch (ValidationException $e) {
            throw new Exception($e->getHumanMessage(), $e->getCode(), $e);
        }

        return true;
    }
}
