<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Connect;

use MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\ConnectStartConfig;
use MyParcelNL\Sdk\Exception\ConnectException;
use MyParcelNL\Sdk\Model\Connect\ConnectConfig;
use MyParcelNL\Sdk\Model\Connect\ConnectPlatform;
use MyParcelNL\Sdk\Services\Connect\ConnectService;
use MyParcelNL\Sdk\Support\Str;

/**
 * start() and handleCallback(): everything that happens while the merchant is in a browser.
 */
class ConnectServiceStartTest extends ConnectServiceTestCase
{
    public function testStartGeneratesAKeyPairWhenTheShopHasNone(): void
    {
        $url = $this->service()->start('My Shop', 'https://shop.example.test');

        self::assertNotNull($this->storage->row('installation'), 'the key pair is stored');
        self::assertSame(43, strlen($this->query($url)['jkt']), 'the jkt is a 32 byte thumbprint');
    }

    public function testStartReusesAStoredKeyPair(): void
    {
        // A reconnect with a new key would register the shop as a new shop at MyParcel.
        $this->seedInstallation();

        $url = $this->service()->start('My Shop', 'https://shop.example.test');

        self::assertSame(self::THUMBPRINT, $this->query($url)['jkt']);
    }

    public function testStartSendsTheFourParametersConnectStartWants(): void
    {
        $query = $this->query($this->service()->start('My Shop', 'https://shop.example.test'));

        self::assertSame(['jkt', 'config', 'nonce', 'scope'], array_keys($query));
        self::assertSame('integration write:orders write:products', $query['scope']);
    }

    public function testStartPointsAtTheConfiguredHost(): void
    {
        $url = $this->service()->start('My Shop', 'https://shop.example.test');

        self::assertStringStartsWith(self::HOST . '/connect/start?', $url);
    }

    public function testStartPutsTheShopDetailsInOneBase64UrlConfigParameter(): void
    {
        $query = $this->query($this->service()->start('My Shop', 'https://shop.example.test'));

        self::assertSame(
            [
                'platform' => 'GENERIC',
                'shopName' => 'My Shop',
                'shopUrl'  => 'https://shop.example.test',
            ],
            json_decode(Str::base64UrlDecode($query['config']), true)
        );
    }

    public function testStartTellsMyParcelWhichPlatformThisIs(): void
    {
        $config = (new ConnectConfig(ConnectPlatform::WOOCOMMERCE, self::KEY))->withAcceptance(true);
        $service = new ConnectService($config, $this->storage, $this->httpAnswering($this->tokenResponse()));

        $query = $this->query($service->start('My Shop', 'https://shop.example.test'));

        self::assertSame('WOOCOMMERCE', $this->configPayload($query)['platform']);
    }

    public function testStartSendsThePluginVersionWhenItIsGiven(): void
    {
        $query = $this->query($this->service()->start('My Shop', 'https://shop.example.test', '1.2.3'));

        self::assertSame('1.2.3', $this->configPayload($query)['version']);
    }

    public function testStartSendsNoVersionWhenThereIsNone(): void
    {
        // MyParcel records 0.0.1 for a plugin that does not say, so an absent key is not a problem.
        $query = $this->query($this->service()->start('My Shop', 'https://shop.example.test'));

        self::assertArrayNotHasKey('version', $this->configPayload($query));
    }

    public function testStartRefusesAVersionTheConfigCannotHold(): void
    {
        $this->expectException(ConnectException::class);

        $this->service()->start('My Shop', 'https://shop.example.test', str_repeat('9', 257));
    }

    public function testStartStoresNothingWhenItRefusesTheDetails(): void
    {
        // A refused call must leave no start nonce behind, or a later callback could match one the
        // merchant never got a page for. It must not generate a key pair either.
        try {
            $this->service()->start('My Shop', 'http://shop.example.test');
            self::fail('expected a ConnectException');
        } catch (ConnectException $exception) {
            self::assertNull($this->storage->row('nonces'));
            self::assertNull($this->storage->row('installation'));
        }
    }

    public function testStartRefusesDetailsThatAreTooLongTogether(): void
    {
        // Each field is within its own bound, but a name of 64 characters that JSON escapes to six
        // bytes each, plus a full URL and a full version, overflows the config parameter.
        $this->expectException(ConnectException::class);

        $this->service()->start(
            str_repeat("\u{4e2d}", 64),
            'https://' . str_repeat('a', 111) . '.test',
            str_repeat('9', 250)
        );
    }

    public function testStartAcceptsAShopNameOutsideLatin1(): void
    {
        // The generated model's pattern is emitted without /u and refuses these, so the SDK checks
        // it itself. A shop called Straße has to be able to connect.
        foreach (['Straße', 'Ølsalg', 'Łódź', '中文商店'] as $name) {
            self::assertNotSame('', $this->service()->start($name, 'https://shop.example.test'), $name);
        }
    }

