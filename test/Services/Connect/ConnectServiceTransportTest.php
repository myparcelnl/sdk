<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Connect;

use MyParcelNL\Sdk\Test\Bootstrap\Http\Response;
use MyParcelNL\Sdk\Exception\ConnectException;
use MyParcelNL\Sdk\Services\Connect\ConnectService;
use RuntimeException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * What the two connect calls put on the wire, and what the service makes of what comes back.
 */
class ConnectServiceTransportTest extends ConnectServiceTestCase
{
    public function testTheExchangePostsTheCodeAsJsonWithTheProofInItsOwnHeader(): void
    {
        $this->connect();

        $request = $this->request(0);

        self::assertSame('POST', $request->getMethod());
        self::assertSame(self::HOST . '/connect/token', (string) $request->getUri());
        self::assertSame('application/json', $request->getHeaderLine('Content-Type'));
        self::assertSame(['code' => self::CODE], json_decode((string) $request->getBody(), true));
        self::assertNotSame('', $request->getHeaderLine('DPoP'));
    }

    public function testNeitherCallSendsAnAuthorizationHeader(): void
    {
        // Holding the key is the credential at both connect endpoints.
        $this->connect();

        self::assertFalse($this->request(0)->hasHeader('Authorization'));

        $this->seedConnectedShop();
        $this->service($this->refreshResponse())->refresh();

        self::assertFalse($this->request(1)->hasHeader('Authorization'));
    }

    public function testAConnectCallDoesNotFollowARedirect(): void
    {
        // A redirect would send the proof, and at /connect/token the code, to another URL.
        $this->seedConnectedShop();

        $maxRedirects = null;
        $http         = new MockHttpClient(function (string $method, string $url, array $options) use (&$maxRedirects) {
            $maxRedirects = $options['max_redirects'] ?? null;

            return new MockResponse('', [
                'http_code'        => 307,
                'response_headers' => ['location' => 'https://elsewhere.example.test/connect/refresh'],
            ]);
        });

        try {
            (new ConnectService($this->config(), $this->storage, $http))->refresh();
            self::fail('The 307 did not throw.');
        } catch (ConnectException $exception) {
            self::assertSame(307, $exception->getStatusCode());
        }

        self::assertSame(0, $maxRedirects, 'the HTTP client must not follow the redirect');
    }

    public function testTheRefreshPostsNoBodyButStillDeclaresJson(): void
    {
        // Without the Content-Type the service answers 415, and the generated endpoint sends none
        // for a call without a body.
        $this->seedConnectedShop();

        $this->service($this->refreshResponse())->refresh();

        $request = $this->request(0);

        self::assertSame(self::HOST . '/connect/refresh', (string) $request->getUri());
        self::assertSame('application/json', $request->getHeaderLine('Content-Type'));
        self::assertSame('', (string) $request->getBody());
    }

    public function testARepeatedNonceHeaderTakesTheFirstValue(): void
    {
        // getHeaderLine() joins repeated values with ", ", and that string as a nonce would put the
        // flow into a permanent retry loop.
        $this->seedConnectedShop();

        $this->service(new Response(
            200,
            ['DPoP-Nonce' => ['first', 'second'], 'Content-Type' => 'application/json'],
            (string) json_encode([
                'accessToken'      => $this->jwt(['cnf' => ['jkt' => self::THUMBPRINT]]),
                'tokenType'        => 'DPoP',
                'expiresIn'        => 3600,
                'refreshExpiresIn' => 31536000,
                'scope'            => 'integration',
            ])
        ))->refresh();

        self::assertSame('first', $this->storage->row('nonces')['dpopNonce']);
    }

    public function testASuccessBodyThatIsNotJsonIsAnHttpFailure(): void
    {
        $this->seedConnectedShop();

        try {
            $this->service(new Response(200, ['Content-Type' => 'application/json'], 'not json'))->refresh();
            self::fail('expected a ConnectException');
        } catch (ConnectException $exception) {
            self::assertSame(200, $exception->getStatusCode(), 'it did reach MyParcel');
        }
    }

