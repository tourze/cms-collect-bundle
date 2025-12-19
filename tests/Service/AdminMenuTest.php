<?php

declare(strict_types=1);

namespace Tourze\CmsCollectBundle\Tests\Service;

use Knp\Menu\ItemInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\CmsCollectBundle\Entity\CollectLog;
use Tourze\CmsCollectBundle\Service\AdminMenu;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminMenuTestCase;

/**
 * AdminMenu 单元测试
 *
 * @internal
 */
#[CoversClass(AdminMenu::class)]
#[RunTestsInSeparateProcesses]
final class AdminMenuTest extends AbstractEasyAdminMenuTestCase
{
    protected function onSetUp(): void
    {
        // 创建测试专用的LinkGenerator实现，避免Mock导致的类型推断失败
        $linkGenerator = new class implements LinkGeneratorInterface {
            public function getCurdListPage(string $entityClass): string
            {
                return match ($entityClass) {
                    CollectLog::class => '/admin/collectlog',
                    default => '/admin/unknown',
                };
            }

            public function extractEntityFqcn(string $url): ?string
            {
                return match (true) {
                    str_contains($url, '/admin/collectlog') => CollectLog::class,
                    default => null,
                };
            }

            public function setDashboard(string $dashboardControllerFqcn): void
            {
                // 测试环境不需要实际实现Dashboard设置
            }
        };

        self::getContainer()->set(LinkGeneratorInterface::class, $linkGenerator);
    }

    protected function getMenuService(): AdminMenu
    {
        return self::getService(AdminMenu::class);
    }

    public function testAdminMenuCreatesCmsCollectMenuWithCorrectItems(): void
    {
        // 创建真实的菜单项
        $factory = new \Knp\Menu\MenuFactory();
        $rootItem = new \Knp\Menu\MenuItem('root', $factory);

        // 执行菜单构建
        $this->getMenuService()($rootItem);

        // 验证内容管理菜单被创建
        $cmsMenu = $rootItem->getChild('内容管理');
        $this->assertInstanceOf(ItemInterface::class, $cmsMenu);

        // 验证收藏记录子菜单被正确添加
        $collectLogItem = $cmsMenu->getChild('收藏记录');
        $this->assertInstanceOf(ItemInterface::class, $collectLogItem);
        $this->assertSame('/admin/collectlog', $collectLogItem->getUri());
        $this->assertSame('fas fa-heart', $collectLogItem->getAttribute('icon'));
    }

    public function testAdminMenuDoesNotCreateDuplicateCmsMenu(): void
    {
        // 创建真实的菜单项
        $factory = new \Knp\Menu\MenuFactory();
        $rootItem = new \Knp\Menu\MenuItem('root', $factory);
        $existingCmsMenu = $rootItem->addChild('内容管理');

        // 执行菜单构建
        $this->getMenuService()($rootItem);

        // 验证使用了现有的内容管理菜单
        $cmsMenu = $rootItem->getChild('内容管理');
        $this->assertSame($existingCmsMenu, $cmsMenu);

        // 验证收藏记录子菜单被正确添加
        $collectLogItem = $cmsMenu->getChild('收藏记录');
        $this->assertInstanceOf(ItemInterface::class, $collectLogItem);
        $this->assertSame('/admin/collectlog', $collectLogItem->getUri());
        $this->assertSame('fas fa-heart', $collectLogItem->getAttribute('icon'));
    }
}
