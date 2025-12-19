# CMS Collect Bundle

[![PHP Version](https://img.shields.io/badge/php-%5E8.1-blue.svg)](https://php.net/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![Build Status](https://img.shields.io/badge/build-passing-brightgreen.svg)](#)
[![Code Coverage](https://img.shields.io/badge/coverage-100%25-brightgreen.svg)](#)

[English](README.md) | [中文](README.zh-CN.md)

A Symfony bundle that provides user content collection functionality for CMS systems.

## Features

- User content collection/uncollection
- Collection log tracking with user information
- JSON-RPC API endpoint for collection operations
- Event system for collection actions
- Doctrine ORM integration
- IP tracking and timestamp support

## Requirements

- PHP 8.1+
- Symfony 6.4+
- Doctrine ORM 3.0+

## Installation

```bash
composer require tourze/cms-collect-bundle
```

## Configuration

### Enable the Bundle

Add the bundle to your `config/bundles.php`:

```php
return [
    // ...
    Tourze\CmsCollectBundle\CmsCollectBundle::class => ['all' => true],
];
```

### Database Setup

Run the doctrine migrations to create the required tables:

```bash
php bin/console doctrine:migrations:migrate
```

## Usage

### JSON-RPC API

The bundle provides a JSON-RPC procedure for collection operations:

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

**Response:**
```json
{
    "jsonrpc": "2.0",
    "result": {
        "__message": "Collection successful"
    },
    "id": 1
}
```

### Entity Usage

The `CollectLog` entity tracks user collection actions:

```php
use Tourze\CmsCollectBundle\Entity\CollectLog;

$collectLog = new CollectLog();
$collectLog->setUser($user);
$collectLog->setEntity($entity);
$collectLog->setValid(true);
```

### Repository Usage

Use the repository to query collection logs:

```php
use Tourze\CmsCollectBundle\Repository\CollectLogRepository;

$repository = $entityManager->getRepository(CollectLog::class);
$userCollections = $repository->findBy(['user' => $user, 'valid' => true]);
```

## Advanced Usage

### Custom Event Listeners

Listen to collection events for custom functionality:

```php
use Tourze\CmsBundle\Event\CollectEntityEvent;
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
        // Custom logic for collection changes
        $entity = $event->getEntity();
        $user = $event->getSender();
        
        // Log the collection action
        // Send notifications
        // Update statistics
    }
}
```

### Query Collections by User

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

## Events

The bundle dispatches `CollectEntityEvent` when collection state changes:

```php
use Tourze\CmsBundle\Event\CollectEntityEvent;

$event = new CollectEntityEvent();
$event->setSender($user);
$event->setEntity($entity);
$event->setMessage('Collected content: ' . $entity->getTitle());
```

## Security

This bundle requires user authentication through Symfony Security component. 
All collection operations require the `IS_AUTHENTICATED_FULLY` attribute.

## Testing

```bash
# Run tests
vendor/bin/phpunit packages/cms-collect-bundle/tests

# Run static analysis
vendor/bin/phpstan analyse packages/cms-collect-bundle
```

## License

MIT