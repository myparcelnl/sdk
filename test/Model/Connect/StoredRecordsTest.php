<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Connect;

use MyParcelNL\Sdk\Exception\ConnectException;
use MyParcelNL\Sdk\Model\Connect\ConnectInstallation;
use MyParcelNL\Sdk\Model\Connect\ConnectNonces;
use MyParcelNL\Sdk\Model\Connect\ConnectToken;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class StoredRecordsTest extends TestCase
{
    public function testInstallationRoundTripsThroughAnArray(): void
    {
        $installation = new ConnectInstallation('envelope', 'a-connection-id');
        $restored     = ConnectInstallation::fromArray($installation->toArray());

        self::assertSame('envelope', $restored->getEncryptedPrivateKey());
        self::assertSame('a-connection-id', $restored->getConnectionId());
    }

    public function testInstallationStartsWithoutAConnectionId(): void
    {
        // The key pair exists from the first start(); the connection id arrives at the exchange.
        self::assertNull((new ConnectInstallation('envelope'))->getConnectionId());
    }

    public function testInstallationRoundTripsTheTokenChallenge(): void
    {
        // htm and htu arrive once, on the callback, and every refresh proof is bound to them. They
        // last as long as the installation, not as long as any one token.
        $installation = (new ConnectInstallation('envelope'))->withTokenChallenge('POST', 'https://idp.test/oauth/token');
        $restored     = ConnectInstallation::fromArray($installation->toArray());

        self::assertSame('POST', $restored->getChallengeHtm());
        self::assertSame('https://idp.test/oauth/token', $restored->getChallengeHtu());
    }

    public function testInstallationStartsWithoutAChallenge(): void
    {
        $installation = new ConnectInstallation('envelope');

        self::assertNull($installation->getChallengeHtm());
        self::assertNull($installation->getChallengeHtu());
    }

    public function testInstallationWithTokenChallengeLeavesTheOriginalAlone(): void
    {
        $installation = new ConnectInstallation('envelope');
        $installation->withTokenChallenge('POST', 'https://idp.test/oauth/token');

        self::assertNull($installation->getChallengeHtm());
    }

    public function testInstallationKeepsTheKeyWhenTheConnectionIdChanges(): void
    {
        $installation = (new ConnectInstallation('envelope'))->withConnectionId('new-id');

        self::assertSame('envelope', $installation->getEncryptedPrivateKey());
        self::assertSame('new-id', $installation->getConnectionId());
    }

    public function testInstallationWithConnectionIdLeavesTheOriginalAlone(): void
    {
        $installation = new ConnectInstallation('envelope');
        $installation->withConnectionId('new-id');

        self::assertNull($installation->getConnectionId());
    }

    public function testTokenRoundTripsThroughAnArray(): void
    {
        $token    = new ConnectToken('envelope', 1735689600, 'write:orders');
        $restored = ConnectToken::fromArray($token->toArray());

        self::assertSame('envelope', $restored->getEncryptedAccessToken());
        self::assertSame(1735689600, $restored->getExpiresAt());
        self::assertSame('write:orders', $restored->getScope());
    }

    public function testTokenHoldsNothingThatOutlivesIt(): void
    {
        // Everything a refresh needs is on the installation, so losing this record costs one refresh
        // and never a reconnect. That is what makes it safe in a cache with a lifetime.
        self::assertSame(
            ['version', 'encryptedAccessToken', 'expiresAt', 'scope'],
            array_keys((new ConnectToken('envelope', 1, 'write:orders'))->toArray())
        );
    }

    public function testTokenCanBeReplaced(): void
    {
        $token = (new ConnectToken('old', 1, 'write:orders'))->withAccessToken('new', 2, 'write:orders write:products');

        self::assertSame('new', $token->getEncryptedAccessToken());
        self::assertSame(2, $token->getExpiresAt());
        self::assertSame('write:orders write:products', $token->getScope());
    }

    public function testNoncesRoundTripThroughAnArray(): void
    {
        $nonces   = new ConnectNonces('start-nonce', 1735689600, 'dpop-nonce');
        $restored = ConnectNonces::fromArray($nonces->toArray());

        self::assertSame('start-nonce', $restored->getStartNonce());
        self::assertSame(1735689600, $restored->getStartNonceExpiresAt());
        self::assertSame('dpop-nonce', $restored->getDpopNonce());
    }

    public function testNoncesAreAllOptional(): void
    {
        $nonces = new ConnectNonces();

        self::assertNull($nonces->getStartNonce());
        self::assertNull($nonces->getStartNonceExpiresAt());
        self::assertNull($nonces->getDpopNonce());
    }

    public function testNoncesCanDropTheStartNonceAndKeepTheDpopNonce(): void
    {
        // handleCallback() burns the start nonce; the DPoP nonce stays useful for the next call.
        $nonces = (new ConnectNonces('start', 1, 'dpop'))->withoutStartNonce();

        self::assertNull($nonces->getStartNonce());
        self::assertNull($nonces->getStartNonceExpiresAt());
        self::assertSame('dpop', $nonces->getDpopNonce());
    }

    public function testNoncesCanReplaceTheDpopNonceAndKeepTheStartNonce(): void
    {
        $nonces = (new ConnectNonces('start', 1, 'old'))->withDpopNonce('new');

        self::assertSame('start', $nonces->getStartNonce());
        self::assertSame('new', $nonces->getDpopNonce());
    }

    /**
     * @dataProvider provideRecordClasses
     */
    public function testEveryRecordWritesVersionOne(string $class, array $arguments): void
    {
        $record = new $class(...$arguments);

        self::assertSame(1, $record->toArray()['version'], "$class must write version 1");
    }

    /**
     * @dataProvider provideRecordClasses
     */
    public function testEveryRecordReadsAsAbsentOnAnUnknownVersion(string $class, array $arguments): void
    {
        // A state written by a newer SDK must read as "not there" rather than be misread. The
        // merchant then reconnects, which is recoverable; a wrong read is not.
        $record = new $class(...$arguments);
        $array  = $record->toArray();

        $array['version'] = 99;

        self::assertNull($class::fromArray($array));
    }

    /**
     * @dataProvider provideRecordClasses
     */
    public function testEveryRecordReadsAsAbsentWithoutAVersion(string $class, array $arguments): void
    {
        $record = new $class(...$arguments);
        $array  = $record->toArray();

        unset($array['version']);

        self::assertNull($class::fromArray($array));
    }

    /**
     * @dataProvider provideRecordClasses
     */
    public function testEveryRecordSurvivesJsonEncoding(string $class, array $arguments): void
    {
        // A consumer stores the array as JSON, so it has to come back through that unchanged.
        $record  = new $class(...$arguments);
        $decoded = json_decode(json_encode($record->toArray()), true);

        self::assertSame($record->toArray(), $class::fromArray($decoded)->toArray());
    }

    public function provideRecordClasses(): array
    {
        return [
            'installation' => [ConnectInstallation::class, ['envelope', 'a-connection-id']],
            'token'        => [ConnectToken::class, ['envelope', 1735689600, 'write:orders']],
            'nonces'       => [ConnectNonces::class, ['start-nonce', 1735689600, 'dpop-nonce']],
        ];
    }

    public function testInstallationRejectsAnEmptyKeyEnvelope(): void
    {
        $this->expectException(ConnectException::class);

        new ConnectInstallation('');
    }

    public function testInstallationReadsAsAbsentWhenTheKeyEnvelopeIsGone(): void
    {
        self::assertNull(ConnectInstallation::fromArray(['version' => 1, 'connectionId' => 'x']));
    }

    public function testInstallationReadsAsAbsentWhenTheStoredKeyEnvelopeIsEmpty(): void
    {
        // A truncated or half-written row. fromArray() promises null when there is nothing usable,
        // and it is reached from isConnected(), which a settings screen asks and must not throw.
        self::assertNull(ConnectInstallation::fromArray(['version' => 1, 'encryptedPrivateKey' => '']));
    }

    /**
     * @dataProvider provideUnusableKeyEnvelopes
     * @param mixed $envelope
     */
    public function testInstallationReadsAsAbsentWhenTheStoredKeyEnvelopeIsNotAString($envelope): void
    {
        // Casting these gave an installation carrying '0', '1' or 'Array', and the failure then
        // surfaced later as a decryption error blaming the encryption key.
        self::assertNull(ConnectInstallation::fromArray([
            'version'             => 1,
            'encryptedPrivateKey' => $envelope,
        ]));
    }

    public function provideUnusableKeyEnvelopes(): array
    {
        return $this->createProviderDataset([
            'an integer'  => [0],
            'a boolean'   => [true],
            'an array'    => [['envelope']],
            'a float'     => [1.5],
        ]);
    }

    public function testVersionIsReadAsAnIntegerEvenWhenStorageStringifiesIt(): void
    {
        // A per-column table or a cache layer can hand back '1' instead of 1. A strict compare would
        // read every record as absent, which for the installation means a new key pair.
        $array            = (new ConnectInstallation('envelope'))->toArray();
        $array['version'] = '1';

        self::assertNotNull(ConnectInstallation::fromArray($array));
    }

    /**
     * @dataProvider provideUnusableTokenRows
     * @param array<string, mixed> $override
     */
    public function testTokenReadsAsAbsentWhenAStoredFieldIsUnusable(array $override): void
    {
        // A truncated or half-written row. Casting instead gave a token of 'Array', a scope of
        // 'Array', or an expiry of 0, which reads as expired at the epoch.
        $row = array_merge([
            'version'              => 1,
            'encryptedAccessToken' => 'envelope',
            'expiresAt'            => 1800000000,
            'scope'                => 'integration',
        ], $override);

        self::assertNull(ConnectToken::fromArray($row));
    }

    public function provideUnusableTokenRows(): array
    {
        return $this->createProviderDataset([
            'an empty token'        => [['encryptedAccessToken' => '']],
            'a token that is an array' => [['encryptedAccessToken' => ['envelope']]],
            'a token that is a number' => [['encryptedAccessToken' => 0]],
            'a scope that is an array' => [['scope' => ['integration']]],
            'an expiry that is words'  => [['expiresAt' => 'soon']],
            'an expiry that is an array' => [['expiresAt' => [1]]],
        ]);
    }

    public function testTokenKeepsAnExpiryThatStorageStringified(): void
    {
        // Same reason hasKnownVersion() accepts '1': a per-column table hands back a string.
        $token = ConnectToken::fromArray([
            'version'              => 1,
            'encryptedAccessToken' => 'envelope',
            'expiresAt'            => '1800000000',
            'scope'                => 'integration',
        ]);

        self::assertNotNull($token);
        self::assertSame(1800000000, $token->getExpiresAt());
    }

    public function testTokenReadsAsAbsentWhenAFieldIsGone(): void
    {
        $complete = (new ConnectToken('envelope', 1, 'write:orders'))->toArray();

        foreach (['encryptedAccessToken', 'expiresAt', 'scope'] as $field) {
            $partial = $complete;
            unset($partial[$field]);

            self::assertNull(ConnectToken::fromArray($partial), "a token without $field must read as absent");
        }
    }
}
