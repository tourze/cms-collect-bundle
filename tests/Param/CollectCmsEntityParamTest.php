<?php

declare(strict_types=1);

namespace Tourze\CmsCollectBundle\Tests\Param;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\CmsCollectBundle\Param\CollectCmsEntityParam;
use Tourze\JsonRPC\Core\Contracts\RpcParamInterface;
use Symfony\Component\Validator\Validation;

/**
 * CollectCmsEntityParam 的单元测试
 */
#[CoversClass(CollectCmsEntityParam::class)]
final class CollectCmsEntityParamTest extends TestCase
{
    public function testImplementsRpcParamInterface(): void
    {
        $param = new CollectCmsEntityParam(123);
        $this->assertInstanceOf(RpcParamInterface::class, $param);
    }

    public function testValidParam(): void
    {
        $param = new CollectCmsEntityParam(123);
        $this->assertSame(123, $param->entityId);
    }

    public function testParamIsReadonly(): void
    {
        $param = new CollectCmsEntityParam(789);

        // 验证属性是只读的
        $this->assertSame(789, $param->entityId);

        // 尝试修改会抛出错误（PHP readonly属性）
        $this->expectException(\Error::class);
        $param->entityId = 999;
    }

    public function testClassIsReadonly(): void
    {
        $reflection = new \ReflectionClass(CollectCmsEntityParam::class);
        $this->assertTrue($reflection->isReadOnly());
    }

    public function testClassIsFinal(): void
    {
        $reflection = new \ReflectionClass(CollectCmsEntityParam::class);
        $this->assertTrue($reflection->isFinal());
    }

    public function testValidationFailsWhenEntityIdIsZero(): void
    {
        $param = new CollectCmsEntityParam(0);

        $validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();

        $violations = $validator->validate($param);
        $this->assertGreaterThan(0, count($violations));
    }

    public function testValidationFailsWhenEntityIdIsNegative(): void
    {
        $param = new CollectCmsEntityParam(-1);

        $validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();

        $violations = $validator->validate($param);
        $this->assertGreaterThan(0, count($violations));
    }

    public function testValidationPassesWithValidEntityId(): void
    {
        $param = new CollectCmsEntityParam(789);

        $validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();

        $violations = $validator->validate($param);
        $this->assertCount(0, $violations);
    }
}