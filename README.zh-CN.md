# CMS 收藏包

[![PHP Version](https://img.shields.io/badge/php-%5E8.1-blue.svg)](https://php.net/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![Build Status](https://img.shields.io/badge/build-passing-brightgreen.svg)](#)
[![Code Coverage](https://img.shields.io/badge/coverage-100%25-brightgreen.svg)](#)

[English](README.md) | [中文](README.zh-CN.md)

一个为 CMS 系统提供用户内容收藏功能的 Symfony 包。

## 功能特性

- 用户内容收藏/取消收藏
- 收藏日志跟踪，包含用户信息
- JSON-RPC API 端点用于收藏操作
- 收藏动作事件系统
- Doctrine ORM 集成
- IP 跟踪和时间戳支持

## 系统要求

- PHP 8.1+
- Symfony 6.4+
- Doctrine ORM 3.0+

## 安装

```bash
composer require tourze/cms-collect-bundle
```

## 配置

### 启用包

将包添加到您的 `config/bundles.php` 文件：

```php
return [
    // ...
    Tourze\CmsCollectBundle\CmsCollectBundle::class => ['all' => true],
];
```

### 数据库设置

运行 doctrine 迁移以创建所需的表：

```bash
php bin/console doctrine:migrations:migrate
```

## 使用方法

### JSON-RPC API

包提供了一个 JSON-RPC 过程用于收藏操作：

```json
{
    "jsonrpc": "2.0",
    "method": "CollectCmsEntity",
    "params": {
        "entityId": 123
    },
    "id": 1
}
```

**响应：**
```json
{
    "jsonrpc": "2.0",
    "result": {
        "__message": "收藏成功"
    },
    "id": 1
}
```

### 实体使用

`CollectLog` 实体跟踪用户收藏动作：

```php
use Tourze\CmsCollectBundle\Entity\CollectLog;

$collectLog = new CollectLog();
$collectLog->setUser($user);
$collectLog->setEntity($entity);
$collectLog->setValid(true);
```

### 仓储使用

使用仓储查询收藏日志：

```php
use Tourze\CmsCollectBundle\Repository\CollectLogRepository;

$repository = $entityManager->getRepository(CollectLog::class);
$userCollections = $repository->findBy(['user' => $user, 'valid' => true]);
```

## 高级用法

### 自定义事件监听器

监听收藏事件以实现自定义功能：

```php
use CmsBundle\Event\CollectEntityEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CollectionEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            CollectEntityEvent::class => 'onCollectionChange',
        ];
    }

    public function onCollectionChange(CollectEntityEvent $event): void
    {
        // 收藏变化的自定义逻辑
        $entity = $event->getEntity();
        $user = $event->getSender();
        
        // 记录收藏动作
        // 发送通知
        // 更新统计数据
    }
}
```

### 按用户查询收藏

```php
use Tourze\CmsCollectBundle\Repository\CollectLogRepository;

class UserCollectionService
{
    public function __construct(
        private CollectLogRepository $collectLogRepository
    ) {}

    public function getUserCollections(UserInterface $user): array
    {
        return $this->collectLogRepository->findBy([
            'user' => $user,
            'valid' => true,
        ], ['createTime' => 'DESC']);
    }

    public function isEntityCollectedByUser($entity, UserInterface $user): bool
    {
        $log = $this->collectLogRepository->findOneBy([
            'entity' => $entity,
            'user' => $user,
        ]);

        return $log && $log->isValid();
    }
}
```

## 事件

包在收藏状态变化时调度 `CollectEntityEvent`：

```php
use CmsBundle\Event\CollectEntityEvent;

$event = new CollectEntityEvent();
$event->setSender($user);
$event->setEntity($entity);
$event->setMessage('收藏内容：' . $entity->getTitle());
```

## 安全

该包需要通过 Symfony Security 组件进行用户认证。所有收藏操作都需要 `IS_AUTHENTICATED_FULLY` 属性。

## 测试

```bash
# 运行测试
vendor/bin/phpunit packages/cms-collect-bundle/tests

# 运行静态分析
vendor/bin/phpstan analyse packages/cms-collect-bundle
```

## 许可证

MIT
