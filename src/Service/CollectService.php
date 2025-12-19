<?php

declare(strict_types=1);

namespace Tourze\CmsCollectBundle\Service;

use Tourze\CmsBundle\Entity\Entity;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\CmsCollectBundle\Repository\CollectLogRepository;

/**
 * 收藏服务类
 *
 * 为其他模块提供收藏查询功能，避免直接调用 Repository
 */
final readonly class CollectService
{
    public function __construct(
        private CollectLogRepository $collectLogRepository,
    ) {
    }

    public function isCollectedByUser(Entity $entity, UserInterface $user): bool
    {
        return null !== $this->collectLogRepository->findOneBy([
            'entity' => $entity,
            'user' => $user,
            'valid' => true,
        ]);
    }
}
