<?php

namespace Tourze\CmsCollectBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\CmsCollectBundle\DependencyInjection\CmsCollectExtension;
use Tourze\PHPUnitSymfonyUnitTest\AbstractDependencyInjectionExtensionTestCase;

/**
 * @internal
 */
#[CoversClass(CmsCollectExtension::class)]
final class CmsCollectExtensionTest extends AbstractDependencyInjectionExtensionTestCase
{
    private CmsCollectExtension $extension;

    private ContainerBuilder $container;

    protected function setUp(): void
    {
        parent::setUp();
        $this->extension = new CmsCollectExtension();
        $this->container = new ContainerBuilder();
        $this->container->setParameter('kernel.environment', 'test');
    }

    public function testLoadWithEmptyConfigs(): void
    {
        $this->extension->load([], $this->container);

        // 验证 Procedure 服务已加载
        $this->assertTrue(
            $this->container->hasDefinition('Tourze\CmsCollectBundle\Procedure\CollectCmsEntity')
            || $this->container->hasAlias('Tourze\CmsCollectBundle\Procedure\CollectCmsEntity')
        );

        // 验证 Repository 服务已加载
        $this->assertTrue(
            $this->container->hasDefinition('Tourze\CmsCollectBundle\Repository\CollectLogRepository')
            || $this->container->hasAlias('Tourze\CmsCollectBundle\Repository\CollectLogRepository')
        );
    }

    public function testExtensionAlias(): void
    {
        $this->assertEquals('cms_collect', $this->extension->getAlias());
    }
}
