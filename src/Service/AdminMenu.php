<?php

declare(strict_types=1);

namespace Tourze\CmsCollectBundle\Service;

use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\CmsCollectBundle\Entity\CollectLog;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface;

#[Autoconfigure(public: true)]
readonly class AdminMenu implements MenuProviderInterface
{
    public function __construct(private LinkGeneratorInterface $linkGenerator)
    {
    }

    public function __invoke(ItemInterface $item): void
    {
        $childItem = $item->getChild('内容管理');
        if (null === $childItem) {
            $childItem = $item->addChild('内容管理');
        }

        $childItem
            ->addChild('收藏记录')
            ->setUri($this->linkGenerator->getCurdListPage(CollectLog::class))
            ->setAttribute('icon', 'fas fa-heart')
        ;
    }
}
