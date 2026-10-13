<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Connect;

use MyParcelNL\Sdk\Exception\ConnectException;
use MyParcelNL\Sdk\Model\Connect\ConnectPlatform;
use MyParcelNL\Sdk\Services\Connect\ConnectService;

/**
 * The token lifecycle: handing one out, refreshing it, and giving it up.
 */
class ConnectServiceTokenTest extends ConnectServiceTestCase
{
    public function testGetAccessTokenHandsBackAFreshToken(): void
    {
        $this->seedConnectedShop(time() + 3600);

        self::assertSame('a.b.c', $this->service()->getAccessToken());
    }

    public function testGetAccessTokenDoesNotCallMyParcelForAFreshToken(): void
    {
        $this->seedConnectedShop(time() + 3600);

        self::assertSame('a.b.c', $this->service()->getAccessToken());
        self::assertSame(0, $this->callCount(), 'a usable token needs no call');
    }

    public function testGetAccessTokenRefreshesInsideTheLeeway(): void
    {
        // 30 seconds by default: a call already on its way must not expire halfway through.
        $this->seedConnectedShop(time() + 29);

        $token = $this->service($this->refreshResponse())->getAccessToken();

        self::assertSame(1, $this->callCount());
        self::assertSame(self::HOST . '/connect/refresh', (string) $this->request(0)->getUri());
        self::assertNotSame('a.b.c', $token, 'a new token came back');
    }

    public function testGetAccessTokenRefreshesAnExpiredToken(): void
    {
        $this->seedConnectedShop(time() - 1);

        $this->service($this->refreshResponse())->getAccessToken();

        self::assertSame(1, $this->callCount());
    }

    public function testGetAccessTokenThrowsForAShopThatNeverConnected(): void
    {
        $this->expectException(ConnectException::class);

        $this->service()->getAccessToken();
    }

    public function testGetAccessTokenThrowsWhenThereIsAKeyButNoToken(): void
    {
        // start() was called and the merchant never came back.
        $this->seedInstallation();

        $this->expectException(ConnectException::class);

        $this->service()->getAccessToken();
    }

    public function testRefreshUsesTheStoredChallengeAndKeepsTheConnectionId(): void
    {
        $this->seedConnectedShop();

        $state = $this->service($this->refreshResponse())->refresh();

        $claims = $this->proofClaims(0);

        self::assertSame('POST', $claims['htm']);
        self::assertSame(self::HTU, $claims['htu']);
        self::assertArrayNotHasKey('ath', $claims, 'a refresh proof carries no access token hash');
        self::assertSame(self::CONNECTION_ID, $state->getConnectionId(), 'the refresh answer omits it');
    }

    public function testRefreshStoresTheNewTokenAndItsExpiry(): void
    {
        $this->seedConnectedShop();

        $this->service($this->refreshResponse())->refresh();

        $reloaded = $this->service();

        self::assertExpiresIn(3600, $this->tokenExpiryFromStorage());
        self::assertNotSame('a.b.c', $reloaded->getAccessToken());
    }

    public function testRefreshLeavesTheInstallationAlone(): void
    {
        $this->seedConnectedShop();
        $before = $this->storage->row('installation');

        $this->service()->refresh();

        self::assertSame($before, $this->storage->row('installation'));
    }

    public function testANonceDemandCostsOneExtraCallAndThenSucceeds(): void
    {
        $this->seedConnectedShop();

        $this->service(self::nonceDemand('use-this'), $this->refreshResponse())->refresh();

        self::assertSame(2, $this->callCount());
        self::assertArrayNotHasKey('nonce', $this->proofClaims(0), 'the first proof had none to send');
        self::assertSame('use-this', $this->proofClaims(1)['nonce'], 'the retry carries it');
    }

    public function testTheNonceIsKeptSoTheNextCallDoesNotPayAgain(): void
    {
        $this->seedConnectedShop();

        $this->service(self::nonceDemand('use-this'), $this->refreshResponse())->refresh();

        self::assertSame('use-this', $this->storage->row('nonces')['dpopNonce']);
    }

