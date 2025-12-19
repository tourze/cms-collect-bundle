<?php

declare(strict_types=1);

namespace Tourze\CmsCollectBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\CmsCollectBundle\DependencyInjection\CmsCollectExtension;
use Tourze\PHPUnitSymfonyUnitTest\AbstractDependencyInjectionExtensionTestCase;

/**
 * @internal
 */
#[CoversClass(CmsCollectExtension::class)]
final class CmsCollectExtensionTest extends AbstractDependencyInjectionExtensionTestCase
{
    protected function onSetUp(): void
    {
        // 不需要额外的初始化逻辑
    }

    // 基类会自动运行所有必要的测试
}
