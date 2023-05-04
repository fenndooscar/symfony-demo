<?php

declare(strict_types = 1);

namespace App\State\QueryHandler\Book;

use App\Repository\BookRepositoryInterface;
use App\State\Query\Book\ShowBookQuery;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[AsMessageHandler]
readonly class ShowBookHandler
{
    public function __construct(
        private BookRepositoryInterface $bookRepository,
        private NormalizerInterface $normalizer
    ) {
    }

    /**
     * @throws EntityNotFoundException
     * @throws ExceptionInterface
     *
     * @return array<string, mixed>
     */
    public function __invoke(ShowBookQuery $query): array
    {
        return $this->normalizer->normalize($this->bookRepository->findOneById($query->id));
    }
}
