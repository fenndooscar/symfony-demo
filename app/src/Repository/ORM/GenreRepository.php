<?php

declare(strict_types = 1);

namespace App\Repository\ORM;

use App\Entity\Genre;
use App\Repository\GenreRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;

use function array_column;
use function count;
use function in_array;
use function is_null;

class GenreRepository extends ServiceEntityRepository implements GenreRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Genre::class);
    }

    public function save(?Genre $entity, bool $flush = false): void
    {
        if (!is_null($entity)) {
            $this->getEntityManager()->persist($entity);
        }

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(?Genre $entity, bool $flush = false): void
    {
        if (!is_null($entity)) {
            $this->getEntityManager()->remove($entity);
        }

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByIdOrNull(int $id): ?Genre
    {
        /** @var Genre|null $genre */
        $genre = $this->findOneBy(['id' => $id]);

        return $genre;
    }

    /**
     * @throws EntityNotFoundException
     */
    public function findOneById(int $id): Genre
    {
        $genre = $this->findOneByIdOrNull($id);

        if (!$genre instanceof Genre) {
            throw EntityNotFoundException::fromClassNameAndIdentifier(Genre::class, [(string) $id]);
        }

        return $genre;
    }

    /**
     * @param int[] $ids
     *
     * @throws EntityNotFoundException
     *
     * @return array<Genre>
     */
    public function findByIds(array $ids, bool $throw = true): array
    {
        if (empty($ids)) {
            return [];
        }

        $genres = $this->findBy(['id' => $ids]);

        if (true === $throw && count($genres) !== count($ids)) {
            $filtered = [];
            $founded  = array_column($genres, 'id');

            foreach ($ids as $id) {
                if (in_array($id, $founded, true)) {
                    continue;
                }

                $filtered[] = (string) $id;
            }

            throw EntityNotFoundException::fromClassNameAndIdentifier(Genre::class, $filtered);
        }

        return $genres;
    }
}
