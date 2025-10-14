<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Concerns;

use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class HasUserAgentRefactoringTest extends TestCase
{
    /**
     * Test that both platform and proposition user agent methods work.
     */
    public function testUserAgentPlatformToPropositionRefactoring(): void
    {
        $testClass = new class {
            use HasUserAgent;
        };

        $testClass->setUserAgent('WooCommerce', '5.0.0');
        $userAgents1 = $testClass->getUserAgent();
        self::assertArrayHasKey('WooCommerce', $userAgents1, 'Legacy setUserAgent should work');
        self::assertEquals('5.0.0', $userAgents1['WooCommerce'], 'Legacy setUserAgent should set correct version');

        $testClass->resetUserAgent();
        $testClass->setUserAgentForProposition('Shopify', '2.1.0');
        $userAgents2 = $testClass->getUserAgent();
        self::assertArrayHasKey('Shopify', $userAgents2, 'New setUserAgentForProposition should work');
        self::assertEquals('2.1.0', $userAgents2['Shopify'], 'New setUserAgentForProposition should set correct version');

        // Test both methods work together (same behavior, different naming)
        $testClass->resetUserAgent();
        $testClass->setUserAgent('Platform1', '1.0.0');
        $testClass->setUserAgentForProposition('Proposition1', '2.0.0');
        $userAgents3 = $testClass->getUserAgent();
        self::assertCount(2, $userAgents3, 'Both methods should add user agents');
        self::assertArrayHasKey('Platform1', $userAgents3, 'Legacy method should add user agent');
        self::assertArrayHasKey('Proposition1', $userAgents3, 'New method should add user agent');
    }
}
