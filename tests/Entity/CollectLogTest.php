<?php

namespace Tourze\CmsCollectBundle\Tests\Entity;

use CmsBundle\Entity\Entity;
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
    protected function createEntity(): CollectLog
    {
        return new CollectLog();
    }

    /**
     * 提供属性及其样本值的 Data Provider.
     *
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        yield 'valid' => ['valid', true];
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
        $entity = new Entity();

        $log->setEntity($entity);
        $this->assertSame($entity, $log->getEntity());
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
