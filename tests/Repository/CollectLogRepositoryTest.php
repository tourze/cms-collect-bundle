<?php

namespace Tourze\CmsCollectBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\CmsCollectBundle\Entity\CollectLog;
use Tourze\CmsCollectBundle\Repository\CollectLogRepository;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;

/**
 * @internal
 */
#[CoversClass(CollectLogRepository::class)]
#[RunTestsInSeparateProcesses]
final class CollectLogRepositoryTest extends AbstractRepositoryTestCase
{
    private CollectLogRepository $repository;

    private UserInterface $testUser;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(CollectLogRepository::class);
        $this->testUser = $this->createNormalUser('test@example.com', 'password123');
    }

    public function testSaveAndRemoveCollectLog(): void
    {
        $collectLog = new CollectLog();
        $collectLog->setUser($this->testUser);
        $collectLog->setEntity(null);
        $collectLog->setValid(true);

        $this->repository->save($collectLog);
        $this->assertNotNull($collectLog->getId());

        $foundLog = $this->repository->find($collectLog->getId());
        $this->assertInstanceOf(CollectLog::class, $foundLog);
        $this->assertTrue($foundLog->isValid());

        $savedId = $collectLog->getId();
        $this->repository->remove($collectLog);
        $removedLog = $this->repository->find($savedId);
        $this->assertNull($removedLog);
    }

    public function testSaveWithoutFlush(): void
    {
        $collectLog = new CollectLog();
        $collectLog->setUser($this->testUser);
        $collectLog->setEntity(null);
        $collectLog->setValid(true);

        $this->repository->save($collectLog, false);
        $this->assertNotNull($collectLog->getId());

        // 手动刷新以确保持久化
        self::getEntityManager()->flush();

        $foundLog = $this->repository->find($collectLog->getId());
        $this->assertInstanceOf(CollectLog::class, $foundLog);
    }

    public function testFindOneByShouldRespectOrderByClause(): void
    {
        $collectLog1 = new CollectLog();
        $collectLog1->setUser($this->testUser);
        $collectLog1->setEntity(null);
        $collectLog1->setValid(false);
        $this->repository->save($collectLog1);

        $entity2Id = 'entity-2-' . uniqid();
        $collectLog2 = new CollectLog();
        $collectLog2->setUser($this->testUser);
        $collectLog2->setEntity(null);
        $collectLog2->setValid(true);
        $this->repository->save($collectLog2);

        $result = $this->repository->findOneBy(['user' => $this->testUser], ['valid' => 'DESC']);
        $this->assertInstanceOf(CollectLog::class, $result);
        $this->assertTrue($result->isValid());
    }

    public function testQueryWithUserAssociation(): void
    {
        $collectLog = new CollectLog();
        $collectLog->setUser($this->testUser);
        $collectLog->setEntity(null);
        $collectLog->setValid(true);
        $this->repository->save($collectLog);

        $results = $this->repository->findBy(['user' => $this->testUser]);
        $this->assertIsArray($results);
        $this->assertNotEmpty($results);
        $user = $results[0]->getUser();
        $this->assertNotNull($user);
        $this->assertEquals($this->testUser->getUserIdentifier(), $user->getUserIdentifier());
    }

    public function testQueryWithEntityAssociation(): void
    {
        $collectLog = new CollectLog();
        $collectLog->setUser($this->testUser);
        $collectLog->setEntity(null);
        $collectLog->setValid(true);
        $this->repository->save($collectLog);

        $results = $this->repository->findBy(['entity' => null]);
        $this->assertIsArray($results);
        $this->assertNotEmpty($results);
        $this->assertNull($results[0]->getEntity());
    }

    public function testCountWithUserAssociation(): void
    {
        $collectLog = new CollectLog();
        $collectLog->setUser($this->testUser);
        $collectLog->setEntity(null);
        $collectLog->setValid(true);
        $this->repository->save($collectLog);

        $count = $this->repository->count(['user' => $this->testUser]);
        $this->assertIsInt($count);
        $this->assertGreaterThanOrEqual(1, $count);
    }

    public function testCountWithEntityAssociation(): void
    {
        $collectLog = new CollectLog();
        $collectLog->setUser($this->testUser);
        $collectLog->setEntity(null);
        $collectLog->setValid(true);
        $this->repository->save($collectLog);

        $count = $this->repository->count(['entity' => null]);
        $this->assertIsInt($count);
        $this->assertGreaterThanOrEqual(1, $count);
    }

    public function testFindByWithNullValidField(): void
    {
        $collectLog = new CollectLog();
        $collectLog->setUser($this->testUser);
        $collectLog->setEntity(null);
        $collectLog->setValid(null);
        $this->repository->save($collectLog);

        $results = $this->repository->findBy(['valid' => null]);
        $this->assertIsArray($results);
        $this->assertNotEmpty($results);
        $this->assertNull($results[0]->isValid());
    }

    public function testFindByWithNullUserField(): void
    {
        $collectLog = new CollectLog();
        $collectLog->setUser(null);
        $collectLog->setEntity(null);
        $collectLog->setValid(true);
        $this->repository->save($collectLog);

        $results = $this->repository->findBy(['user' => null]);
        $this->assertIsArray($results);
        $this->assertNotEmpty($results);
        $this->assertNull($results[0]->getUser());
    }

    public function testCountWithNullValidField(): void
    {
        $collectLog = new CollectLog();
        $collectLog->setUser($this->testUser);
        $collectLog->setEntity(null);
        $collectLog->setValid(null);
        $this->repository->save($collectLog);

        $count = $this->repository->count(['valid' => null]);
        $this->assertIsInt($count);
        $this->assertGreaterThanOrEqual(1, $count);
    }

    public function testCountWithNullUserField(): void
    {
        $collectLog = new CollectLog();
        $collectLog->setUser(null);
        $collectLog->setEntity(null);
        $collectLog->setValid(true);
        $this->repository->save($collectLog);

        $count = $this->repository->count(['user' => null]);
        $this->assertIsInt($count);
        $this->assertGreaterThanOrEqual(1, $count);
    }

    public function testRemoveMethod(): void
    {
        $collectLog = new CollectLog();
        $collectLog->setUser($this->testUser);
        $collectLog->setEntity(null);
        $collectLog->setValid(true);
        $this->repository->save($collectLog);

        $savedId = $collectLog->getId();
        $this->assertNotNull($savedId);

        $this->repository->remove($collectLog);
        $removedLog = $this->repository->find($savedId);
        $this->assertNull($removedLog);
    }

    public function testFindOneByAssociationUserShouldReturnMatchingEntity(): void
    {
        $collectLog = new CollectLog();
        $collectLog->setUser($this->testUser);
        $collectLog->setEntity(null);
        $collectLog->setValid(true);
        $this->repository->save($collectLog);

        $result = $this->repository->findOneBy(['user' => $this->testUser]);
        $this->assertInstanceOf(CollectLog::class, $result);
        $user = $result->getUser();
        $this->assertNotNull($user);
        $this->assertEquals($this->testUser->getUserIdentifier(), $user->getUserIdentifier());
    }

    public function testFindOneByAssociationEntityShouldReturnMatchingEntity(): void
    {
        $collectLog = new CollectLog();
        $collectLog->setUser($this->testUser);
        $collectLog->setEntity(null);
        $collectLog->setValid(true);
        $this->repository->save($collectLog);

        $result = $this->repository->findOneBy(['entity' => null]);
        $this->assertInstanceOf(CollectLog::class, $result);
        $this->assertNull($result->getEntity());
    }

    public function testCountByAssociationUserShouldReturnCorrectNumber(): void
    {
        $collectLog = new CollectLog();
        $collectLog->setUser($this->testUser);
        $collectLog->setEntity(null);
        $collectLog->setValid(true);
        $this->repository->save($collectLog);

        $count = $this->repository->count(['user' => $this->testUser]);
        $this->assertIsInt($count);
        $this->assertGreaterThanOrEqual(1, $count);
    }

    /**
     * @return ServiceEntityRepository<CollectLog>
     */
    protected function getRepository(): ServiceEntityRepository
    {
        return $this->repository;
    }

    protected function createNewEntity(): object
    {
        $collectLog = new CollectLog();
        // 创建唯一的用户和实体组合以避免唯一约束冲突
        $uniqueUser = $this->createNormalUser('test' . uniqid() . '@example.com', 'password123');
        $uniqueEntityId = 'entity-' . uniqid();
        $collectLog->setUser($uniqueUser);
        $collectLog->setEntity(null);
        $collectLog->setValid(true);

        return $collectLog;
    }
}
