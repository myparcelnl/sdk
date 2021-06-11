<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model;

class RequestBody
{
    /**
     * @var array
     */
    private $body;

    /**
     * @var string
     */
    private $namespace;

    public function __construct(string $namespace, array $body)
    {
        $this->namespace = $namespace;
        $this->body      = $body;
    }

    /**
     * @return array[][]
     */
    public function toArray(): array
    {
        return [
            'data' => [
                $this->namespace => $this->body,
            ],
        ];
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }
}
