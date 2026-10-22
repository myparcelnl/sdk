<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Ecommerce;

use GuzzleHttp\Psr7\Response;
use MyParcelNL\Sdk\Crypto\DpopKeyPair;
use MyParcelNL\Sdk\Crypto\EcdsaSignature;
use MyParcelNL\Sdk\Exception\CommandCheckUnavailableException;
use MyParcelNL\Sdk\Exception\UnauthorizedCommandException;
use MyParcelNL\Sdk\Services\Ecommerce\CommandRequestVerifier;
use MyParcelNL\Sdk\Test\Bootstrap\InMemoryJwksStorage;
use MyParcelNL\Sdk\Test\Services\Connect\ConnectServiceTestCase;

/**
 * Signs requests the way MyParcel does, with a key the test serves as MyParcel's JWKS, and checks
 * that every check lets a good request through and refuses a bad one.
 */
final class CommandRequestVerifierTest extends ConnectServiceTestCase
{
    private const BODY = '{"type":"ORDER_SHIPMENTS","order":{"sourceId":"1001","status":"PARTIALLY_SHIPPED"},"shipment":{"shipmentId":"a3f1c0de-0000-4000-8000-000000000001","orderLines":[{"sourceId":"5001","quantity":2}]},"commandId":"6f1c2d3e-4a5b-4c6d-8e7f-0a1b2c3d4e5f","connectionId":"' . self::CONNECTION_ID . '"}';

    /**
     * @var \MyParcelNL\Sdk\Crypto\DpopKeyPair MyParcel's signing key, for this test.
     */
    private $myparcelKey;

    /**
     * @var \MyParcelNL\Sdk\Test\Bootstrap\InMemoryJwksStorage
     */
    private $jwksStorage;

    protected function setUp(): void
    {
        parent::setUp();

        $this->myparcelKey = DpopKeyPair::generate();
        $this->jwksStorage = new InMemoryJwksStorage();

        $this->seedInstallation(self::CONNECTION_ID, 'POST', self::HTU, null, self::COMMAND_ENDPOINT);
        $this->seedJwks([$this->myparcelJwk()]);
    }

    public function testAcceptsARequestSignedByMyParcel(): void
    {
        $this->verifier()->verify('POST', $this->sign(), self::BODY);

        self::assertCount(0, $this->history, 'the stored keys were used, nothing was fetched');
    }

    public function testAcceptsHeadersInAnyCasingAndAsLists(): void
    {
        $headers = [];

        foreach ($this->sign() as $name => $value) {
            $headers[strtoupper($name)] = [$value];
        }

        $this->verifier()->verify('POST', $headers, self::BODY);

        self::assertTrue(true);
    }

    public function testFetchesTheKeysWhenNothingIsStored(): void
    {
        $this->jwksStorage->seed(null);

        $this->verifier(self::jwks([$this->myparcelJwk()]))->verify('POST', $this->sign(), self::BODY);

        self::assertCount(1, $this->history);
        self::assertStringEndsWith('/.well-known/jwks.json', (string) $this->history[0]['request']->getUri());
    }

    public function testAFailedKeyFetchIsUnavailableNotUnauthorized(): void
    {
        $this->jwksStorage->seed(null);

        $this->expectException(CommandCheckUnavailableException::class);

        $this->verifier(new Response(503))->verify('POST', $this->sign(), self::BODY);
    }

    public function testAnUnknownKeyIsUnauthorized(): void
    {
        $this->seedJwks([]);

        $this->expectExceptionObject(new UnauthorizedCommandException('The signature keyid is not a MyParcel key'));

        $this->verifier(self::jwks([]))->verify('POST', $this->sign(), self::BODY);
    }

    public function testASignatureByAnotherKeyIsUnauthorized(): void
    {
        // The right kid, but the signature comes from a key MyParcel does not have.
        $headers = $this->sign(['signer' => DpopKeyPair::generate()]);

        $this->expectExceptionObject(new UnauthorizedCommandException('The signature does not verify'));

        $this->verifier()->verify('POST', $headers, self::BODY);
    }

