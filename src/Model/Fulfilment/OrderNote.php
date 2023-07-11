<?php

namespace MyParcelNL\Sdk\src\Model\Fulfilment;

use Exception;
use MyParcelNL\Sdk\src\Exception\ValidationException;
use MyParcelNL\Sdk\src\Validator\Order\OrderNoteValidator;
use MyParcelNL\Sdk\src\Validator\ValidatorFactory;

class OrderNote
{
    /**
     * @var string either 'customer' or 'webshop'
     */
    private $author;

    /**
     * @var string
     */
    private $note;

    /**
     * @var string
     */
    private $uuid;

    /**
     * @var string The validator class that should be used for this model.
     */
    private $validatorClass = OrderNoteValidator::class;

    public function __construct(?array $data = []) {
        if (! is_array($data)) {
            return;
        }

        $this->note   = $data['note'] ?? null;
        $this->author = $data['author'] ?? null;
        $this->uuid   = $data['uuid'] ?? null;
    }

    /**
     * @return null|string
     */
    public function getAuthor(): ?string
    {
        return $this->author;
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
     * @return null|string
     */
    public function getNote(): ?string
    {
        return $this->note;
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
     * @return null|string
     */
    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    /**
     * @param  null|string $uuid
     *
     * @return \MyParcelNL\Sdk\src\Model\Fulfilment\OrderNote
     */
    public function setUuid(?string $uuid): self
    {
        $this->uuid = $uuid;

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
        return [
            'note'   => $this->getNote(),
            'author' => $this->getAuthor(),
        ];
    }
}
