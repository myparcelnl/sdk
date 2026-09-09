<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Connect;

use MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\ConnectRefreshPost200Response;
use MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\ConnectTokenPost200Response;
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
        $response = ConnectTokenResponse::fromExchange(new ConnectTokenPost200Response([
            'access_token'  => 'a.b.c',
            'connection_id' => '6f1e6f3e-0000-4000-8000-000000000000',
            'token_type'    => 'DPoP',
            'expires_in'    => 86400,
            'scope'         => 'integration',
        ]));

        self::assertSame('a.b.c', $response->getAccessToken());
        self::assertSame('6f1e6f3e-0000-4000-8000-000000000000', $response->getConnectionId());
        self::assertSame(86400, $response->getExpiresIn());
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

        ConnectTokenResponse::fromExchange(new ConnectTokenPost200Response($body));
    }

    public function provideIncompleteExchangeBodies(): array
    {
        $complete = [
            'access_token'  => 'a.b.c',
            'connection_id' => '6f1e6f3e-0000-4000-8000-000000000000',
            'token_type'    => 'DPoP',
            'expires_in'    => 3600,
            'scope'         => 'integration',
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

        ConnectTokenResponse::fromRefresh(self::refreshBody(['access_token' => '']));
    }

    public function testTokenResponseAcceptsAnExpiryThatArrivesAsAString(): void
    {
        $response = ConnectTokenResponse::fromRefresh(self::refreshBody(['expires_in' => '3600']));

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

        ConnectTokenResponse::fromRefresh(self::refreshBody(['expires_in' => $expiresIn]));
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

        ConnectTokenResponse::fromRefresh(self::refreshBody(['token_type' => $tokenType]));
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
            ConnectTokenResponse::fromRefresh(self::refreshBody(['token_type' => 'Bearer']));
            self::fail('expected a ConnectException');
        } catch (ConnectException $exception) {
            self::assertStringContainsString('token_type', $exception->getMessage());
        }
    }

    /**
     * @param array<string, mixed> $overrides
     */
    private static function refreshBody(array $overrides = []): ConnectRefreshPost200Response
    {
        return new ConnectRefreshPost200Response($overrides + [
            'access_token' => 'a.b.c',
            'token_type'   => 'DPoP',
            'expires_in'   => 3600,
            'scope'        => 'integration',
        ]);
    }
}
