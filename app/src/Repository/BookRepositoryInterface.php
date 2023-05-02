<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Book;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ObjectRepository;

/**
 * @template T of object
 */
interface BookRepositoryInterface extends ObjectRepository
{
    public function save(?Book $entity, bool $flush = false): void;

    public function remove(?Book $entity, bool $flush = false): void;

    public function findOneByIdOrNull(int $id): ?Book;

    /**
     * @throws EntityNotFoundException
     */
    public function findOneById(int $id): Book;

    /**
     * @param int[] $ids
     *
     * @throws EntityNotFoundException
     *
     * @return array<Book>
     */
    public function findByIds(array $ids, bool $throw = true): array;
}
