<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Connect;

use GuzzleHttp\Exception\ConnectException as GuzzleConnectException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use MyParcelNL\Sdk\Exception\ConnectException;

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
        self::assertSame(['code' => 'a-code'], json_decode((string) $request->getBody(), true));
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

    public function testTheRefreshPostsNoBodyButStillDeclaresJson(): void
    {
        // Without the Content-Type the service answers 415, and Guzzle sends none for an empty body.
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
                'accessToken' => $this->jwt(['cnf' => ['jkt' => self::THUMBPRINT]]),
                'tokenType'   => 'DPoP',
                'expiresIn'   => 3600,
                'scope'       => 'integration',
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
        // failures, and Guzzle raises them as ConnectException, which is not a RequestException.
        $this->seedConnectedShop();

        $failure = new GuzzleConnectException(
            'cURL error 6: Could not resolve host',
            new Request('POST', self::HOST . '/connect/refresh')
        );

        try {
            $this->service($failure)->refresh();
            self::fail('expected a ConnectException');
        } catch (ConnectException $exception) {
            self::assertStringContainsString('Could not resolve host', $exception->getMessage());
            self::assertSame(0, $exception->getStatusCode(), 'nothing came back to report a status for');
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
