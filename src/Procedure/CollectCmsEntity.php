<?php

declare(strict_types=1);

namespace Tourze\CmsCollectBundle\Procedure;

use Tourze\CmsBundle\Enum\EntityState;
use Tourze\CmsBundle\Event\CollectEntityEvent;
use Tourze\CmsBundle\Service\EntityService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Tourze\CmsCollectBundle\Entity\CollectLog;
use Tourze\CmsCollectBundle\Param\CollectCmsEntityParam;
use Tourze\CmsCollectBundle\Repository\CollectLogRepository;
use Tourze\JsonRPC\Core\Attribute\MethodDoc;
use Tourze\JsonRPC\Core\Attribute\MethodExpose;
use Tourze\JsonRPC\Core\Attribute\MethodTag;
use Tourze\JsonRPC\Core\Contracts\RpcParamInterface;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\JsonRPC\Core\Result\ArrayResult;
use Tourze\JsonRPCLockBundle\Procedure\LockableProcedure;
use Tourze\JsonRPCLogBundle\Attribute\Log;
use Tourze\UserIDBundle\Model\SystemUser;

#[MethodExpose(method: 'CollectCmsEntity')]
#[MethodTag(name: '内容管理')]
#[MethodDoc(summary: '收藏/反收藏指定文章')]
#[IsGranted(attribute: 'IS_AUTHENTICATED_FULLY')]
#[Autoconfigure(public: true)]
#[Log]
final class CollectCmsEntity extends LockableProcedure
{
    public function __construct(
        private readonly EntityService $entityService,
        private readonly CollectLogRepository $collectLogRepository,
        private readonly Security $security,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @phpstan-param CollectCmsEntityParam $param
     */
    public function execute(CollectCmsEntityParam|RpcParamInterface $param): ArrayResult
    {
        $entity = $this->entityService->findEntitiesBy([
            'id' => $param->entityId,
            'state' => EntityState::PUBLISHED,
        ])[0] ?? null;
        if (null === $entity) {
            throw new ApiException('找不到文章');
        }

        $log = $this->collectLogRepository->findOneBy([
            'entity' => $entity,
            'user' => $this->security->getUser(),
        ]);
        if (null === $log) {
            $log = new CollectLog();
            $log->setEntity($entity);
            $log->setUser($this->security->getUser());
        }

        $log->setValid(!($log->isValid() ?? false));
        $this->entityManager->persist($log);
        $this->entityManager->flush();

        $event = new CollectEntityEvent();
        $user = $this->security->getUser();
        if (null !== $user) {
            $event->setSender($user);
        }
        $event->setReceiver(SystemUser::instance());
        $event->setEntity($entity);

        if (true === $log->isValid()) {
            $event->setMessage("收藏内容：{$entity->getTitle()}");
        } else {
            $event->setMessage("取消收藏内容：{$entity->getTitle()}");
        }

        $this->eventDispatcher->dispatch($event);

        return new ArrayResult([
            '__message' => (true === $log->isValid()) ? '收藏成功' : '已取消收藏',
        ]);
    }

    /**
     * @return array<string, mixed>|null
     */
}
