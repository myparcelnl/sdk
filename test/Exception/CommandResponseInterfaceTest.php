<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Exception;

use MyParcelNL\Sdk\Exception\CommandCheckUnavailableException;
use MyParcelNL\Sdk\Exception\CommandResponseInterface;
use MyParcelNL\Sdk\Exception\InvalidCommandException;
use MyParcelNL\Sdk\Exception\UnauthorizedCommandException;
use MyParcelNL\Sdk\Exception\UnknownCommandException;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

final class CommandResponseInterfaceTest extends TestCase
{
    /**
     * @dataProvider provideExceptions
     */
    public function testEachCommandExceptionNamesItsStatus(CommandResponseInterface $exception, int $status): void
    {
        self::assertSame($status, $exception->getStatusCode());
    }

    public function provideExceptions(): array
    {
        return [
            'unauthorized' => [new UnauthorizedCommandException('x'), 401],
            'unavailable'  => [new CommandCheckUnavailableException('x'), 503],
            'invalid'      => [new InvalidCommandException('x'), 400],
            'unknown'      => [new UnknownCommandException('ORDER_X'), 404],
        ];
    }
}
