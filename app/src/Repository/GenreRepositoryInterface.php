<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Genre;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ObjectRepository;

interface GenreRepositoryInterface extends ObjectRepository
{
    public function save(?Genre $entity, bool $flush = false): void;

    public function remove(?Genre $entity, bool $flush = false): void;

    public function findOneByIdOrNull(int $id): ?Genre;

    /**
     * @throws EntityNotFoundException
     */
    public function findOneById(int $id): Genre;

    /**
     * @param int[] $ids
     *
     * @throws EntityNotFoundException
     *
     * @return array<Genre>
     */
    public function findByIds(array $ids, bool $throw = true): array;
}