    public function testASecondNonceDemandIsAFaultRatherThanAnotherRetry(): void
    {
        $this->seedConnectedShop();

        try {
            $this->service(self::nonceDemand('a'), self::nonceDemand('a'))->refresh();
            self::fail('expected a ConnectException');
        } catch (ConnectException $exception) {
            self::assertSame(2, $this->callCount(), 'exactly one retry');
            self::assertSame(
                ConnectException::nonceRequired()->getMessage(),
                $exception->getMessage(),
                'a repeated demand is reported as a fault, not relayed as another HTTP failure'
            );
        }
    }

    public function testANonceDemandWithoutANonceIsNotRetried(): void
    {
        // Nothing to retry with, so retrying would send the same proof again.
        $this->seedConnectedShop();

        try {
            $this->service(self::errorResponse(400, 'use_dpop_nonce'))->refresh();
            self::fail('expected a ConnectException');
        } catch (ConnectException $exception) {
            self::assertSame(1, $this->callCount());
        }
    }

    public function testARevokedConnectionClearsTheTokenAndKeepsTheKey(): void
    {
        // invalid_grant on a refresh means MyParcel no longer has this connection.
        $this->seedConnectedShop();

        try {
            $this->service(self::errorResponse(400, 'invalid_grant'))->refresh();
            self::fail('expected a ConnectException');
        } catch (ConnectException $exception) {
            self::assertSame('invalid_grant', $exception->getErrorCode());
        }

        self::assertNull($this->storage->row('token'), 'the token is gone');
        self::assertNotNull($this->storage->row('installation'), 'the key pair is not');
    }

    public function testARefusedProofIsNotTreatedAsARevokedConnection(): void
    {
        // invalid_dpop_proof is our own fault, and the connection is still fine.
        $this->seedConnectedShop();

        try {
            $this->service(self::errorResponse(401, 'invalid_dpop_proof'))->refresh();
            self::fail('expected a ConnectException');
        } catch (ConnectException $exception) {
            self::assertSame('invalid_dpop_proof', $exception->getErrorCode());
        }

        self::assertNotNull($this->storage->row('token'), 'the token survives');
    }

    public function testIsConnectedIsTrueForAConnectedShop(): void
    {
        $this->seedConnectedShop();

        self::assertTrue($this->service()->isConnected());
    }

    public function testIsConnectedIsFalseForAShopWithOnlyAKey(): void
    {
        $this->seedInstallation();

        self::assertFalse($this->service()->isConnected());
    }

    public function testIsConnectedIsFalseWhenTheEncryptionKeyNoLongerFits(): void
    {
        // A rotated or lost key must not throw out of a question a settings screen asks.
        $this->seedConnectedShop();

        $other = new ConnectService(
            (new \MyParcelNL\Sdk\Model\Connect\ConnectConfig(ConnectPlatform::SHOPIFY, 'a different key')),
            $this->storage,
            $this->httpAnswering()
        );

        self::assertFalse($other->isConnected());
    }

    public function testDisconnectKeepsTheKeyAndTheConnectionId(): void
    {
        $this->seedConnectedShop();

        $this->service()->disconnect();

        self::assertNull($this->storage->row('token'));
        self::assertNull($this->storage->row('nonces'));

        $installation = $this->storage->row('installation');

        self::assertNotNull($installation);
        self::assertSame(self::CONNECTION_ID, $installation['connectionId'], 'inbound requests still carry it');
    }

    public function testUninstallRemovesEverything(): void
    {
        $this->seedConnectedShop();

        $this->service()->uninstall();

        self::assertNull($this->storage->row('installation'));
        self::assertNull($this->storage->row('token'));
    }

