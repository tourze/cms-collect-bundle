<?php

declare(strict_types=1);

namespace Tourze\CmsCollectBundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\CmsCollectBundle\Service\CollectService;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;

/**
 * @internal
 */
#[CoversClass(CollectService::class)]
#[RunTestsInSeparateProcesses]
final class CollectServiceTest extends AbstractIntegrationTestCase
{
    protected function onSetUp(): void
    {
    }

    public function testServiceCanBeInstantiated(): void
    {
        $service = self::getService(CollectService::class);
        $this->assertInstanceOf(CollectService::class, $service);
    }

    public function testServiceHasCorrectDependencies(): void
    {
        $service = self::getService(CollectService::class);

        $reflection = new \ReflectionClass($service);

        $this->assertTrue($reflection->hasProperty('collectLogRepository'));
    }

    public function testIsCollectedByUserMethodSignature(): void
    {
        $service = self::getService(CollectService::class);

        $reflection = new \ReflectionClass($service);
        $method = $reflection->getMethod('isCollectedByUser');

        $this->assertEquals('isCollectedByUser', $method->getName());
        $this->assertTrue($method->isPublic());
        $this->assertEquals(2, $method->getNumberOfParameters());

        $parameters = $method->getParameters();
        $this->assertEquals('entity', $parameters[0]->getName());
        $this->assertTrue($parameters[0]->getType() instanceof \ReflectionNamedType);
        $this->assertEquals('Tourze\CmsBundle\Entity\Entity', $parameters[0]->getType()->getName());

        $this->assertEquals('user', $parameters[1]->getName());
        $this->assertTrue($parameters[1]->getType() instanceof \ReflectionNamedType);
        $this->assertEquals('Symfony\Component\Security\Core\User\UserInterface', $parameters[1]->getType()->getName());

        $returnType = $method->getReturnType();
        $this->assertNotNull($returnType);
        $this->assertInstanceOf(\ReflectionNamedType::class, $returnType);
        $this->assertEquals('bool', $returnType->getName());
    }
}
