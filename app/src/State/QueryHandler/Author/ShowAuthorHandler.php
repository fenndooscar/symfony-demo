<?php

declare(strict_types = 1);

namespace App\State\QueryHandler\Author;

use App\Repository\AuthorRepositoryInterface;
use App\State\Query\Author\ShowAuthorQuery;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[AsMessageHandler]
readonly class ShowAuthorHandler
{
    public function __construct(
        private AuthorRepositoryInterface $authorRepository,
        private NormalizerInterface $normalizer
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
        $author  = $this->authorRepository->findOneById($query->id);

        return $this->normalizer->normalize($author);
    }
}