    public function testAFailureBodyThatIsNotJsonKeepsItsStatus(): void
    {
        // A 415 answers with problem+json, and an HTML error page is not JSON at all.
        $this->seedConnectedShop();

        try {
            $this->service(new Response(415, [], '<html>no</html>'))->refresh();
            self::fail('expected a ConnectException');
        } catch (ConnectException $exception) {
            self::assertSame(415, $exception->getStatusCode());
            self::assertNull($exception->getErrorCode(), 'there was no error member to read');
        }
    }

    public function testAFailureCarriesTheStatusTheBodyAndTheHeaders(): void
    {
        $this->seedConnectedShop();

        try {
            $this->service(self::errorResponse(400, 'use_dpop_nonce'))->refresh();
            self::fail('expected a ConnectException');
        } catch (ConnectException $exception) {
            self::assertSame(400, $exception->getStatusCode());
            self::assertSame('use_dpop_nonce', $exception->getErrorCode());
            self::assertSame(['error' => 'use_dpop_nonce'], $exception->getResponseBody());
        }
    }

    public function testANetworkFailureKeepsItsCause(): void
    {
        // DNS failure, connection refused, a TLS error and a timeout are the commonest production
        // failures. The HTTP client raises them as a transport exception, without an answer.
        $this->seedConnectedShop();

        $failure = new RuntimeException('Could not resolve host');

        try {
            $this->service($failure)->refresh();
            self::fail('expected a ConnectException');
        } catch (ConnectException $exception) {
            self::assertStringContainsString('Could not resolve host', $exception->getMessage());
            self::assertSame(0, $exception->getStatusCode(), 'nothing came back to report a status for');
        }
    }

    public function testACallbackCodeThatIsNotInTheFormOfTheSpecIsRefusedAndNotSent(): void
    {
        // The generated client checks the code against the pattern of the spec before it sends it.
        $this->seedInstallation();

        $service = $this->service();
        $service->start('My Shop', 'https://shop.example.test');

        try {
            $service->handleCallback([
                'nonce' => $this->storage->row('nonces')['startNonce'],
                'code'  => 'not a code',
                'htm'   => 'POST',
                'htu'   => self::HTU,
            ]);
            self::fail('expected a ConnectException');
        } catch (ConnectException $exception) {
            self::assertStringContainsString('code', $exception->getMessage());
        }

        self::assertSame(0, $this->callCount(), 'nothing went out');
    }

    public function testAFailureCarriesItsHeadersWithLowerCaseNames(): void
    {
        // The HTTP client reports the names of response headers in lower case.
        $this->seedConnectedShop();

        try {
            $this->service(self::errorResponse(401, 'invalid_dpop_proof', ['X-Request-Id' => 'abc']))->refresh();
            self::fail('expected a ConnectException');
        } catch (ConnectException $exception) {
            self::assertSame(['abc'], $exception->getResponseHeaders()['x-request-id']);
        }
    }

    public function testASuccessBodyThatIsNotAnObjectIsAnHttpFailure(): void
    {
        $this->seedConnectedShop();

        try {
            $this->service(new Response(200, ['Content-Type' => 'application/json'], '"a token"'))->refresh();
            self::fail('expected a ConnectException');
        } catch (ConnectException $exception) {
            self::assertSame(200, $exception->getStatusCode());
        }
    }

    public function testSendsTheSameUserAgentAsTheRestOfTheSdk(): void
    {
        $this->connect();

        $sent = $this->request(0)->getHeaderLine('User-Agent');

        self::assertStringContainsString('MyParcelNL-SDK/', $sent);
        self::assertStringNotContainsString('OpenAPI-Generator', $sent, 'that names the generator, not us');
    }

    public function testAPluginNameGoesInFrontOfTheSdkUserAgent(): void
    {
        // The same setUserAgentForProposition() the other services take, so a MyParcel log line
        // reads the same whichever endpoint it came from.
        $this->seedConnectedShop();

        $service = $this->service($this->refreshResponse());
        $service->setUserAgentForProposition('MyParcelNL-WooCommerce', '5.1.0');
        $service->refresh();

        self::assertStringStartsWith(
            'MyParcelNL-WooCommerce/5.1.0 MyParcelNL-SDK/',
            $this->request(0)->getHeaderLine('User-Agent')
        );
    }
}
