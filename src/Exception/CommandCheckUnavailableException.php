<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Exception;

/**
 * A check in CommandRequestVerifier could not be finished, because fetching MyParcel's keys failed.
 *
 * Reply 503, so MyParcel sends the command again later. A 401 here would drop it for good.
 */
final class CommandCheckUnavailableException extends \Exception implements CommandResponseInterface
{
    public function getStatusCode(): int
    {
        return 503;
    }
}
