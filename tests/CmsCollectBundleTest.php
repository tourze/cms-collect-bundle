<?php

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
}
