<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Author;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ObjectRepository;

interface AuthorRepositoryInterface extends ObjectRepository
{
    public function save(?Author $entity, bool $flush = false): void;

    public function remove(?Author $entity, bool $flush = false): void;

    public function findOneByIdOrNull(int $id): ?Author;

    /**
     * @throws EntityNotFoundException
     */
    public function findOneById(int $id): Author;

    /**
     * @param int[] $ids
     *
     * @throws EntityNotFoundException
     *
     * @return array<Author>
     */
    public function findByIds(array $ids, bool $throw = true): array;

    /**
     * Counts entities by a set of criteria.
     *
     * @psalm-param array<string, mixed> $criteria
     *
     * @return int the cardinality of the objects that match the given criteria
     */
    public function count(array $criteria): int;
}
