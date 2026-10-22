<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Exception;

/**
 * A command body that CommandReader cannot read: no JSON object, or a field missing or of the wrong type.
 *
 * Reply 400, so MyParcel drops the command instead of sending it again.
 */
final class InvalidCommandException extends \Exception
{
}
