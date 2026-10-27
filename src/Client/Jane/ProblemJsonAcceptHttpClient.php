<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Client\Jane;

use Symfony\Component\HttpClient\DecoratorTrait;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Adds `application/problem+json` to the first Accept line when a later line has it.
 *
 * Jane sends each Accept value as its own line. The Core API reads only the first line, so it sends an
 * error as `application/json`. For the operations that list `application/problem+json`, such as
 * `deleteShipments`, Jane generates an exception class that accepts only that model. An
 * `application/json` error then throws a TypeError.
 *
 * Remove this class when Jane generates exception classes that accept both models (janephp#1138).
 * JaneClientFactoryTest fails when that happens.
 *
 * @internal
 */
final class ProblemJsonAcceptHttpClient implements HttpClientInterface
{
    use DecoratorTrait;

    private const PROBLEM_JSON = 'application/problem+json';

    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        foreach ($options['headers'] ?? [] as $name => $value) {
            if (! is_string($name) || 0 !== strcasecmp($name, 'Accept')) {
                continue;
            }

            $accept = array_values((array) $value);

            if (in_array(self::PROBLEM_JSON, array_slice($accept, 1), true)) {
                $accept[0] .= ', ' . self::PROBLEM_JSON;
                $options['headers'][$name] = $accept;
            }
        }

        return $this->client->request($method, $url, $options);
    }
}
