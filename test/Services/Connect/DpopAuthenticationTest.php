<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Connect;

use MyParcelNL\Sdk\Test\Bootstrap\Http\Response;
use Jane\Component\OpenApiRuntime\Client\Plugin\AuthenticationRegistry;
use Jane\Component\OpenApiRuntime\Client\Plugin\ServerUrlHttpClient;
use LogicException;
use MyParcelNL\Sdk\Services\Connect\DpopAuthentication;
use MyParcelNL\Sdk\Support\Str;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * The plugin signs a request of the generated e-commerce client. The shop is connected and has the
 * token `a.b.c`.
 */
final class DpopAuthenticationTest extends ConnectServiceTestCase
{
    private const RESOURCE = self::HOST . '/webhook/orders';

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedConnectedShop();
    }

    public function testItAppliesToTheDpopSchemeOfTheSpec(): void
    {
        self::assertSame('dpop', $this->plugin()->getScope());
    }

    public function testPutsTheTokenAndItsProofInTheHeaders(): void
    {
        $headers = $this->decorate('POST', self::RESOURCE)['headers'];

        self::assertSame('DPoP a.b.c', $headers['Authorization']);
        self::assertNotSame('', $headers['DPoP']);
    }

    public function testKeepsTheOtherHeadersAndOptions(): void
    {
        $options = $this->decorate('POST', self::RESOURCE, [
            'headers' => ['Content-Type' => 'application/json'],
            'body'    => '[]',
        ]);

        self::assertSame('application/json', $options['headers']['Content-Type']);
        self::assertSame('[]', $options['body']);
    }

    public function testTheProofBindsToTheMethodAndTheUrl(): void
    {
        $claims = $this->proofOf($this->decorate('POST', self::RESOURCE));

        self::assertSame('POST', $claims['htm']);
        self::assertSame(self::RESOURCE, $claims['htu']);
    }

    public function testTheProofsUrlDropsTheQueryAndTheFragment(): void
    {
        // RFC 9449 signs htu without either, so a proof built from the full URL never verifies.
        $claims = $this->proofOf($this->decorate('GET', self::RESOURCE . '?page=2#top'));

        self::assertSame(self::RESOURCE, $claims['htu']);
        self::assertSame('GET', $claims['htm']);
    }

    public function testTheProofsUrlHasALowerCaseHostAndNoDefaultPort(): void
    {
        $claims = $this->proofOf($this->decorate(
            'POST',
            'HTTPS://Shopify.Ecommerce.Api.Acceptance.MyParcel.nl:443/webhook/orders'
        ));

        self::assertSame(self::RESOURCE, $claims['htu']);
    }

    public function testTheProofsUrlKeepsAPortThatIsNotTheDefault(): void
    {
        $claims = $this->proofOf($this->decorate('POST', 'https://localhost:8443/webhook/orders'));

        self::assertSame('https://localhost:8443/webhook/orders', $claims['htu']);
    }

    public function testTheProofIsBoundToTheTokenThatGoesOutWithIt(): void
    {
        $options = $this->decorate('POST', self::RESOURCE);
        $token   = substr($options['headers']['Authorization'], strlen('DPoP '));

        self::assertSame(Str::base64UrlEncode(hash('sha256', $token, true)), $this->proofOf($options)['ath']);
    }

    public function testReadsTheTokenOnceEvenWhenEveryReadWouldRefreshIt(): void
    {
        // A token whose whole life is inside the refresh leeway: every getAccessToken() refreshes
        // it. A second read for the proof would double the traffic, and could put another token
        // in the Authorization header than the one that the proof was made for.
        $this->seedToken('expired.token', time() - 1);

        $plugin  = new DpopAuthentication($this->service($this->shortLivedRefresh(1), $this->shortLivedRefresh(2)));
        $options = [];

        $plugin->decorate('POST', self::RESOURCE, $options);

        self::assertSame(1, $this->callCount(), 'one refresh, so the token was read once');
    }

    public function testEveryRequestGetsItsOwnProof(): void
    {
        $first  = $this->proofOf($this->decorate('POST', self::RESOURCE));
        $second = $this->proofOf($this->decorate('POST', self::RESOURCE));

        self::assertNotSame($first['jti'], $second['jti'], 'a proof is single use');
    }

    public function testAUrlWithoutAHostThrows(): void
    {
        // The plugin got the path only: its registry sits outside the ServerUrlHttpClient.
        $this->expectException(LogicException::class);

        $this->decorate('POST', '/webhook/orders');
    }

    public function testSignsTheFullUrlWhenTheRegistrySitsInsideTheServerUrlClient(): void
    {
        $sent = [];
        $mock = new MockHttpClient(function (string $method, string $url, array $options) use (&$sent) {
            $sent = ['url' => $url, 'headers' => $options['normalized_headers']];

            return new MockResponse('[]', ['http_code' => 202]);
        });

        $client = (new ServerUrlHttpClient(self::HOST))((new AuthenticationRegistry([$this->plugin()]))($mock));

        // The generated client marks a secured operation in this way.
        $client->request('POST', '/webhook/orders', [
            'extra' => [AuthenticationRegistry::SCOPES_OPTION => ['dpop']],
        ]);

        self::assertSame(self::RESOURCE, $sent['url']);
        self::assertSame(['Authorization: DPoP a.b.c'], $sent['headers']['authorization']);
        self::assertSame(self::RESOURCE, self::claimsOf(substr($sent['headers']['dpop'][0], strlen('DPoP: ')))['htu']);
    }

    private function plugin(): DpopAuthentication
    {
        return new DpopAuthentication($this->service());
    }

    /**
     * A refresh answer whose token expires inside the 30 second leeway.
     *
     * @param int $serial Makes the token of each answer different.
     */
    private function shortLivedRefresh(int $serial): Response
    {
        return self::jsonBody(200, [
            'accessToken'      => $this->jwt(['cnf' => ['jkt' => self::THUMBPRINT], 'serial' => $serial]),
            'tokenType'        => 'DPoP',
            'expiresIn'        => 10,
            'refreshExpiresIn' => 31536000,
            'scope'            => 'integration',
        ]);
    }

    /**
     * Let the plugin decorate one request and give back the options, as the registry does.
     *
     * @param  array<string, mixed> $options
     * @return array<string, mixed>
     */
    private function decorate(string $method, string $url, array $options = []): array
    {
        $this->plugin()->decorate($method, $url, $options);

        return $options;
    }

    /**
     * The claims of the proof in the options of one request.
     *
     * @param  array<string, mixed> $options
     * @return array<string, mixed>
     */
    private function proofOf(array $options): array
    {
        return self::claimsOf($options['headers']['DPoP'] ?? '');
    }

    /**
     * @return array<string, mixed>
     */
    private static function claimsOf(string $proof): array
    {
        return (array) json_decode(Str::base64UrlDecode(explode('.', $proof)[1] ?? ''), true);
    }
}
