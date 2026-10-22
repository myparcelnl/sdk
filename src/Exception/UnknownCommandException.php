<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Exception;

/**
 * A command whose type this SDK version does not know.
 *
 * Reply 404: the contract tells MyParcel that the plugin does not support the type.
 */
final class UnknownCommandException extends \Exception implements CommandResponseInterface
{
    /**
     * @var string
     */
    private $type;

    public function __construct(string $type)
    {
        parent::__construct(sprintf('Unknown command type "%s"', $type));

        $this->type = $type;
    }

    /**
     * Get the type the command body named, for your log.
     */
    public function getType(): string
    {
        return $this->type;
    }

    public function getStatusCode(): int
    {
        return 404;
    }
}
