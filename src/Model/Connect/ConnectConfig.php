<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Connect;

use MyParcelNL\Sdk\Exception\ConnectException;

/**
 * What the consumer sets once, before any connect call.
 *
 * Immutable: every with* method returns a copy, so a config can be shared without anything changing
 * it underneath.
 */
final class ConnectConfig
{
    /**
     * The only two host shapes. This class is the only place in the SDK that builds an e-commerce
     * host, so the connect calls and the API calls cannot end up pointing at different places.
     */
    private const HOST_PRODUCTION = 'https://%s.ecommerce.api.myparcel.nl';

    private const HOST_ACCEPTANCE = 'https://%s.ecommerce.api.acceptance.myparcel.nl';

    private const DEFAULT_LEEWAY_SECONDS = 30;

    /**
     * @var string
     */
    private $platform;

    /**
     * @var string
     */
    private $servicePrefix;

    /**
     * @var string
     */
    private $encryptionKey;

    /**
     * @var bool
     */
    private $acceptance = false;

    /**
     * @var int
     */
    private $expiryLeewaySeconds = self::DEFAULT_LEEWAY_SECONDS;

    /**
     * @var string[]
     */
    private $scopes;

    /**
     * @param string $platform      The platform you are integrating, from ConnectPlatform. It picks
     *                              the e-commerce service the SDK calls.
     * @param string $encryptionKey Encrypts the secrets in the stored state. Keep it out of the
     *                              database, for example in wp-config.php. A key stored next to the
     *                              data it protects protects nothing.
     * @throws \MyParcelNL\Sdk\Exception\ConnectException
     */
    public function __construct(string $platform, string $encryptionKey)
    {
        $servicePrefix = ConnectPlatform::toServicePrefix($platform);

        if (null === $servicePrefix) {
            throw ConnectException::invalidArgument(sprintf(
                '"%s" is not a platform this SDK can connect, use one of: %s',
                $platform,
                implode(', ', ConnectPlatform::getAllowableEnumValues())
            ));
        }

        if ('' === $encryptionKey) {
            throw ConnectException::invalidArgument('The encryption key cannot be empty');
        }

        $this->platform      = $platform;
        $this->servicePrefix = $servicePrefix;
        $this->encryptionKey = $encryptionKey;
        $this->scopes        = ConnectScope::getAllowableEnumValues();
    }

    /**
     * Point at the acceptance environment instead of production.
     */
    public function withAcceptance(bool $acceptance): self
    {
        $clone             = clone $this;
        $clone->acceptance = $acceptance;

        return $clone;
    }

    /**
     * How early to refresh the access token, in seconds.
     *
     * The default of 30 keeps a call that is already on its way from expiring halfway through.
     *
     * @throws \MyParcelNL\Sdk\Exception\ConnectException
     */
    public function withExpiryLeewaySeconds(int $seconds): self
    {
        if ($seconds < 0) {
            throw ConnectException::invalidArgument('The expiry leeway cannot be negative');
        }

        $clone                      = clone $this;
        $clone->expiryLeewaySeconds = $seconds;

        return $clone;
    }

    /**
     * Ask for fewer scopes than the default of all of them.
     *
     * @param  string[] $scopes From ConnectScope.
     * @throws \MyParcelNL\Sdk\Exception\ConnectException
     */
    public function withScopes(array $scopes): self
    {
        if ([] === $scopes) {
            throw ConnectException::invalidArgument('Ask for at least one scope');
        }

        $unknown = array_diff($scopes, ConnectScope::getAllowableEnumValues());

        if ([] !== $unknown) {
            throw ConnectException::invalidArgument(sprintf(
                'Unknown scope(s) %s, use one of: %s',
                implode(', ', $unknown),
                implode(', ', ConnectScope::getAllowableEnumValues())
            ));
        }

        $clone         = clone $this;
        $clone->scopes = array_values(array_unique($scopes));

        return $clone;
    }

    /**
     * The platform, as MyParcel records it for the shop.
     */
    public function getPlatform(): string
    {
        return $this->platform;
    }

    /**
     * The first label of the e-commerce host, derived from the platform.
     */
    public function getServicePrefix(): string
    {
        return $this->servicePrefix;
    }

    public function getEncryptionKey(): string
    {
        return $this->encryptionKey;
    }

    public function isAcceptance(): bool
    {
        return $this->acceptance;
    }

    /**
     * The scopes to ask for. All of them, unless withScopes() narrowed the list.
     *
     * @return string[]
     */
    public function getScopes(): array
    {
        return $this->scopes;
    }

    /**
     * The scopes as /connect/start wants them: one string, space delimited.
     */
    public function getScopeString(): string
    {
        return implode(' ', $this->getScopes());
    }

    public function getExpiryLeewaySeconds(): int
    {
        return $this->expiryLeewaySeconds;
    }

    /**
     * The e-commerce host every connect and API call goes to.
     */
    public function getHost(): string
    {
        return sprintf($this->acceptance ? self::HOST_ACCEPTANCE : self::HOST_PRODUCTION, $this->servicePrefix);
    }
}
