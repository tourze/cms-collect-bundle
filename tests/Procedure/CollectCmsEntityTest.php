<?php

declare(strict_types=1);

namespace Tourze\CmsCollectBundle\Tests\Procedure;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\CmsCollectBundle\Procedure\CollectCmsEntity;
use Tourze\PHPUnitJsonRPC\AbstractProcedureTestCase;

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

    public function testExecuteMethodExists(): void
    {
        $procedure = self::getService(CollectCmsEntity::class);
        $this->assertTrue(method_exists($procedure, 'execute'));
        $this->assertTrue((new \ReflectionMethod($procedure, 'execute'))->isPublic());
    }
}
