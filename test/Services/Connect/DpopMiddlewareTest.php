<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Connect;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Promise\Create;
use GuzzleHttp\Psr7\NoSeekStream;
use GuzzleHttp\Psr7\Utils;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use MyParcelNL\Sdk\Services\Connect\ConnectService;
use MyParcelNL\Sdk\Services\Connect\DpopMiddleware;
use MyParcelNL\Sdk\Support\Str;
use Psr\Http\Message\RequestInterface;

/**
 * The middleware signs e-commerce calls. Two clients are mocked here: the one it sits on, and the
 * one ConnectService uses to refresh when a call comes back 401.
 */
class DpopMiddlewareTest extends ConnectServiceTestCase
{
    private const RESOURCE = 'https://shopify.ecommerce.api.acceptance.myparcel.nl/webhook/orders';

    /**
     * @var array<int, array{request: RequestInterface, response: mixed}>
     */
    private $calls = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->calls = [];
        $this->seedConnectedShop();
    }

    public function testPutsTheTokenAndItsProofOnTheRequest(): void
    {
        $this->ecommerce()->post(self::RESOURCE);

        $request = $this->call(0);

        self::assertSame('DPoP a.b.c', $request->getHeaderLine('Authorization'));
        self::assertNotSame('', $request->getHeaderLine('DPoP'));
    }

    public function testOverwritesABearerAuthorizationHeader(): void
    {
        // The generated client writes Bearer when an access token is set on its Configuration.
        $this->ecommerce()->post(self::RESOURCE, ['headers' => ['Authorization' => 'Bearer an-api-key']]);

        self::assertSame('DPoP a.b.c', $this->call(0)->getHeaderLine('Authorization'));
    }

    public function testTheProofBindsToTheMethodAndTheUrl(): void
    {
        $this->ecommerce()->post(self::RESOURCE);

        $claims = $this->proofOf(0);

        self::assertSame('POST', $claims['htm']);
        self::assertSame(self::RESOURCE, $claims['htu']);
    }

    public function testTheProofsUrlDropsTheQueryAndTheFragment(): void
    {
        // RFC 9449 signs htu without either, so a proof built from the full URL never verifies.
        $this->ecommerce()->get(self::RESOURCE . '?page=2#top');

        self::assertSame(self::RESOURCE, $this->proofOf(0)['htu']);
        self::assertSame('GET', $this->proofOf(0)['htm']);
    }

    public function testTheProofIsBoundToTheTokenThatGoesOutWithIt(): void
    {
        $this->ecommerce()->post(self::RESOURCE);

        $request = $this->call(0);
        $token   = substr($request->getHeaderLine('Authorization'), strlen('DPoP '));

        self::assertSame(
            Str::base64UrlEncode(hash('sha256', $token, true)),
            $this->proofOf(0)['ath'],
            'ath hashes the token as sent, so one read has to serve both headers'
        );
    }

    public function testA401RefreshesTheTokenAndSendsOnceMore(): void
    {
        $client = $this->ecommerce(
            [new Response(401, [], '{"message":"Unauthorized"}'), new Response(200, [], '[]')],
            [$this->refreshResponse()]
        );

        $response = $client->post(self::RESOURCE);

        self::assertSame(200, $response->getStatusCode());
        self::assertCount(2, $this->calls, 'exactly one retry');
        self::assertNotSame(
            $this->call(0)->getHeaderLine('Authorization'),
            $this->call(1)->getHeaderLine('Authorization'),
            'the retry carries the refreshed token'
        );
    }

    public function testTheRetryCarriesAFreshProofForTheFreshToken(): void
    {
        $client = $this->ecommerce(
            [new Response(401, [], '{"message":"Unauthorized"}'), new Response(200, [], '[]')],
            [$this->refreshResponse()]
        );

        $client->post(self::RESOURCE);

        $retried = $this->call(1);
        $token   = substr($retried->getHeaderLine('Authorization'), strlen('DPoP '));

        self::assertSame(Str::base64UrlEncode(hash('sha256', $token, true)), $this->proofOf(1)['ath']);
        self::assertNotSame($this->proofOf(0)['jti'], $this->proofOf(1)['jti'], 'a proof is single use');
    }

    public function testTheRetrySendsTheBodyAgainRatherThanAnEmptyOne(): void
    {
        // The first attempt reads the body to the end. Without a rewind the retry sends nothing and
        // a push with no orders in it looks like a success. MockHandler never reads a body, so the
        // other retry tests here cannot see this.
        $read = $this->attemptsAgainst(
            [new Response(401, [], '{"message":"Unauthorized"}'), new Response(202, [], '[]')],
            Utils::streamFor('[{"externalIdentifier":"order-42"}]')
        );

        self::assertCount(2, $read);
        self::assertSame('[{"externalIdentifier":"order-42"}]', $read[0]);
        self::assertSame($read[0], $read[1], 'the retry carried the same body, not an empty one');
    }

    public function testABodyThatCannotBeRewoundSurfacesThe401Instead(): void
    {
        // Nothing can replay it, so retrying would send an empty body and hide the 401.
        $read = $this->attemptsAgainst(
            [new Response(401, [], '{"message":"Unauthorized"}')],
            new NoSeekStream(Utils::streamFor('[{"externalIdentifier":"order-42"}]'))
        );

        self::assertCount(1, $read, 'no retry attempted');
    }

    public function testASecond401IsNotRetriedAgain(): void
    {
        $client = $this->ecommerce(
            [new Response(401, [], '{"message":"Unauthorized"}'), new Response(401, [], '{"message":"Unauthorized"}')],
            [$this->refreshResponse()]
        );

        try {
            $client->post(self::RESOURCE);
            self::fail('expected the second 401 to surface');
        } catch (ClientException $exception) {
            self::assertSame(401, $exception->getResponse()->getStatusCode());
        }

        self::assertCount(2, $this->calls, 'one retry, not a loop');
    }

    public function testAFailureThatIsNotA401IsNotRetried(): void
    {
        // Only a 401 means the token was refused. Retrying anything else would send it twice.
        $client = $this->ecommerce([new Response(500, [], 'boom')]);

        try {
            $client->post(self::RESOURCE);
            self::fail('expected the 500 to surface');
        } catch (\GuzzleHttp\Exception\ServerException $exception) {
            self::assertSame(500, $exception->getResponse()->getStatusCode());
        }

        self::assertCount(1, $this->calls);
    }

    public function testASuccessIsPassedStraightBack(): void
    {
        $response = $this->ecommerce([new Response(200, [], '{"ok":true}')])->post(self::RESOURCE);

        self::assertSame('{"ok":true}', (string) $response->getBody());
        self::assertCount(1, $this->calls);
    }

    public function testReadsTheTokenOnceEvenWhenEveryReadWouldRefreshIt(): void
    {
        // A token whose whole life is inside the refresh leeway: every getAccessToken() refreshes
        // it. Reading twice to fill the two headers would double the traffic, and could put a
        // different token in the Authorization header than the proof's ath was built from.
        $this->seedToken('expired.token', time() - 1);

        $client = $this->ecommerce(
            [new Response(200, [], '[]')],
            [$this->shortLivedRefresh(1), $this->shortLivedRefresh(2)]
        );

        $client->post(self::RESOURCE);

        self::assertSame(1, $this->callCount(), 'one refresh, so the token was read once');
    }

    /**
     * A refresh answer whose token expires inside the 30 second leeway.
     *
     * @param int $serial Makes each answer's token differ from the last.
     */
    private function shortLivedRefresh(int $serial): Response
    {
        return self::jsonBody(200, [
            'accessToken' => $this->jwt(['cnf' => ['jkt' => self::THUMBPRINT], 'serial' => $serial]),
            'tokenType'   => 'DPoP',
            'expiresIn'   => 10,
            'scope'       => 'integration',
        ]);
    }

    /**
     * A client with the middleware pushed inside http_errors, so it sees a raw 401 rather than an
     * exception. History is pushed after it, and so sits inside it, which is what lets these tests
     * assert on the signed request rather than the one that went in.
     *
     * @param \GuzzleHttp\Psr7\Response[]|null $answers        What the e-commerce host answers.
     *                                                       Nullable because new Response() cannot
     *                                                       be a parameter default before PHP 8.1.
     * @param \GuzzleHttp\Psr7\Response[]      $connectAnswers What /connect/refresh answers.
     */
    private function ecommerce(?array $answers = null, array $connectAnswers = []): Client
    {
        $connect = new ConnectService(
            $this->config(),
            $this->storage,
            $this->httpAnswering(...$connectAnswers)
        );

        $stack = HandlerStack::create(new MockHandler($answers ?? [new Response(200, [], '[]')]));
        $stack->push(new DpopMiddleware($connect));
        $stack->push(Middleware::history($this->calls));

        return new Client(['handler' => $stack]);
    }

    /**
     * Send one request through the middleware and report what each attempt's handler could read
     * off the body, from wherever the stream was left.
     *
     * @param  \GuzzleHttp\Psr7\Response[]        $answers
     * @param  \Psr\Http\Message\StreamInterface $body
     * @return string[]
     */
    private function attemptsAgainst(array $answers, $body): array
    {
        $read    = [];
        $connect = new ConnectService($this->config(), $this->storage, $this->httpAnswering($this->refreshResponse()));

        $stack = HandlerStack::create(static function ($request, $options) use (&$answers, &$read) {
            $read[] = $request->getBody()->getContents();

            return Create::promiseFor(array_shift($answers));
        });
        $stack->push(new DpopMiddleware($connect));

        try {
            (new Client(['handler' => $stack]))->post(self::RESOURCE, ['body' => $body]);
        } catch (\GuzzleHttp\Exception\ClientException $expected) {
            // A 401 that is not retried surfaces here, which is the point of one of the callers.
        }

        return $read;
    }

    /**
     * One e-commerce call. The inherited request() reads the connect client's history instead.
     */
    private function call(int $index): RequestInterface
    {
        return $this->request($index, $this->calls);
    }

    /**
     * @return array<string, mixed>
     */
    private function proofOf(int $index): array
    {
        return self::dpopClaimsOf($this->call($index));
    }
}
