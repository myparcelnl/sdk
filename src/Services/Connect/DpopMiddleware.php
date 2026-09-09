<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Connect;

use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Signs every e-commerce request with the connected shop's key.
 *
 * Push it onto a Guzzle handler stack after HandlerStack::create(), which makes it the innermost
 * middleware. It then sees a raw 401 with its headers, before http_errors turns one into an
 * exception.
 *
 * It belongs only on the e-commerce client. The connect calls sign themselves, against the htm and
 * htu stored at the callback rather than the URL being called.
 */
final class DpopMiddleware
{
    /**
     * @var \MyParcelNL\Sdk\Services\Connect\ConnectService
     */
    private $connect;

    public function __construct(ConnectService $connect)
    {
        $this->connect = $connect;
    }

    /**
     * @param  callable $next The next handler on the stack.
     * @return callable
     */
    public function __invoke(callable $next): callable
    {
        return function (RequestInterface $request, array $options) use ($next): PromiseInterface {
            return $next($this->sign($request), $options)->then(
                function (ResponseInterface $response) use ($next, $request, $options) {
                    if (401 !== $response->getStatusCode()) {
                        return $response;
                    }

                    $body = $request->getBody();

                    // A body that cannot be rewound cannot be sent twice, so the 401 surfaces
                    // instead. http_errors sits outside this middleware and raises it.
                    if (!$body->isSeekable()) {
                        return $response;
                    }

                    // getAccessToken() already refreshes an expired token, so a 401 here means
                    // MyParcel refused one that had not expired yet: a clock that runs behind, or a
                    // connection revoked mid-flight. One retry with a fresh token and a fresh proof.
                    $this->connect->refresh();

                    // The first attempt read the body to the end. Without this the retry sends an
                    // empty one, and a push with no orders in it looks like a success.
                    $body->rewind();

                    return $next($this->sign($request), $options);
                }
            );
        };
    }

    /**
     * Put the token and its proof on one request.
     *
     * @throws \MyParcelNL\Sdk\Exception\ConnectException
     */
    private function sign(RequestInterface $request): RequestInterface
    {
        // Read once, used twice. A token that changed between the header and the proof's ath would
        // be refused, because ath is the hash of the token that goes out with it.
        $token = $this->connect->getAccessToken();

        // Uri already lowercases the scheme and the host and drops a default port, which is the
        // normalisation MyParcel applies before it compares. RFC 9449 signs htu without either of
        // these, so a proof built from the full URL could never verify.
        $htu = (string) $request->getUri()->withQuery('')->withFragment('');

        return $request
            // DPoP, not Bearer: this overwrites the Authorization header the generated client writes
            // when an access token is set on its Configuration.
            ->withHeader('Authorization', 'DPoP ' . $token)
            ->withHeader('DPoP', $this->connect->createResourceProof($request->getMethod(), $htu, $token));
    }
}