    public function testTheBoundsStartChecksAreTheOnesTheSpecDeclares(): void
    {
        // start() checks the lengths itself, because the generated shop-name pattern is emitted
        // without /u and refuses valid names. That leaves the bounds in two places, so this fails
        // if a regenerated spec moves one out from under them.
        $longestUrl = 'https://' . str_repeat('a', 128 - strlen('https://') - strlen('.test')) . '.test';

        $atTheLimit = new ConnectStartConfig([
            'platform'  => ConnectPlatform::GENERIC,
            'shop_name' => str_repeat('a', 64),
            'shop_url'  => $longestUrl,
            'version'   => str_repeat('9', 256),
        ]);

        self::assertSame([], $atTheLimit->listInvalidProperties(), 'the spec still allows our maxima');

        // And start() accepts the same values, so neither side can shrink unnoticed.
        self::assertNotSame(
            '',
            $this->service()->start(str_repeat('a', 64), $longestUrl, str_repeat('9', 256))
        );

        $overTheLimit = [
            'shop_name' => str_repeat('a', 65),
            'shop_url'  => $longestUrl . 'a',
            'version'   => str_repeat('9', 257),
        ];

        foreach ($overTheLimit as $field => $value) {
            $config = new ConnectStartConfig([
                'platform'  => ConnectPlatform::GENERIC,
                'shop_name' => str_repeat('a', 64),
                'shop_url'  => $longestUrl,
                'version'   => str_repeat('9', 256),
                $field      => $value,
            ]);

            self::assertNotSame([], $config->listInvalidProperties(), "$field one over the limit");
        }
    }

    public function testStartStripsControlCharactersFromTheShopName(): void
    {
        // The spec bans them and they would break the consent page, but a merchant should not have
        // to rename their shop over a stray newline.
        $query = $this->query($this->service()->start("My\nShop", 'https://shop.example.test'));

        self::assertSame('MyShop', $this->configPayload($query)['shopName']);
    }

    public function testStartTrimsAShopNameThatIsTooLong(): void
    {
        // 64 characters is the spec's limit. The name is the platform's shop title, not something
        // the plugin picks, so it is cut to fit.
        $query = $this->query($this->service()->start(str_repeat('a', 100), 'https://shop.example.test'));

        self::assertSame(str_repeat('a', 64), $this->configPayload($query)['shopName']);
    }

    public function testStartCutsTheShopNameInCharactersNotBytes(): void
    {
        $query = $this->query($this->service()->start(str_repeat('é', 100), 'https://shop.example.test'));

        self::assertSame(64, mb_strlen($this->configPayload($query)['shopName']));
    }

    public function testStartEncodesTheScopeSpacesAsPercentTwenty(): void
    {
        // A + in a query is only a space under form encoding, so %20 leaves nothing to interpret.
        $url = $this->service()->start('My Shop', 'https://shop.example.test');

        self::assertStringContainsString('scope=integration%20write%3Aorders%20write%3Aproducts', $url);
        self::assertStringNotContainsString('+', $url);
    }

    /**
     * @param  array<string, string> $query
     * @return array<string, mixed>
     */
    private function configPayload(array $query): array
    {
        return (array) json_decode(Str::base64UrlDecode($query['config']), true);
    }

    public function testStartMakesASingleUseNonceOfThirtyTwoBytes(): void
    {
        $query = $this->query($this->service()->start('My Shop', 'https://shop.example.test'));

        self::assertSame(43, strlen($query['nonce']));
        self::assertSame($query['nonce'], $this->storage->row('nonces')['startNonce']);
    }

    public function testStartGivesTheMerchantAnHourToComeBack(): void
    {
        // 3600 is MyParcel's session lifetime, so the local window and the server's close together.
        // Shorter refuses a callback MyParcel still accepts; longer accepts one it will never send.
        $this->service()->start('My Shop', 'https://shop.example.test');

        self::assertExpiresIn(3600, $this->storage->row('nonces')['startNonceExpiresAt']);
    }

    /**
     * @dataProvider provideUnusableShopUrls
     */
    public function testStartRefusesSomethingThatIsNotAUsableShopUrl(string $shopUrl): void
    {
        $this->expectException(ConnectException::class);

        $this->service()->start('My Shop', $shopUrl);
    }

    public function provideUnusableShopUrls(): array
    {
        return $this->createProviderDataset([
            'not https'      => ['http://shop.example.test'],
            'a query'        => ['https://shop.example.test?a=1'],
            'a fragment'     => ['https://shop.example.test#x'],
            'empty'          => [''],
            'too long'       => ['https://' . str_repeat('a', 130) . '.test'],
            'not a url'      => ['shop.example.test'],
        ]);
    }