    public function testCreateResourceProofBindsToTheRequestAndTheToken(): void
    {
        $this->seedConnectedShop();

        $proof  = $this->service()->createResourceProof('POST', self::HOST . '/orders', 'the-token');
        $claims = json_decode(\MyParcelNL\Sdk\Support\Str::base64UrlDecode(explode('.', $proof)[1]), true);

        self::assertSame('POST', $claims['htm']);
        self::assertSame(self::HOST . '/orders', $claims['htu']);
        self::assertSame(
            \MyParcelNL\Sdk\Support\Str::base64UrlEncode(hash('sha256', 'the-token', true)),
            $claims['ath'],
            'ath ties the proof to that one token'
        );
    }

    public function testGetConfigHandsBackTheConfig(): void
    {
        self::assertSame(self::HOST, $this->service()->getConfig()->getHost());
    }

    public function testIsSupportedIsTrueWhereTheTestsRun(): void
    {
        // The three conditions are checked individually; this only proves CI can run Connect.
        self::assertTrue(ConnectService::isSupported());
    }

    /**
     * @dataProvider provideExchangeFailures
     */
    public function testAnExchangeFailureBecomesItsOwnCaseAndKeepsTheWireDetail(
        string $wireCode,
        string $expectedMessage
    ): void {
        // invalid_grant means two different things. Here the code is gone and the merchant restarts;
        // on a refresh it means MyParcel dropped the connection. Inverting the two would leave the
        // refresh tests green, so the exchange side needs its own.
        $this->seedInstallation();

        $service = $this->service(self::errorResponse(400, $wireCode));
        $service->start('My Shop', 'https://shop.example.test');

        try {
            $service->handleCallback([
                'nonce' => $this->storage->row('nonces')['startNonce'],
                'code'  => 'a-code',
                'htm'   => 'POST',
                'htu'   => self::HTU,
            ]);
            self::fail('expected a ConnectException');
        } catch (ConnectException $exception) {
            self::assertSame($expectedMessage, $exception->getMessage());
            self::assertSame($wireCode, $exception->getErrorCode(), 'the wire value survives for a log line');
            self::assertSame(400, $exception->getStatusCode(), 'so does the status');
        }
    }

    public function provideExchangeFailures(): array
    {
        return [
            'a spent code'         => ['invalid_grant', ConnectException::sessionExpired()->getMessage()],
            'no sales channel'     => ['invalid_sales_channel', ConnectException::invalidSalesChannel()->getMessage()],
            'an unknown code'      => ['invalid_code', ConnectException::invalidCallback('MyParcel did not recognise the code')->getMessage()],
            'a broken service'     => ['server_error', ConnectException::serverError()->getMessage()],
        ];
    }

    public function testAnExchangeFailureLeavesAnyExistingTokenAlone(): void
    {
        // Only a refresh may clear the token. A failed exchange says nothing about the old one.
        $this->seedConnectedShop();

        $service = $this->service(self::errorResponse(400, 'invalid_grant'));
        $service->start('My Shop', 'https://shop.example.test');

        try {
            $service->handleCallback([
                'nonce' => $this->storage->row('nonces')['startNonce'],
                'code'  => 'a-code',
                'htm'   => 'POST',
                'htu'   => self::HTU,
            ]);
        } catch (ConnectException $exception) {
            // expected
        }

        self::assertNotNull($this->storage->row('token'));
    }

    public function testRefreshAfterADisconnectDoesNotBringTheShopBack(): void
    {
        // A scheduled refresh must not undo what the merchant just did.
        $this->seedConnectedShop();
        $this->service()->disconnect();

        $this->expectException(ConnectException::class);

        $this->service()->refresh();
    }

    public function testATokenRecordLostFromACacheCanStillBeRefreshed(): void
    {
        // The other side of the same coin: losing the token row is not a disconnect.
        $this->seedConnectedShop();
        $this->storage->seed('token', null);

        self::assertNotNull($this->service()->refresh()->getAccessToken());
    }

    private function tokenExpiryFromStorage(): int
    {
        return $this->storage->row('token')['expiresAt'];
    }
}
