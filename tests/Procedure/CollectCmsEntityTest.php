<?php

namespace Tourze\CmsCollectBundle\Tests\Procedure;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\CmsCollectBundle\Procedure\CollectCmsEntity;
use Tourze\JsonRPC\Core\Tests\AbstractProcedureTestCase;

/**
 * @internal
 */
#[CoversClass(CollectCmsEntity::class)]
#[RunTestsInSeparateProcesses]
final class CollectCmsEntityTest extends AbstractProcedureTestCase
{
    protected function onSetUp(): void
    {
        // 不需要额外的初始化逻辑
    }

    public function testGetMockResult(): void
    {
        $mockResult = CollectCmsEntity::getMockResult();

        $this->assertIsArray($mockResult);
        $this->assertArrayHasKey('__message', $mockResult);
        $this->assertContains($mockResult['__message'], ['收藏成功', '已取消收藏']);
    }

    public function testServiceIsAccessible(): void
    {
        $service = self::getService(CollectCmsEntity::class);
        $this->assertInstanceOf(CollectCmsEntity::class, $service);
    }

    public function testExecuteReturnsArray(): void
    {
        $mockResult = CollectCmsEntity::getMockResult();
        $this->assertIsArray($mockResult);
        $this->assertArrayHasKey('__message', $mockResult);
    }
}