    public function testAChangedBodyIsUnauthorizedOnTheDigest(): void
    {
        $this->expectExceptionObject(new UnauthorizedCommandException('Content-Digest does not match the body'));

        $this->verifier()->verify('POST', $this->sign(), str_replace('"quantity":2', '"quantity":3', self::BODY));
    }

    public function testADigestThatMatchesAChangedBodyIsUnauthorizedOnTheSignature(): void
    {
        $body    = str_replace('"quantity":2', '"quantity":3', self::BODY);
        $headers = $this->sign();

        $headers['Content-Digest'] = self::digest($body);

        $this->expectExceptionObject(new UnauthorizedCommandException('The signature does not verify'));

        $this->verifier()->verify('POST', $headers, $body);
    }

    public function testAnotherTargetUriIsUnauthorized(): void
    {
        // Signed for another shop's endpoint. The stored one is what the base is built with.
        $headers = $this->sign(['targetUri' => 'https://other.example.test/myparcel/commands']);

        $this->expectExceptionObject(new UnauthorizedCommandException('The signature does not verify'));

        $this->verifier()->verify('POST', $headers, self::BODY);
    }

    public function testAnotherMethodIsUnauthorized(): void
    {
        $this->expectExceptionObject(new UnauthorizedCommandException('The signature does not verify'));

        $this->verifier()->verify('PUT', $this->sign(), self::BODY);
    }

    public function testAShopWithoutACommandEndpointIsUnauthorized(): void
    {
        $this->seedInstallation(self::CONNECTION_ID, 'POST', self::HTU);

        $this->expectExceptionObject(new UnauthorizedCommandException('This shop has no command endpoint'));

        $this->verifier()->verify('POST', $this->sign(), self::BODY);
    }

    public function testAnotherConnectionIdIsUnauthorized(): void
    {
        $body = str_replace(self::CONNECTION_ID, 'some-other-connection', self::BODY);

        $this->expectExceptionObject(new UnauthorizedCommandException('The connectionId is not this shop\'s'));

        $this->verifier()->verify('POST', $this->sign(['body' => $body]), $body);
    }

    public function testABodyThatIsNotAJsonObjectIsUnauthorized(): void
    {
        $body = '[1,2]';

        $this->expectExceptionObject(new UnauthorizedCommandException('The body is not a JSON object'));

        $this->verifier()->verify('POST', $this->sign(['body' => $body]), $body);
    }

    /**
     * @dataProvider provideTimes
     */
    public function testTheTimeWindowHasNoTolerance(int $createdOffset, int $lifetime, ?string $failure): void
    {
        $headers = $this->sign(['created' => time() + $createdOffset, 'lifetime' => $lifetime]);

        if (null !== $failure) {
            $this->expectExceptionObject(new UnauthorizedCommandException($failure));
        }

        $this->verifier()->verify('POST', $headers, self::BODY);

        self::assertTrue(true);
    }

    public function provideTimes(): array
    {
        // Offsets leave a margin of a few seconds for the test's own run time.
        return [
            'fresh'                         => [0, 60, null],
            'created 55 s ago'              => [-55, 60, null],
            'created 55 s ahead'            => [55, 60, null],
            'created 61 s ago'              => [-61, 60, 'The signature was created too far from now'],
            'created 61 s ahead'            => [61, 60, 'The signature was created too far from now'],
            'expired'                       => [-50, 45, 'The signature has expired'],
            'lives 61 s'                    => [0, 61, 'The signature lives longer than 60 seconds'],
        ];
    }

    /**
     * @dataProvider provideBrokenHeaders
     */
    public function testBrokenSignatureHeadersAreUnauthorized(array $changes, string $failure): void
    {
        $headers = array_merge($this->sign(), $changes);

        $this->expectExceptionObject(new UnauthorizedCommandException($failure));

        $this->verifier()->verify('POST', $headers, self::BODY);
    }

