<?php

declare(strict_types=1);

namespace Tourze\CmsCollectBundle\Param;

use Symfony\Component\Validator\Constraints as Assert;
use Tourze\JsonRPC\Core\Attribute\MethodParam;
use Tourze\JsonRPC\Core\Contracts\RpcParamInterface;

/**
 * CollectCmsEntity Procedure 的参数对象
 *
 * 用于收藏/反收藏指定文章的请求参数
 */
final readonly class CollectCmsEntityParam implements RpcParamInterface
{
    public function __construct(
        #[MethodParam(description: '文章ID')]
        #[Assert\NotBlank]
        #[Assert\Positive]
        public int $entityId,
    ) {
    }
}
