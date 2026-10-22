<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Exception;

/**
 * An exception about a command from MyParcel that says which HTTP status to reply.
 *
 * Every exception the SDK throws while verifying or reading a command implements this, so one catch
 * block answers them all:
 *
 *     } catch (CommandResponseInterface $exception) {
 *         http_response_code($exception->getStatusCode());
 *     }
 */
interface CommandResponseInterface
{
    /**
     * Get the HTTP status to reply to MyParcel with. Never put the message in the response.
     */
    public function getStatusCode(): int;
}
