<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Account;

use MyParcelNL\Sdk\Model\Account\Account;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class AccountRefactoringTest extends TestCase
{
    /**
     * Test that both platform and proposition methods work with legacy API format.
     */
    public function testPlatformToPropositionRefactoringWithLegacyAPI(): void
    {
        // Test with current API format (platform_id)
        $accountDataLegacy = [
            'id' => 12345,
            'platform_id' => 7,
            'shops' => []
        ];

        $account = new Account($accountDataLegacy);

        // Test both legacy and new methods return same data
        self::assertEquals(7, $account->getPlatformId(), 'Legacy getPlatformId() should work');
        self::assertEquals(7, $account->getPropositionId(), 'New getPropositionId() should work');
        self::assertEquals($account->getPlatformId(), $account->getPropositionId(), 'Both methods should return same value');

        // Test toArray has proposition_id as primary key
        $array = $account->toArray();
        self::assertArrayHasKey('proposition_id', $array, 'toArray should include proposition_id as primary key');
        self::assertEquals(7, $array['proposition_id'], 'proposition_id should have correct value');
        
        // Legacy platform_id should still be included
        self::assertArrayHasKey('platform_id', $array, 'toArray should include legacy platform_id');
        self::assertEquals($array['proposition_id'], $array['platform_id'], 'platform_id should match proposition_id');
    }

    /**
     * Test that future API format with proposition_id works too.
     */
    public function testPlatformToPropositionRefactoringWithFutureAPI(): void
    {
        // Test with future API format (proposition_id)
        $accountDataFuture = [
            'id' => 54321,
            'proposition_id' => 9,
            'shops' => []
        ];

        $account = new Account($accountDataFuture);

        // Test both methods still work with future API format
        self::assertEquals(9, $account->getPlatformId(), 'Legacy getPlatformId() should work with future API');
        self::assertEquals(9, $account->getPropositionId(), 'New getPropositionId() should work with future API');
        self::assertEquals($account->getPlatformId(), $account->getPropositionId(), 'Both methods should return same value');
    }

    /**
     * Test that proposition_id takes precedence when both are provided.
     */
    public function testPropositionIdTakesPrecedence(): void
    {
        // Test with both keys provided (proposition_id should win)
        $accountDataBoth = [
            'id' => 99999,
            'platform_id' => 5,    // Old value
            'proposition_id' => 8, // New value should be used
            'shops' => []
        ];

        $account = new Account($accountDataBoth);

        // proposition_id should take precedence
        self::assertEquals(8, $account->getPlatformId(), 'Should use proposition_id when both provided');
        self::assertEquals(8, $account->getPropositionId(), 'Should use proposition_id when both provided');
    }
}
