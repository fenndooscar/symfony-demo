<?php

declare(strict_types = 1);

namespace App\State\QueryHandler\Genre;

use App\Repository\GenreRepositoryInterface;
use App\State\Query\Genre\ShowGenreQuery;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[AsMessageHandler]
readonly class ShowGenreHandler
{
    public function __construct(
        private GenreRepositoryInterface $genreRepository,
        private NormalizerInterface $normalizer
    ) {
    }

    /**
     * @throws EntityNotFoundException
     * @throws ExceptionInterface
     *
     * @return array<string, mixed>
     */
    public function __invoke(ShowGenreQuery $query): array
    {
        return $this->normalizer->normalize($this->genreRepository->findOneById($query->id));
    }
}