    public function provideBrokenHeaders(): array
    {
        $params = ';created=1791806400;expires=1791806460;keyid="k";alg="ecdsa-p256-sha256"';
        $list   = '("@method" "@target-uri" "content-type" "content-digest")';

        return [
            'no Signature-Input'       => [['Signature-Input' => ''], 'The signature-input header is missing'],
            'no Signature'             => [['Signature' => ''], 'The signature header is missing'],
            'no Content-Digest'        => [['Content-Digest' => ''], 'The content-digest header is missing'],
            'no Content-Type'          => [['Content-Type' => ''], 'The content-type header is missing'],
            'Signature-Input not 8941' => [['Signature-Input' => 'myparcel=(unterminated'], 'The signature-input header is not an RFC 8941 dictionary'],
            'Signature-Input cut off'  => [['Signature-Input' => 'myparcel='], 'The signature-input header is not an RFC 8941 dictionary'],
            'parameter cut off'        => [['Signature-Input' => 'myparcel=("@method");created='], 'The signature-input header is not an RFC 8941 dictionary'],
            'Content-Digest cut off'   => [['Content-Digest' => 'sha-256='], 'The content-digest header is not an RFC 8941 dictionary'],
            'two members'              => [['Signature-Input' => "myparcel=$list$params, other=$list$params"], 'The header must have exactly one member, myparcel'],
            'other label'              => [['Signature-Input' => "sig1=$list$params"], 'The header must have exactly one member, myparcel'],
            'Signature other label'    => [['Signature' => 'sig1=:AAAA:'], 'The header must have exactly one member, myparcel'],
            'not an inner list'        => [['Signature-Input' => "myparcel=\"x\"$params"], 'Signature-Input is not an inner list'],
            'fewer components'         => [['Signature-Input' => "myparcel=(\"@method\" \"@target-uri\" \"content-type\")$params"], 'The covered components are not the ones MyParcel signs'],
            'other order'              => [['Signature-Input' => "myparcel=(\"@target-uri\" \"@method\" \"content-type\" \"content-digest\")$params"], 'The covered components are not the ones MyParcel signs'],
            'component parameter'      => [['Signature-Input' => "myparcel=(\"@method\" \"@target-uri\" \"content-type\" \"content-digest\";sf)$params"], 'A covered component is not a plain string'],
            'other alg'                => [['Signature-Input' => "myparcel=$list;created=1791806400;expires=1791806460;keyid=\"k\";alg=\"rsa-v1_5-sha256\""], 'The signature alg is not ecdsa-p256-sha256'],
            'no alg'                   => [['Signature-Input' => "myparcel=$list;created=1791806400;expires=1791806460;keyid=\"k\""], 'The signature alg is not ecdsa-p256-sha256'],
            'created a string'         => [['Signature-Input' => "myparcel=$list;created=\"1791806400\";expires=1791806460;keyid=\"k\";alg=\"ecdsa-p256-sha256\""], 'The signature parameters have the wrong types'],
            'no expires'               => [['Signature-Input' => "myparcel=$list;created=1791806400;keyid=\"k\";alg=\"ecdsa-p256-sha256\""], 'The signature parameters have the wrong types'],
            'keyid a token'            => [['Signature-Input' => "myparcel=$list;created=1791806400;expires=1791806460;keyid=k;alg=\"ecdsa-p256-sha256\""], 'The signature parameters have the wrong types'],
            'Signature not bytes'      => [['Signature' => 'myparcel="abc"'], 'The Signature value is not a byte sequence'],
            'digest sha-512'           => [['Content-Digest' => 'sha-512=:AAAA:'], 'The header must have exactly one member, sha-256'],
            'two digests'              => [['Content-Digest' => 'sha-256=:AAAA:, sha-512=:AAAA:'], 'The header must have exactly one member, sha-256'],
            'digest not bytes'         => [['Content-Digest' => 'sha-256="abc"'], 'The Content-Digest value is not a byte sequence'],
        ];
    }

    /**
     * @dataProvider provideRequestsThatFailBeforeTheKeyLookup
     */
    public function testACheckThatFailsBeforeTheKeyLookupCostsNoFetch(array $signOverrides, array $changes, string $failure): void
    {
        // Nothing stored, so a key lookup would fetch. None of these may get that far.
        $this->jwksStorage->seed(null);
        $headers = array_merge($this->sign($signOverrides), $changes);

        try {
            $this->verifier()->verify('POST', $headers, self::BODY);
            self::fail('expected an UnauthorizedCommandException');
        } catch (UnauthorizedCommandException $exception) {
            self::assertSame($failure, $exception->getMessage());
            self::assertCount(0, $this->history);
        }
    }

