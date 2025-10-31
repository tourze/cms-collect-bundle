<?php

declare(strict_types=1);

namespace Tourze\CmsCollectBundle\DataFixtures;

use Carbon\CarbonImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use Tourze\CmsCollectBundle\Entity\CollectLog;

/**
 * 收藏记录数据填充
 *
 * 创建测试用的收藏记录数据
 * 只在 test 和 dev 环境中加载
 */
#[When(env: 'test')]
#[When(env: 'dev')]
class CollectLogFixtures extends Fixture implements FixtureGroupInterface
{
    public const COLLECT_LOG_REFERENCE_PREFIX = 'collect-log-';
    public const COLLECT_LOG_COUNT = 20;

    public function load(ObjectManager $manager): void
    {
        // 创建收藏记录
        for ($i = 0; $i < self::COLLECT_LOG_COUNT; ++$i) {
            $collectLog = new CollectLog();

            // 设置随机的有效状态 (80% 概率为有效)
            $collectLog->setValid(mt_rand(1, 100) <= 80);

            // 设置时间
            $daysAgo = mt_rand(1, 365);
            $createTime = CarbonImmutable::now()->modify("-{$daysAgo} days");
            $collectLog->setCreateTime($createTime);

            $updateDaysOffset = mt_rand(0, 30);
            $collectLog->setUpdateTime($createTime->modify("+{$updateDaysOffset} days"));

            $manager->persist($collectLog);
            $this->addReference(self::COLLECT_LOG_REFERENCE_PREFIX . $i, $collectLog);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return [
            'cms',
        ];
    }
}
