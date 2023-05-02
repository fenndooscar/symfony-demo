<?php

declare(strict_types = 1);

namespace App\Repository\ORM;

use App\Entity\Book;
use App\Repository\BookRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;

use function array_column;
use function count;
use function in_array;
use function is_null;

class BookRepository extends ServiceEntityRepository implements BookRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function save(?Book $entity, bool $flush = false): void
    {
        if (!is_null($entity)) {
            $this->getEntityManager()->persist($entity);
        }

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(?Book $entity, bool $flush = false): void
    {
        if (!is_null($entity)) {
            $this->getEntityManager()->remove($entity);
        }

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByIdOrNull(int $id): ?Book
    {
        /** @var Book|null $book */
        $book = $this->findOneBy(['id' => $id]);

        return $book;
    }

    /**
     * @throws EntityNotFoundException
     */
    public function findOneById(int $id): Book
    {
        $book = $this->findOneByIdOrNull($id);

        if (!$book instanceof Book) {
            throw EntityNotFoundException::fromClassNameAndIdentifier(Book::class, [(string) $id]);
        }

        return $book;
    }

    /**
     * @param int[] $ids
     *
     * @throws EntityNotFoundException
     *
     * @return array<Book>
     */
    public function findByIds(array $ids, bool $throw = true): array
    {
        if (empty($ids)) {
            return [];
        }

        /** @var array<Book> $books */
        $books = $this
            ->createQueryBuilder('book', 'book.id')
            ->where('book.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();

        if (true === $throw && count($books) !== count($ids)) {
            $filtered = [];
            $founded  = array_column($books, 'id');

            foreach ($ids as $id) {
                if (in_array($id, $founded, true)) {
                    continue;
                }

                $filtered[] = (string) $id;
            }

            throw EntityNotFoundException::fromClassNameAndIdentifier(Book::class, $filtered);
        }

        return $books;
    }
}
