<?php

declare(strict_types = 1);

namespace App\Repository\ORM;

use App\Entity\Author;
use App\Repository\AuthorRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;

use function array_column;
use function count;
use function in_array;
use function is_null;

class AuthorRepository extends ServiceEntityRepository implements AuthorRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function save(?Author $entity, bool $flush = false): void
    {
        if (!is_null($entity)) {
            $this->getEntityManager()->persist($entity);
        }

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(?Author $entity, bool $flush = false): void
    {
        if (!is_null($entity)) {
            $this->getEntityManager()->remove($entity);
        }

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByIdOrNull(int $id): ?Author
    {
        /** @var Author|null $author */
        $author = $this->findOneBy(['id' => $id]);

        return $author;
    }

    /**
     * @throws EntityNotFoundException
     */
    public function findOneById(int $id): Author
    {
        $author = $this->findOneByIdOrNull($id);

        if (!$author instanceof Author) {
            throw EntityNotFoundException::fromClassNameAndIdentifier(Author::class, [(string) $id]);
        }

        return $author;
    }

    /**
     * @param int[] $ids
     *
     * @throws EntityNotFoundException
     *
     * @return array<Author>
     */
    public function findByIds(array $ids, bool $throw = true): array
    {
        if (empty($ids)) {
            return [];
        }

        /** @var array<Author> $authors */
        $authors = $this
            ->createQueryBuilder('author', 'author.id')
            ->where('author.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();

        if (true === $throw && count($authors) !== count($ids)) {
            $filtered = [];
            $founded  = array_column($authors, 'id');

            foreach ($ids as $id) {
                if (in_array($id, $founded, true)) {
                    continue;
                }

                $filtered[] = (string) $id;
            }

            throw EntityNotFoundException::fromClassNameAndIdentifier(Author::class, $filtered);
        }

        return $authors;
    }

    /**
     * Counts entities by a set of criteria.
     *
     * @psalm-param array<string, mixed> $criteria
     *
     * @return int the cardinality of the objects that match the given criteria
     */
    public function count(array $criteria): int
    {
        return parent::count($criteria);
    }
}
