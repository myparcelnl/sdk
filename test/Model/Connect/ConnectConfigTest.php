<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Connect;

use MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\ConnectStartConfig;
use MyParcelNL\Sdk\Exception\ConnectException;
use MyParcelNL\Sdk\Model\Connect\ConnectConfig;
use MyParcelNL\Sdk\Model\Connect\ConnectPlatform;
use MyParcelNL\Sdk\Model\Connect\ConnectScope;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class ConnectConfigTest extends TestCase
{
    private const KEY = 'a key from wp-config.php';

    public function testDefaults(): void
    {
        $config = new ConnectConfig(ConnectPlatform::SHOPIFY, self::KEY);

        self::assertFalse($config->isAcceptance());
        self::assertSame(30, $config->getExpiryLeewaySeconds());
        self::assertSame(
            ['integration'],
            $config->getScopes(),
            'every scope, unless withScopes() narrows it'
        );
    }

    public function testBuildsTheProductionHostFromThePlatform(): void
    {
        self::assertSame(
            'https://shopify.ecommerce.api.myparcel.nl',
            (new ConnectConfig(ConnectPlatform::SHOPIFY, self::KEY))->getHost()
        );
    }

    public function testBuildsTheAcceptanceHostFromThePlatform(): void
    {
        self::assertSame(
            'https://shopify.ecommerce.api.acceptance.myparcel.nl',
            (new ConnectConfig(ConnectPlatform::SHOPIFY, self::KEY))->withAcceptance(true)->getHost()
        );
    }

    public function testTheHostLabelIsNotTheLowercasedPlatform(): void
    {
        // WOOCOMMERCE maps to 'woo', which is the only pair where the two differ. It is commented
        // out in ConnectPlatform::SERVICE_PREFIXES, and SHOPIFY maps to 'shopify', so there is
        // nothing left to tell apart. Restore this with the platform.
        self::markTestSkipped('only SHOPIFY is enabled, and its host label is its lowercased name');
    }

    public function testScopeStringIsSpaceDelimited(): void
    {
        self::assertSame(
            'integration',
            (new ConnectConfig(ConnectPlatform::SHOPIFY, self::KEY))->getScopeString()
        );
    }

    public function testWithScopesNarrowsTheList(): void
    {
        $config = (new ConnectConfig(ConnectPlatform::SHOPIFY, self::KEY))
            ->withScopes([ConnectScope::INTEGRATION]);

        self::assertSame('integration', $config->getScopeString());
    }

    public function testWithScopesDropsARepeatedScope(): void
    {
        $config = (new ConnectConfig(ConnectPlatform::SHOPIFY, self::KEY))
            ->withScopes([ConnectScope::INTEGRATION, ConnectScope::INTEGRATION]);

        self::assertSame(['integration'], $config->getScopes());
    }

    public function testWithScopesRejectsAnUnknownScope(): void
    {
        $this->expectException(ConnectException::class);

        (new ConnectConfig(ConnectPlatform::SHOPIFY, self::KEY))->withScopes(['delete:everything']);
    }

    public function testWithScopesRejectsAnEmptyList(): void
    {
        $this->expectException(ConnectException::class);

        (new ConnectConfig(ConnectPlatform::SHOPIFY, self::KEY))->withScopes([]);
    }

    public function testWithMethodsLeaveTheOriginalAlone(): void
    {
        $config  = new ConnectConfig(ConnectPlatform::SHOPIFY, self::KEY);
        $changed = $config->withAcceptance(true)
            ->withExpiryLeewaySeconds(90)
            ->withScopes([ConnectScope::INTEGRATION]);

        self::assertFalse($config->isAcceptance());
        self::assertSame(30, $config->getExpiryLeewaySeconds());
        self::assertCount(1, $config->getScopes());

        self::assertTrue($changed->isAcceptance());
        self::assertSame(90, $changed->getExpiryLeewaySeconds());
        self::assertSame(['integration'], $changed->getScopes());
    }

    /**
     * @dataProvider provideBadPlatforms
     */
    public function testRejectsAValueThatIsNotAPlatform(string $platform): void
    {
        $this->expectException(ConnectException::class);

        new ConnectConfig($platform, self::KEY);
    }

    public function provideBadPlatforms(): array
    {
        return $this->createProviderDataset([
            'empty'         => [''],
            'the host label rather than the platform' => ['woo'],
            'lowercase'     => ['shopify'],
            'a dot'         => ['shopify.ecommerce'],
            'a slash'       => ['shopify/x'],
            'a full host'   => ['https://shopify.ecommerce.api.myparcel.nl'],
            'an underscore' => ['MY_SHOP'],
        ]);
    }

    public function testEveryAcceptedPlatformHasAHostLabel(): void
    {
        foreach (ConnectPlatform::getAllowableEnumValues() as $platform) {
            $config = new ConnectConfig($platform, self::KEY);

            self::assertSame($platform, $config->getPlatform());
            self::assertNotSame('', $config->getServicePrefix());
        }
    }

    public function testListsTheAcceptedPlatforms(): void
    {
        self::assertSame(
            ['SHOPIFY'],
            ConnectPlatform::getAllowableEnumValues()
        );
    }

    public function testEveryAcceptedPlatformIsOneTheServiceDeclares(): void
    {
        // Guards the re-export: a platform we accept that the spec dropped would fail here rather
        // than at the first call.
        self::assertSame(
            [],
            array_diff(
                ConnectPlatform::getAllowableEnumValues(),
                (new ConnectStartConfig())->getPlatformAllowableValues()
            )
        );
    }

    public function testRejectsAPlatformTheServiceRunsButNoPluginTargets(): void
    {
        // CSCART and LIGHTSPEED are in the spec, but no PHP plugin targets them.
        $this->expectException(ConnectException::class);

        new ConnectConfig(ConnectStartConfig::PLATFORM_LIGHTSPEED, self::KEY);
    }

    public function testSaysWhichPlatformsAreAcceptedWhenOneIsWrong(): void
    {
        try {
            new ConnectConfig(ConnectStartConfig::PLATFORM_LIGHTSPEED, self::KEY);
            self::fail('expected a ConnectException');
        } catch (ConnectException $exception) {
            // Every accepted platform is named, so a caller can see what to pass instead.
            foreach (ConnectPlatform::getAllowableEnumValues() as $accepted) {
                self::assertStringContainsString($accepted, $exception->getMessage());
            }
        }
    }

    public function testRejectsAnEmptyEncryptionKey(): void
    {
        $this->expectException(ConnectException::class);

        new ConnectConfig(ConnectPlatform::SHOPIFY, '');
    }

    public function testRejectsANegativeLeeway(): void
    {
        $this->expectException(ConnectException::class);

        (new ConnectConfig(ConnectPlatform::SHOPIFY, self::KEY))->withExpiryLeewaySeconds(-1);
    }

    public function testListsTheScopesTheServiceAllows(): void
    {
        self::assertSame(
            ['integration'],
            ConnectScope::getAllowableEnumValues()
        );
    }
}
