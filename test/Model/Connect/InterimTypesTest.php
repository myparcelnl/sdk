<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Connect;

use MyParcelNL\Sdk\Exception\ConnectException;
use MyParcelNL\Sdk\Model\Connect\ConnectCallbackError;
use MyParcelNL\Sdk\Model\Connect\ConnectErrorCode;
use MyParcelNL\Sdk\Model\Connect\ConnectTokenResponse;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class InterimTypesTest extends TestCase
{
    public function testCallbackErrorListsTheFourValuesTheCallbackCanSend(): void
    {
        self::assertSame(
            ['access_denied', 'session_expired', 'invalid_sales_channel', 'server_error'],
            ConnectCallbackError::getAllowableEnumValues()
        );
    }

    public function testConnectExceptionMapsExactlyTheseCallbackErrors(): void
    {
        // The two lists must not drift: the exception maps anything outside this one to server_error.
        foreach (ConnectCallbackError::getAllowableEnumValues() as $value) {
            self::assertSame($value, ConnectException::fromCallbackError($value)->getErrorCode());
        }
    }

    public function testEveryCodeConnectExceptionProducesIsADeclaredValue(): void
    {
        // The mirror of the callback test above. A seventh named constructor arriving with a
        // literal nobody declared would break translate() and nonceFrom() silently, because both
        // compare getErrorCode() against these constants.
        $declared = array_merge(
            ConnectCallbackError::getAllowableEnumValues(),
            ConnectErrorCode::getAllowableEnumValues()
        );

        $produced = [
            ConnectException::accessDenied(),
            ConnectException::sessionExpired(),
            ConnectException::invalidSalesChannel(),
            ConnectException::serverError(),
            ConnectException::connectionRevoked(),
            ConnectException::nonceRequired(),
        ];

        foreach ($produced as $exception) {
            self::assertContains($exception->getErrorCode(), $declared, $exception->getMessage());
        }
    }

    public function testErrorCodeListsTheValuesTheConnectEndpointsReturn(): void
    {
        self::assertSame(
            [
                'use_dpop_nonce',
                'invalid_grant',
                'invalid_dpop_proof',
                'invalid_sales_channel',
                'invalid_code',
                'server_error',
            ],
            ConnectErrorCode::getAllowableEnumValues()
        );
    }

    public function testTokenResponseReadsAnExchangeBody(): void
    {
        $response = ConnectTokenResponse::fromExchange([
            'accessToken' => 'a.b.c',
            'connectionId' => '6f1e6f3e-0000-4000-8000-000000000000',
            'tokenType' => 'DPoP',
            'expiresIn' => 86400,
            'refreshExpiresIn' => 31536000,
            'scope' => 'integration',
        ]);

        self::assertSame('a.b.c', $response->getAccessToken());
        self::assertSame('6f1e6f3e-0000-4000-8000-000000000000', $response->getConnectionId());
        self::assertSame(86400, $response->getExpiresIn());
        self::assertSame(31536000, $response->getRefreshExpiresIn());
        self::assertSame('integration', $response->getScope());
    }

    public function testTokenResponseAcceptsARefreshBodyWithoutAConnectionId(): void
    {
        // /connect/refresh answers with the same fields minus connectionId, which is issued once, so
        // the service keeps the stored one.
        $response = ConnectTokenResponse::fromRefresh(self::refreshBody());

        self::assertNull($response->getConnectionId());
        self::assertSame('a.b.c', $response->getAccessToken());
        self::assertSame(3600, $response->getExpiresIn());
        self::assertSame(31536000, $response->getRefreshExpiresIn());
    }

    public function testTokenResponseCarriesTheNonceHeaderWhichIsNotABodyField(): void
    {
        $response = ConnectTokenResponse::fromRefresh(self::refreshBody(), 'server-issued-nonce');

        self::assertSame('server-issued-nonce', $response->getDpopNonce());
    }

    public function testTokenResponseHasNoNonceWhenTheServerSentNoHeader(): void
    {
        self::assertNull(ConnectTokenResponse::fromRefresh(self::refreshBody())->getDpopNonce());
    }

    /**
     * @dataProvider provideIncompleteExchangeBodies
     */
    public function testTokenResponseRejectsAnExchangeBodyMissingARequiredField(array $body): void
    {
        $this->expectException(ConnectException::class);

        ConnectTokenResponse::fromExchange($body);
    }

    public function provideIncompleteExchangeBodies(): array
    {
        $complete = [
            'accessToken' => 'a.b.c',
            'connectionId' => '6f1e6f3e-0000-4000-8000-000000000000',
            'tokenType' => 'DPoP',
            'expiresIn' => 3600,
            'refreshExpiresIn' => 31536000,
            'scope' => 'integration',
        ];

        $sets = ['nothing at all' => [[]]];

        foreach (array_keys($complete) as $missing) {
            $body = $complete;
            unset($body[$missing]);

            $sets["without $missing"] = [$body];
        }

        return $this->createProviderDataset($sets);
    }

    public function testTokenResponseRejectsAnEmptyAccessToken(): void
    {
        $this->expectException(ConnectException::class);

        ConnectTokenResponse::fromRefresh(self::refreshBody(['accessToken' => '']));
    }

    public function testTokenResponseGivesTheExpiryMomentsInUtc(): void
    {
        $before   = time();
        $response = ConnectTokenResponse::fromRefresh(self::refreshBody());
        $after    = time();

        $expiresAt        = $response->getExpiresAt();
        $refreshExpiresAt = $response->getRefreshExpiresAt();

        self::assertGreaterThanOrEqual($before + 3600, $expiresAt->getTimestamp());
        self::assertLessThanOrEqual($after + 3600, $expiresAt->getTimestamp());
        self::assertGreaterThanOrEqual($before + 31536000, $refreshExpiresAt->getTimestamp());
        self::assertLessThanOrEqual($after + 31536000, $refreshExpiresAt->getTimestamp());
        self::assertSame('+00:00', $expiresAt->getTimezone()->getName());
        self::assertSame('+00:00', $refreshExpiresAt->getTimezone()->getName());
    }

    public function testTokenResponseAcceptsAnExpiryThatArrivesAsAString(): void
    {
        $response = ConnectTokenResponse::fromRefresh(self::refreshBody(['expiresIn' => '3600']));

        self::assertSame(3600, $response->getExpiresIn());
    }

    /**
     * @dataProvider provideUnusableExpiries
     */
    public function testTokenResponseRejectsAnExpiryItCannotUse($expiresIn): void
    {
        // The spec puts exclusiveMinimum 0 on expiresIn, but the generated model only checks for
        // null, so this is the one field the wrapper still guards itself.
        $this->expectException(ConnectException::class);

        ConnectTokenResponse::fromRefresh(self::refreshBody(['expiresIn' => $expiresIn]));
    }

    /**
     * @dataProvider provideUnusableExpiries
     */
    public function testTokenResponseRejectsARefreshExpiryItCannotUse($refreshExpiresIn): void
    {
        $this->expectException(ConnectException::class);

        ConnectTokenResponse::fromRefresh(self::refreshBody(['refreshExpiresIn' => $refreshExpiresIn]));
    }

    public function provideUnusableExpiries(): array
    {
        return $this->createProviderDataset([
            'zero'         => [0],
            'negative'     => [-1],
            'zero string'  => ['0'],
            'not a number' => ['soon'],
            'empty string' => [''],
        ]);
    }

    /**
     * @dataProvider provideWrongTokenTypes
     */
    public function testTokenResponseRejectsATokenTypeThatIsNotExactlyDpop(string $tokenType): void
    {
        // The generated model matches the spec's one value exactly, casing included. MyParcel
        // documents DPoP, so anything else means we are not talking to what we think we are.
        $this->expectException(ConnectException::class);

        ConnectTokenResponse::fromRefresh(self::refreshBody(['tokenType' => $tokenType]));
    }

    public function provideWrongTokenTypes(): array
    {
        return $this->createProviderDataset([
            'another scheme' => ['Bearer'],
            'lowercase'      => ['dpop'],
            'uppercase'      => ['DPOP'],
        ]);
    }

    public function testTokenResponseSaysWhichFieldTheServerGotWrong(): void
    {
        try {
            ConnectTokenResponse::fromRefresh(self::refreshBody(['tokenType' => 'Bearer']));
            self::fail('expected a ConnectException');
        } catch (ConnectException $exception) {
            self::assertStringContainsString('tokenType', $exception->getMessage());
        }
    }

    /**
     * @param array<string, mixed> $overrides
     */
    private static function refreshBody(array $overrides = []): array
    {
        return $overrides + [
            'accessToken' => 'a.b.c',
            'tokenType' => 'DPoP',
            'expiresIn' => 3600,
            'refreshExpiresIn' => 31536000,
            'scope' => 'integration',
        ];
    }
}
