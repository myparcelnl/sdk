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
        $config = new ConnectConfig(ConnectPlatform::GENERIC, self::KEY);

        self::assertFalse($config->isAcceptance());
        self::assertSame(30, $config->getExpiryLeewaySeconds());
        self::assertSame(
            ['integration', 'write:orders', 'write:products'],
            $config->getScopes(),
            'every scope, unless withScopes() narrows it'
        );
    }

    public function testBuildsTheProductionHostFromThePlatform(): void
    {
        self::assertSame(
            'https://generic.ecommerce.api.myparcel.nl',
            (new ConnectConfig(ConnectPlatform::GENERIC, self::KEY))->getHost()
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
        $config = new ConnectConfig(ConnectPlatform::WOOCOMMERCE, self::KEY);

        self::assertSame('WOOCOMMERCE', $config->getPlatform());
        self::assertSame('woo', $config->getServicePrefix());
        self::assertSame('https://woo.ecommerce.api.myparcel.nl', $config->getHost());
    }

    public function testScopeStringIsSpaceDelimited(): void
    {
        self::assertSame(
            'integration write:orders write:products',
            (new ConnectConfig(ConnectPlatform::GENERIC, self::KEY))->getScopeString()
        );
    }

    public function testWithScopesNarrowsTheList(): void
    {
        $config = (new ConnectConfig(ConnectPlatform::GENERIC, self::KEY))
            ->withScopes([ConnectScope::INTEGRATION, ConnectScope::WRITE_ORDERS]);

        self::assertSame('integration write:orders', $config->getScopeString());
    }

    public function testWithScopesDropsARepeatedScope(): void
    {
        $config = (new ConnectConfig(ConnectPlatform::GENERIC, self::KEY))
            ->withScopes([ConnectScope::INTEGRATION, ConnectScope::INTEGRATION]);

        self::assertSame(['integration'], $config->getScopes());
    }

    public function testWithScopesRejectsAnUnknownScope(): void
    {
        $this->expectException(ConnectException::class);

        (new ConnectConfig(ConnectPlatform::GENERIC, self::KEY))->withScopes(['read:capabilities']);
    }

    public function testWithScopesRejectsAnEmptyList(): void
    {
        $this->expectException(ConnectException::class);

        (new ConnectConfig(ConnectPlatform::GENERIC, self::KEY))->withScopes([]);
    }

    public function testWithMethodsLeaveTheOriginalAlone(): void
    {
        $config  = new ConnectConfig(ConnectPlatform::GENERIC, self::KEY);
        $changed = $config->withAcceptance(true)
            ->withExpiryLeewaySeconds(90)
            ->withScopes([ConnectScope::WRITE_ORDERS]);

        self::assertFalse($config->isAcceptance());
        self::assertSame(30, $config->getExpiryLeewaySeconds());
        self::assertCount(3, $config->getScopes());

        self::assertTrue($changed->isAcceptance());
        self::assertSame(90, $changed->getExpiryLeewaySeconds());
        self::assertSame(['write:orders'], $changed->getScopes());
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
            ['GENERIC', 'MAGENTO', 'PRESTA', 'SHOPIFY', 'WOOCOMMERCE'],
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
            self::assertStringContainsString('GENERIC', $exception->getMessage());
            self::assertStringContainsString('SHOPIFY', $exception->getMessage());
        }
    }

    public function testRejectsAnEmptyEncryptionKey(): void
    {
        $this->expectException(ConnectException::class);

        new ConnectConfig(ConnectPlatform::GENERIC, '');
    }

    public function testRejectsANegativeLeeway(): void
    {
        $this->expectException(ConnectException::class);

        (new ConnectConfig(ConnectPlatform::GENERIC, self::KEY))->withExpiryLeewaySeconds(-1);
    }

    public function testListsTheScopesTheServiceAllows(): void
    {
        self::assertSame(
            ['integration', 'write:orders', 'write:products'],
            ConnectScope::getAllowableEnumValues()
        );
    }
}
