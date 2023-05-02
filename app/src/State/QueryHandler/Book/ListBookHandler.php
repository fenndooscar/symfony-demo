<?php

declare(strict_types = 1);

namespace App\State\QueryHandler\Book;

use App\Entity\Book;
use App\Mapper\BookMapper;
use App\Repository\BookRepositoryInterface;
use App\State\Query\Book\ListBooksQuery;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

#[AsMessageHandler]
readonly class ListBookHandler
{
    public function __construct(
        private BookRepositoryInterface $bookRepository,
        private BookMapper $mapper
    ) {
    }

    /**
     * @throws ExceptionInterface
     *
     * @return array<string, mixed>
     */
    public function __invoke(ListBooksQuery $query): array
    {
        $criteria = [];
        /** @var iterable<int, Book> $books */
        $books = $this->bookRepository->findBy($criteria, null, $query->limit, $query->offset);

        return [
            'books' => $this->mapper->many($books),
            'total' => $this->bookRepository->findBy($criteria),
        ];
    }
}
