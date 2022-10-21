<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\Mapping\MappingException;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;

abstract class BaseRepository
{
    protected ManagerRegistry $managerRegistry;
    protected Connection $connection;
    protected ObjectRepository $objectRepository;

    /**
     * @param ManagerRegistry $managerRegistry
     * @param Connection $connection
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        Connection $connection
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->connection = $connection;
        $this->objectRepository = $this->getEntityManager()->getRepository(self::entityClass());
    }

    protected abstract static function entityClass(): string;

    /**
     * @param object $entity
     * @return void
     * @throws ORMException
     */
    public function persistEntity(object $entity): void
    {
        $this->getEntityManager()->persist($entity);
    }

    /**
     * @return void
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws MappingException
     */
    public function flushData(): void
    {
        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear();
    }

    /**
     * @param object $entity
     * @return void
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveEntity(object $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * @param object $entity
     * @return void
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function removeEntity(object $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * @param string $query
     * @param array $params
     * @return array
     * @throws Exception
     */
    protected function executeFetchQuery(string $query, array $params = []): array
    {
        return $this->connection->executeQuery($query, $params)->fetchAllAssociative();
    }

    /**
     * @param string $query
     * @param array|null $params
     * @return void
     * @throws Exception
     */
    protected function executeQuery(string $query, ?array $params = null): void
    {
        $this->connection->executeQuery($query, $params);
    }

    /**
     * @return EntityManager|ObjectManager
     */
    private function getEntityManager()
    {
        $entityManager = $this->getEntityManager();

        if ($entityManager->isOpen()) {
            return $entityManager;
        }

        return $this->managerRegistry->resetManager();
    }
}