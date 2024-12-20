<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Concerns;

use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class HasUserAgentTest extends TestCase
{
    /**
     * @throws \ReflectionException
     */
    public function testSetUserAgents(): void
    {
        /**
         * @var HasUserAgent $trait
         */
        $trait = $this->getObjectForTrait(HasUserAgent::class);

        $trait->setUserAgents(['PlatformName' => '3.2.1']);
        self::assertStringMatchesFormat('PlatformName/3.2.1 MyParcelNL-SDK/%d.%d.%d%S', $trait->getUserAgentHeader());

        $trait->setUserAgent('OtherPlatformName', '4.5.6');
        self::assertStringMatchesFormat(
            'PlatformName/3.2.1 OtherPlatformName/4.5.6 MyParcelNL-SDK/%d.%d.%d%S',
            $trait->getUserAgentHeader()
        );

        $trait->resetUserAgent();
        $trait->setUserAgent('OtherPlatformName', '4.5.6');
        self::assertStringMatchesFormat(
            'OtherPlatformName/4.5.6 MyParcelNL-SDK/%d.%d.%d%S',
            $trait->getUserAgentHeader()
        );
    }
}