    /**
     * @dataProvider provideUnusableShopNames
     */
    public function testStartRefusesSomethingThatIsNotAUsableShopName(string $shopName): void
    {
        $this->expectException(ConnectException::class);

        $this->service()->start($shopName, 'https://shop.example.test');
    }

    public function provideUnusableShopNames(): array
    {
        // Everything else is trimmed to fit. A name the caller never supplied cannot be.
        return $this->createProviderDataset([
            'empty'                    => [''],
            'nothing but a newline'    => ["\n"],
        ]);
    }

    public function testCallbackWithoutANonceIsRefused(): void
    {
        $this->service()->start('My Shop', 'https://shop.example.test');

        $this->expectException(ConnectException::class);

        $this->service()->handleCallback(['code' => 'a-code', 'htm' => 'POST', 'htu' => self::HTU]);
    }

    public function testCallbackWithTheWrongNonceLeavesTheFlowAlone(): void
    {
        // A forged callback must not be able to cancel a flow the merchant is still in.
        $service = $this->service();
        $service->start('My Shop', 'https://shop.example.test');
        $stored = $this->storage->row('nonces')['startNonce'];

        try {
            $service->handleCallback(['nonce' => 'not-it', 'code' => 'a-code', 'htm' => 'POST', 'htu' => self::HTU]);
            self::fail('expected a ConnectException');
        } catch (ConnectException $exception) {
            self::assertSame($stored, $this->storage->row('nonces')['startNonce'], 'the flow survives');
        }
    }

    public function testCallbackWithAnExpiredNonceIsRefused(): void
    {
        $service = $this->service();
        $service->start('My Shop', 'https://shop.example.test');
        $nonce = $this->storage->row('nonces')['startNonce'];

        $nonces = $this->storage->row('nonces');

        // Age the stored flow rather than the clock, so this tests the check and not the time source.
        $nonces['startNonceExpiresAt'] = time() - 1;
        $this->storage->seed('nonces', $nonces);

        $this->expectException(ConnectException::class);

        $service->handleCallback(['nonce' => $nonce, 'code' => 'a-code', 'htm' => 'POST', 'htu' => self::HTU]);
    }

    public function testASuccessfulCallbackStoresEverythingTheShopNeeds(): void
    {
        $state = $this->connect();

        self::assertSame($this->jwt(['cnf' => ['jkt' => self::THUMBPRINT]]), $state->getAccessToken());
        self::assertExpiresIn(3600, (int) $state->getAccessTokenExpiresAt());
        self::assertSame(self::CONNECTION_ID, $state->getConnectionId());
        self::assertSame('integration', $state->getScope());
        self::assertSame(['htm' => 'POST', 'htu' => self::HTU], $state->getTokenChallenge());
    }

    public function testASuccessfulCallbackBurnsTheStartNonceAndKeepsTheDpopNonce(): void
    {
        $this->seedNonces(null, null, 'a-stored-nonce');
        $this->connect();

        $nonces = $this->storage->row('nonces');

        self::assertNull($nonces['startNonce'], 'a replay cannot start a second exchange');
        self::assertSame('a-stored-nonce', $nonces['dpopNonce'], 'the exchange itself needs this');
    }

    public function testTheExchangeProofIsBoundToTheCallbackHtmAndHtu(): void
    {
        $this->connect();

        $claims = $this->proofClaims(0);

        self::assertSame('POST', $claims['htm']);
        self::assertSame(self::HTU, $claims['htu']);
        self::assertArrayNotHasKey('ath', $claims, 'there is no access token yet to hash');
    }

    public function testTheExchangeProofCarriesAStoredNonce(): void
    {
        $this->seedNonces(null, null, 'a-stored-nonce');

        $this->connect();

        self::assertSame('a-stored-nonce', $this->proofClaims(0)['nonce']);
    }

    public function testANonceFromASuccessfulExchangeIsStoredForNextTime(): void
    {
        $this->connect($this->tokenResponse('a-fresh-nonce'));

        self::assertSame('a-fresh-nonce', $this->storage->row('nonces')['dpopNonce']);
    }

    public function testATokenBoundToAnotherKeyIsRefused(): void
    {
        // The proof and the token must belong to the same key, or every later call would 401.
        $this->expectException(ConnectException::class);

        $this->connect($this->tokenResponse(null, 'another-shops-thumbprint'));
    }

