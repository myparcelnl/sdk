<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Exception;

/**
 * A command request that failed one of the checks in CommandRequestVerifier.
 *
 * Reply 401 with an empty body, and do not process the command. MyParcel logs it and drops the
 * command. The message says which check failed, for your own log only.
 */
final class UnauthorizedCommandException extends \Exception implements CommandResponseInterface
{
    public function getStatusCode(): int
    {
        return 401;
    }
}