    public function provideRequestsThatFailBeforeTheKeyLookup(): array
    {
        // The last one does reach the lookup, which proves the fetch happens there and not earlier.
        return [
            'broken header'   => [[], ['Signature' => 'sig1=:AAAA:'], 'The header must have exactly one member, myparcel'],
            'expired'         => [['created' => time() - 120], [], 'The signature was created too far from now'],
            'wrong digest'    => [[], ['Content-Digest' => self::digest('other')], 'Content-Digest does not match the body'],
            'no Content-Type' => [[], ['Content-Type' => ''], 'The content-type header is missing'],
        ];
    }

    public function testASignatureIsRefusedWhenASecondSignatureInputLineArrives(): void
    {
        // Two header lines are one header with two members after RFC 9110 joining.
        $headers = $this->sign();

        $headers['Signature-Input'] = [$headers['Signature-Input'], 'other=("@method");created=1'];

        $this->expectExceptionObject(new UnauthorizedCommandException('The header must have exactly one member, myparcel'));

        $this->verifier()->verify('POST', $headers, self::BODY);
    }

    /**
     * Sign a request the way MyParcel's signer does.
     *
     * @param  array{body?: string, created?: int, lifetime?: int, targetUri?: string, signer?: DpopKeyPair} $overrides
     * @return array<string, string> Content-Type, Content-Digest, Signature-Input and Signature.
     */
    private function sign(array $overrides = []): array
    {
        $body      = $overrides['body'] ?? self::BODY;
        $created   = $overrides['created'] ?? time();
        $expires   = $created + ($overrides['lifetime'] ?? 60);
        $targetUri = $overrides['targetUri'] ?? self::COMMAND_ENDPOINT;
        $signer    = $overrides['signer'] ?? $this->myparcelKey;
        $digest    = self::digest($body);

        $params = sprintf(
            '("@method" "@target-uri" "content-type" "content-digest");created=%d;expires=%d;keyid="%s";alg="ecdsa-p256-sha256"',
            $created,
            $expires,
            $this->myparcelKey->getThumbprint()
        );

        $base = implode("\n", [
            '"@method": POST',
            '"@target-uri": ' . $targetUri,
            '"content-type": application/json',
            '"content-digest": ' . $digest,
            '"@signature-params": ' . $params,
        ]);

        openssl_sign($base, $der, $signer->getKeyMaterial(), OPENSSL_ALGO_SHA256);

        return [
            'Content-Type'    => 'application/json',
            'Content-Digest'  => $digest,
            'Signature-Input' => 'myparcel=' . $params,
            'Signature'       => 'myparcel=:' . base64_encode(EcdsaSignature::signatureFromDER($der)) . ':',
        ];
    }

    private static function digest(string $body): string
    {
        return 'sha-256=:' . base64_encode(hash('sha256', $body, true)) . ':';
    }

    /**
     * @param \GuzzleHttp\Psr7\Response|\Throwable ...$answers What the JWKS URL answers.
     */
    private function verifier(...$answers): CommandRequestVerifier
    {
        return new CommandRequestVerifier($this->service(), $this->jwksStorage, $this->httpAnswering(...$answers));
    }

    /**
     * @return array<string, string>
     */
    private function myparcelJwk(): array
    {
        return $this->myparcelKey->getPublicJwk() + ['kid' => $this->myparcelKey->getThumbprint(), 'alg' => 'ES256', 'use' => 'sig'];
    }

    private function seedJwks(array $keys): void
    {
        $this->jwksStorage->seed(['version' => 1, 'jwks' => ['keys' => $keys], 'fetchedAt' => time()]);
    }

    private static function jwks(array $keys): Response
    {
        return new Response(200, ['Content-Type' => 'application/json'], (string) json_encode(['keys' => $keys]));
    }
}
