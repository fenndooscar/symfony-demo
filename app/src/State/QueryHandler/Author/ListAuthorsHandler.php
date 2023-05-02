<?php

declare(strict_types = 1);

namespace App\State\QueryHandler\Author;

use App\Entity\Author;
use App\Mapper\AuthorMapper;
use App\Repository\AuthorRepositoryInterface;
use App\State\Query\Author\ListAuthorsQuery;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

#[AsMessageHandler]
readonly class ListAuthorsHandler
{
    public function __construct(
        private AuthorRepositoryInterface $authorRepository,
        private AuthorMapper $mapper
    ) {
    }

    /**
     * @throws ExceptionInterface
     *
     * @return array<int, array<string, mixed>>
     */
    public function __invoke(ListAuthorsQuery $query): array
    {
        $criteria = [];
        /** @var iterable<int, Author> $authors */
        $authors = $this->authorRepository->findBy($criteria, null, $query->limit, $query->offset);

        return [
            'authors' => $this->mapper->many($authors),
            'total'   => $this->authorRepository->count($criteria),
        ];
    }
}
