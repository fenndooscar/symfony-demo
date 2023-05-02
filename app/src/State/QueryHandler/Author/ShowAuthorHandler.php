<?php

declare(strict_types = 1);

namespace App\State\QueryHandler\Author;

use App\Mapper\AuthorMapper;
use App\Repository\AuthorRepositoryInterface;
use App\State\Query\Author\ShowAuthorQuery;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

#[AsMessageHandler]
readonly class ShowAuthorHandler
{
    public function __construct(
        private AuthorRepositoryInterface $authorRepository,
        private AuthorMapper $mapper
    ) {
    }

    /**
     * @throws EntityNotFoundException
     * @throws ExceptionInterface
     *
     * @return array<string, mixed>
     */
    public function __invoke(ShowAuthorQuery $query): array
    {
        return $this->mapper->one($this->authorRepository->findOneById($query->id));
    }
}
