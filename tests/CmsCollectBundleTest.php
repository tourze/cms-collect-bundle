<?php

declare(strict_types=1);

namespace Tourze\CmsCollectBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\CmsCollectBundle\CmsCollectBundle;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;

/**
 * @internal
 */
#[CoversClass(CmsCollectBundle::class)]
#[RunTestsInSeparateProcesses]
final class CmsCollectBundleTest extends AbstractBundleTestCase
{
    protected function onSetUp(): void
    {
        // 不需要额外的初始化逻辑
    }

    // 基类会自动运行所有必要的测试
}
