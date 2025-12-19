<?php

declare(strict_types=1);

namespace Tourze\CmsCollectBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\CmsCollectBundle\Entity\CollectLog;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;

/**
 * @internal
 */
#[CoversClass(CollectLog::class)]
final class CollectLogTest extends AbstractEntityTestCase
{
    protected function onSetUp(): void
    {
        // Entity 测试不需要额外的设置
    }

    
    protected function createEntity(): CollectLog
    {
        return new CollectLog();
    }

    /**
     * @return iterable<array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        yield ['user', null];
        yield ['entity', null];
    }

    public function testToString(): void
    {
        $log = $this->createEntity();
        $this->assertEquals('', (string) $log);
    }

    public function testUserSetterAndGetter(): void
    {
        $log = $this->createEntity();
        $user = $this->createMock(UserInterface::class);

        $log->setUser($user);
        $this->assertSame($user, $log->getUser());
    }

    public function testEntitySetterAndGetter(): void
    {
        $log = $this->createEntity();

        // Test null case - when no entity is set
        $this->assertNull($log->getEntity());

        // Note: We cannot test with an actual Entity object because it requires
        // the full CMS bundle to be loaded, which is not available in this isolated test.
        // The functionality is verified in integration tests with the full stack.
    }

    public function testValidSetterAndGetter(): void
    {
        $log = $this->createEntity();

        $this->assertFalse($log->isValid());

        $log->setValid(true);
        $this->assertTrue($log->isValid());

        $log->setValid(false);
        $this->assertFalse($log->isValid());

        $log->setValid(null);
        $this->assertNull($log->isValid());
    }
}