    public function testATokenWithoutAKeyBindingIsRefused(): void
    {
        $this->expectException(ConnectException::class);

        // A token with no cnf.jkt claim: nothing says which key it belongs to.
        $this->connect(self::jsonBody(200, [
            'accessToken'  => $this->jwt(['iss' => 'nobody']),
            'connectionId' => self::CONNECTION_ID,
            'tokenType'    => 'DPoP',
            'expiresIn'    => 3600,
            'scope'        => 'integration',
        ]));
    }

    /**
     * @dataProvider provideCallbackErrors
     */
    public function testACallbackErrorBecomesItsOwnCase(string $error, string $expectedCode): void
    {
        $service = $this->service();
        $service->start('My Shop', 'https://shop.example.test');
        $nonce = $this->storage->row('nonces')['startNonce'];

        try {
            $service->handleCallback(['nonce' => $nonce, 'error' => $error]);
            self::fail('expected a ConnectException');
        } catch (ConnectException $exception) {
            self::assertSame($expectedCode, $exception->getErrorCode());
        }
    }

    public function provideCallbackErrors(): array
    {
        return [
            'access denied'         => ['access_denied', 'access_denied'],
            'session expired'      => ['session_expired', 'session_expired'],
            'invalid sales channel' => ['invalid_sales_channel', 'invalid_sales_channel'],
            'server error'          => ['server_error', 'server_error'],
            'something new'         => ['something_auth0_invented', 'server_error'],
        ];
    }

    /**
     * @dataProvider provideUnusableChallenges
     */
    public function testACallbackWithAnUnusableChallengeIsRefusedRatherThanStored(string $htm, string $htu): void
    {
        // htu with a query cannot be signed, and storing it would make every later refresh fail with
        // no way back except reconnecting. So it is refused here, as a callback problem.
        $service = $this->service();
        $service->start('My Shop', 'https://shop.example.test');

        try {
            $service->handleCallback([
                'nonce' => $this->storage->row('nonces')['startNonce'],
                'code'  => 'a-code',
                'htm'   => $htm,
                'htu'   => $htu,
            ]);
            self::fail('expected a ConnectException');
        } catch (ConnectException $exception) {
            self::assertNull($this->storage->row('token'), 'nothing was stored');
        }
    }

    public function provideUnusableChallenges(): array
    {
        return $this->createProviderDataset([
            'a query'      => ['POST', self::HTU . '?a=1'],
            'a fragment'   => ['POST', self::HTU . '#x'],
            'not a url'    => ['POST', 'not-a-url'],
            'not https'    => ['POST', 'http://account.acceptance.myparcel.nl/oauth/token'],
            'no method'    => ['', self::HTU],
        ]);
    }

    public function testACallbackCarryingAnEmptyErrorIsStillASuccess(): void
    {
        // Some frameworks hand over every declared parameter, empty or not.
        $this->seedInstallation();

        $service = $this->service();
        $service->start('My Shop', 'https://shop.example.test');

        $state = $service->handleCallback([
            'nonce' => $this->storage->row('nonces')['startNonce'],
            'error' => '',
            'code'  => 'a-code',
            'htm'   => 'POST',
            'htu'   => self::HTU,
        ]);

        self::assertNotNull($state->getAccessToken());
    }

    public function testACallbackWithAnArrayParameterIsRefusedCleanly(): void
    {
        // ?nonce[]=x is a valid query string, so this arrives as an array.
        $service = $this->service();
        $service->start('My Shop', 'https://shop.example.test');

        $this->expectException(ConnectException::class);

        $service->handleCallback(['nonce' => ['x'], 'code' => 'a-code', 'htm' => 'POST', 'htu' => self::HTU]);
    }

    public function testStartCountsShopNameInCharactersNotBytes(): void
    {
        // 64 accented characters is 128 bytes, and the limit the message promises is characters.
        $name = str_repeat('é', 64);

        self::assertNotSame('', $this->service()->start($name, 'https://shop.example.test'));
    }

    public function testStartRefusesAShopNameThatCannotBeEncoded(): void
    {
        // Invalid UTF-8 arrives from any latin1 shop title. json_encode returns false for it, and an
        // empty config parameter would reach MyParcel with no shop name and no shop URL.
        $this->expectException(ConnectException::class);

        $this->service()->start('bad' . chr(0xB1) . 'name', 'https://shop.example.test');
    }

    public function testACallbackErrorStillBurnsTheStartNonce(): void
    {
        $service = $this->service();
        $service->start('My Shop', 'https://shop.example.test');
        $nonce = $this->storage->row('nonces')['startNonce'];

        try {
            $service->handleCallback(['nonce' => $nonce, 'error' => 'access_denied']);
        } catch (ConnectException $exception) {
            // expected
        }

        self::assertNull($this->storage->row('nonces')['startNonce']);
    }
}
