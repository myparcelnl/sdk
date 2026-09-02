<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Connect;

use MyParcelNL\Sdk\Crypto\AesGcmCipher;
use MyParcelNL\Sdk\Exception\ConnectException;
use MyParcelNL\Sdk\Model\Connect\ConnectState;
use MyParcelNL\Sdk\Services\Connect\ConnectStateRepository;
use MyParcelNL\Sdk\Test\Bootstrap\InMemoryConnectStorage;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class ConnectStateRepositoryTest extends TestCase
{
    private const KEY = 'an encryption key';

    private const PEM = "-----BEGIN PRIVATE KEY-----\nMIGHfake\n-----END PRIVATE KEY-----\n";

    private const TOKEN = 'header.payload.signature';

    /**
     * @var \MyParcelNL\Sdk\Test\Bootstrap\InMemoryConnectStorage
     */
    private $storage;

    /**
     * @var \MyParcelNL\Sdk\Services\Connect\ConnectStateRepository
     */
    private $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->storage    = new InMemoryConnectStorage();
        $this->repository = new ConnectStateRepository($this->storage, new AesGcmCipher(self::KEY));
    }

    public function testAReadIsRememberedSoSigningOneCallDoesNotReadTwice(): void
    {
        // Signing an API call reads the state twice, and an order import signs hundreds.
        $this->repository->saveInstallation($this->fullState());
        $before = $this->reads();

        $this->repository->load();
        $this->repository->load();
        $this->repository->load();

        self::assertSame(3, $this->reads() - $before, 'three records, read once between them');
    }

    public function testAFreshReadSkipsWhatWasRemembered(): void
    {
        $this->repository->saveInstallation($this->fullState());
        $this->repository->load();
        $before = $this->reads();

        $this->repository->load(true);

        self::assertSame(3, $this->reads() - $before);
    }

    public function testNothingStoredIsRememberedToo(): void
    {
        // Otherwise isConnected() on an unconnected shop reads storage every time it is asked.
        $before = $this->reads();

        self::assertNull($this->repository->load());
        self::assertNull($this->repository->load());

        self::assertSame(1, $this->reads() - $before, 'it stops at the missing installation');
    }

    /**
     * @dataProvider provideWriters
     */
    public function testEveryWriteIsVisibleToTheNextRead(string $writer): void
    {
        // The remembered read has to be forgotten by every writer. A ninth one that forgets to
        // would make its own write invisible until the next request.
        $this->repository->saveInstallation($this->fullState());
        $this->repository->saveToken($this->fullState());
        $this->repository->saveStartNonce($this->fullState());
        $this->repository->load();

        $this->callWriter($writer);

        // Only the reads the load() below performs. Some writers read storage themselves, so
        // counting from before the write would pass whether or not the memo was forgotten.
        $afterWrite = $this->reads();
        $this->repository->load();

        self::assertGreaterThan($afterWrite, $this->reads(), "$writer did not forget");
    }

    public function provideWriters(): array
    {
        return $this->createProviderDataset([
            'saveInstallation' => ['saveInstallation'],
            'saveToken'        => ['saveToken'],
            'saveStartNonce'   => ['saveStartNonce'],
            'saveDpopNonce'    => ['saveDpopNonce'],
            'burnStartNonce'   => ['burnStartNonce'],
            'clearToken'       => ['clearToken'],
            'clearNonces'      => ['clearNonces'],
            'clear'            => ['clear'],
        ]);
    }

    /**
     * Every load the storage has served, across the three records.
     */
    private function reads(): int
    {
        return $this->storage->callCount('loadInstallation')
            + $this->storage->callCount('loadToken')
            + $this->storage->callCount('loadNonces');
    }

    private function callWriter(string $writer): void
    {
        switch ($writer) {
            case 'saveInstallation':
            case 'saveToken':
            case 'saveStartNonce':
                $this->repository->{$writer}($this->fullState());
                return;
            case 'saveDpopNonce':
                $this->repository->saveDpopNonce('a-nonce');
                return;
            default:
                $this->repository->{$writer}();
        }
    }

    public function testLoadReturnsNullWhenNothingIsStored(): void
    {
        self::assertNull($this->repository->load());
    }

    public function testLoadReturnsNullWithoutAnInstallationEvenWhenATokenExists(): void
    {
        // Without a key pair there is nothing: the token cannot be used and cannot be refreshed.
        $this->repository->saveInstallation($this->fullState());
        $this->repository->saveToken($this->fullState());
        $this->storage->seed('installation', null);

        self::assertNull($this->repository->load());
    }

    public function testRoundTripsEveryFieldThroughStorage(): void
    {
        $state = $this->fullState();

        $this->repository->saveInstallation($state);
        $this->repository->saveToken($state);
        $this->repository->saveStartNonce($state);
        $this->repository->saveDpopNonce('dpop-nonce');

        $loaded = $this->repository->load();

        self::assertSame(self::PEM, $loaded->getPrivateKeyPem());
        self::assertSame(self::TOKEN, $loaded->getAccessToken());
        self::assertSame(1735689600, $loaded->getAccessTokenExpiresAt());
        self::assertSame('a-connection-id', $loaded->getConnectionId());
        self::assertSame('write:orders', $loaded->getScope());
        self::assertSame(['htm' => 'POST', 'htu' => 'https://idp.test/oauth/token'], $loaded->getTokenChallenge());
        self::assertSame('start-nonce', $loaded->getStartNonce());
        self::assertSame(1735690000, $loaded->getStartNonceExpiresAt());
        self::assertSame('dpop-nonce', $loaded->getDpopNonce());
    }

    public function testNoPlaintextSecretEverReachesStorage(): void
    {
        $state = $this->fullState();

        $this->repository->saveInstallation($state);
        $this->repository->saveToken($state);

        $stored = $this->storage->everythingAsJson();

        self::assertStringNotContainsString('MIGHfake', $stored, 'the private key must be encrypted');
        self::assertStringNotContainsString(self::TOKEN, $stored, 'the access token must be encrypted');
    }

    public function testTheValuesThatAreNotSecretStayReadable(): void
    {
        // A consumer can look at their own row and see what it is, minus the two secrets.
        $this->repository->saveInstallation($this->fullState());
        $this->repository->saveDpopNonce('dpop-nonce');

        $stored = $this->storage->everythingAsJson();

        self::assertStringContainsString('a-connection-id', $stored);
        self::assertStringContainsString('dpop-nonce', $stored);
        self::assertStringContainsString('https://idp.test/oauth/token', $stored, 'the challenge is not a secret');
    }

    public function testSavingTheTokenTouchesNoOtherRecord(): void
    {
        $this->repository->saveToken($this->fullState());

        self::assertSame(1, $this->storage->callCount('saveToken'));
        self::assertSame(0, $this->storage->callCount('saveInstallation'));
        self::assertSame(0, $this->storage->callCount('saveNonces'));
    }

    public function testLosingTheTokenRecordStillLeavesARefreshPossible(): void
    {
        // A consumer may put the token in a cache with a lifetime. When it expires the shop must be
        // able to refresh, which needs the challenge, so the challenge cannot live with the token.
        $this->repository->saveInstallation($this->fullState());
        $this->repository->saveToken($this->fullState());
        $this->storage->seed('token', null);

        $loaded = $this->repository->load();

        self::assertNull($loaded->getAccessToken());
        self::assertSame(
            ['htm' => 'POST', 'htu' => 'https://idp.test/oauth/token'],
            $loaded->getTokenChallenge(),
            'the challenge outlives any one token'
        );
    }

    public function testSavingTheDpopNonceKeepsAnInFlightStartNonce(): void
    {
        // A background refresh runs while the merchant is at MyParcel. Writing the record whole from
        // the refresh's own state would drop the start nonce and the callback would be refused.
        $this->repository->saveStartNonce($this->fullState());

        $this->repository->saveDpopNonce('a-newer-nonce');

        $nonces = $this->storage->row('nonces');

        self::assertSame('start-nonce', $nonces['startNonce']);
        self::assertSame('a-newer-nonce', $nonces['dpopNonce']);
    }

    public function testBurningTheStartNonceKeepsTheDpopNonce(): void
    {
        // The exchange right after the callback needs the nonce. Deleting the record here would make
        // every exchange pay a use_dpop_nonce round trip.
        $this->repository->saveStartNonce($this->fullState());
        $this->repository->saveDpopNonce('a-nonce');

        $this->repository->burnStartNonce();

        $nonces = $this->storage->row('nonces');

        self::assertNull($nonces['startNonce']);
        self::assertSame('a-nonce', $nonces['dpopNonce']);
    }

    public function testSavingAStateWithoutATokenLeavesAGoodTokenAlone(): void
    {
        $this->repository->saveInstallation($this->fullState());
        $this->repository->saveToken($this->fullState());

        $this->repository->saveToken(ConnectState::withKey(self::PEM));

        self::assertNotNull($this->storage->row('token'), 'only clearToken() may delete it');
    }

    public function testClearTokenLeavesTheInstallation(): void
    {
        $this->repository->saveInstallation($this->fullState());
        $this->repository->saveToken($this->fullState());
        $this->repository->saveStartNonce($this->fullState());

        $this->repository->clearToken();

        self::assertNull($this->storage->row('token'));
        self::assertNotNull($this->storage->row('installation'), 'the key pair must survive a disconnect');
        self::assertNotNull($this->storage->row('nonces'));
    }

    public function testClearNoncesLeavesTheInstallationAndTheToken(): void
    {
        $this->repository->saveInstallation($this->fullState());
        $this->repository->saveToken($this->fullState());
        $this->repository->saveStartNonce($this->fullState());

        $this->repository->clearNonces();

        self::assertNull($this->storage->row('nonces'));
        self::assertNotNull($this->storage->row('installation'));
        self::assertNotNull($this->storage->row('token'));
    }

    public function testClearRemovesEverything(): void
    {
        $this->repository->saveInstallation($this->fullState());
        $this->repository->saveToken($this->fullState());

        $this->repository->clear();

        self::assertNull($this->storage->row('installation'));
        self::assertNull($this->storage->row('token'));
        self::assertNull($this->repository->load());
    }

    public function testAWrongEncryptionKeyIsReportedAsSuch(): void
    {
        $this->repository->saveInstallation($this->fullState());

        $other = new ConnectStateRepository($this->storage, new AesGcmCipher('another key'));

        $this->expectException(ConnectException::class);

        $other->load();
    }

    public function testARowFromANewerSdkReadsAsAbsent(): void
    {
        $this->repository->saveInstallation($this->fullState());

        $row            = $this->storage->row('installation');
        $row['version'] = 99;
        $this->storage->seed('installation', $row);

        self::assertNull($this->repository->load());
    }

    public function testATokenRowFromANewerSdkLeavesTheInstallationUsable(): void
    {
        $this->repository->saveInstallation($this->fullState());
        $this->repository->saveToken($this->fullState());

        $row            = $this->storage->row('token');
        $row['version'] = 99;
        $this->storage->seed('token', $row);

        $loaded = $this->repository->load();

        self::assertNotNull($loaded, 'the key pair is still readable');
        self::assertNull($loaded->getAccessToken(), 'the token reads as absent');
        self::assertNotNull($loaded->getTokenChallenge(), 'so the shop can still refresh');
    }

    public function testAStateWithOnlyAKeyRoundTrips(): void
    {
        // What start() writes before any callback: a key pair and nothing else.
        $this->repository->saveInstallation(ConnectState::withKey(self::PEM));

        $loaded = $this->repository->load();

        self::assertSame(self::PEM, $loaded->getPrivateKeyPem());
        self::assertNull($loaded->getAccessToken());
        self::assertNull($loaded->getConnectionId());
        self::assertNull($loaded->getTokenChallenge());
    }

    private function fullState(): ConnectState
    {
        return ConnectState::withKey(self::PEM)
            ->withConnectionId('a-connection-id')
            ->withTokenChallenge('POST', 'https://idp.test/oauth/token')
            ->withToken(self::TOKEN, 1735689600, 'write:orders')
            ->withStartNonce('start-nonce', 1735690000)
            ->withDpopNonce('dpop-nonce');
    }
}
