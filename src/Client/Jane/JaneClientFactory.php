<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Client\Jane;

use Jane\Component\OpenApiRuntime\Client\AuthenticationPlugin;
use Jane\Component\OpenApiRuntime\Client\Plugin\AuthenticationRegistry;
use Jane\Component\OpenApiRuntime\Client\Plugin\ServerUrlHttpClient;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Authentication\BearerAuthentication as CoreApiAuthentication;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Client as CoreApiClient;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\JaneObjectNormalizer as CoreApiNormalizer;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Client\FormEncoder as CoreApiFormEncoder;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Authentication\JwtAuthentication as IamApiAuthentication;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Client as IamApiClient;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Normalizer\JaneObjectNormalizer as IamApiNormalizer;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Runtime\Client\FormEncoder as IamApiFormEncoder;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Builds the generated Jane clients with the API key, the user agent and extra headers.
 *
 * The generated Client::create() builds a serializer that validates responses, and a client cannot
 * change it later. So this factory builds the same parts as Client::create() itself, with
 * SkipValidationOnReadSerializer. JaneClientFactoryTest fails when a regeneration changes those parts.
 *
 * @internal Use the API factories, such as ShipmentApiFactory.
 */
final class JaneClientFactory
{
    /**
     * The Core API host from the spec.
     */
    public const CORE_API_HOST = 'https://api.myparcel.nl';

    /**
     * The IAM API host from the spec.
     */
    public const IAM_API_HOST = 'https://iam.api.myparcel.nl';

    /**
     * Default HTTP client timeout in seconds.
     *
     * Shared with generated-client based services to ensure consistent behaviour.
     */
    public const DEFAULT_HTTP_TIMEOUT = 10;

    /**
     * Create a Core API client.
     *
     * @param string                   $apiKey     The API key as the Backoffice shows it. This method encodes it.
     * @param string|null              $host       Overrides the Core API host. An empty string uses the default.
     * @param string|null              $userAgent  Sent as the User-Agent on every request.
     * @param array<string, string>    $headers    Sent on every request, such as `x-dmp-*` API feature flags.
     * @param HttpClientInterface|null $httpClient Sends the requests. Tests pass a MockHttpClient.
     */
    public static function coreApi(
        string $apiKey,
        ?string $host = null,
        ?string $userAgent = null,
        array $headers = [],
        ?HttpClientInterface $httpClient = null
    ): CoreApiClient {
        return new CoreApiClient(
            new ProblemJsonAcceptHttpClient(self::createHttpClient(
                $host ?: self::CORE_API_HOST,
                new CoreApiAuthentication(base64_encode($apiKey)),
                $userAgent,
                $headers,
                $httpClient
            )),
            self::createSerializer(new CoreApiNormalizer(), new CoreApiFormEncoder())
        );
    }

    /**
     * Create an IAM API client.
     *
     * The IAM spec calls the bearer scheme `jwt`. It accepts the same encoded API key as the Core API.
     *
     * @param string                   $apiKey     The API key as the Backoffice shows it. This method encodes it.
     * @param string|null              $host       Overrides the IAM API host. An empty string uses the default.
     * @param string|null              $userAgent  Sent as the User-Agent on every request.
     * @param array<string, string>    $headers    Sent on every request.
     * @param HttpClientInterface|null $httpClient Sends the requests. Tests pass a MockHttpClient.
     */
    public static function iamApi(
        string $apiKey,
        ?string $host = null,
        ?string $userAgent = null,
        array $headers = [],
        ?HttpClientInterface $httpClient = null
    ): IamApiClient {
        return new IamApiClient(
            self::createHttpClient(
                $host ?: self::IAM_API_HOST,
                new IamApiAuthentication(base64_encode($apiKey)),
                $userAgent,
                $headers,
                $httpClient
            ),
            self::createSerializer(new IamApiNormalizer(), new IamApiFormEncoder())
        );
    }

    /**
     * Add the timeout, the headers, the host and the authentication to the HTTP client.
     *
     * @param array<string, string> $headers
     */
    private static function createHttpClient(
        string $host,
        AuthenticationPlugin $authentication,
        ?string $userAgent,
        array $headers,
        ?HttpClientInterface $httpClient
    ): HttpClientInterface {
        if ($userAgent) {
            $headers['User-Agent'] = $userAgent;
        }

        $httpClient = ($httpClient ?? HttpClient::create())->withOptions([
            'timeout' => self::DEFAULT_HTTP_TIMEOUT,
            'headers' => $headers,
        ]);

        // The same decorators as the generated Client::create(), in the same order. The
        // AuthenticationRegistry adds the API key only to operations that the spec secures.
        $httpClient = (new ServerUrlHttpClient($host))($httpClient);

        return (new AuthenticationRegistry([$authentication]))($httpClient);
    }

    /**
     * Build the serializer with the same normalizers and encoders as the generated Client::create(),
     * and wrap it in SkipValidationOnReadSerializer.
     */
    private static function createSerializer(
        NormalizerInterface&DenormalizerInterface $normalizer,
        EncoderInterface&DecoderInterface $formEncoder
    ): SkipValidationOnReadSerializer {
        return new SkipValidationOnReadSerializer(
            new Serializer(
                [new ArrayDenormalizer(), $normalizer],
                [new JsonEncoder(new JsonEncode(), new JsonDecode(['json_decode_associative' => true])), $formEncoder]
            )
        );
    }
}
