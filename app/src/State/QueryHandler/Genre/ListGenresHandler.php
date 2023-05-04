<?php

declare(strict_types = 1);

namespace App\State\QueryHandler\Genre;

use App\Entity\Genre;
use App\Repository\GenreRepositoryInterface;
use App\State\Query\Genre\ListGenresQuery;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[AsMessageHandler]
readonly class ListGenresHandler
{
    public function __construct(
        private GenreRepositoryInterface $genreRepository,
        private NormalizerInterface $normalizer
    ) {
    }

    /**
     * @throws ExceptionInterface
     *
     * @return array<string, mixed>
     */
    public function __invoke(ListGenresQuery $query): array
    {
        $criteria = [];
        /** @var iterable<int, Genre> $genres */
        $genres = $this->genreRepository->findBy($criteria, null, $query->limit, $query->offset);

        return [
            'genres' => $this->normalizer->normalize($genres),
            'total'  => $this->genreRepository->findBy($criteria),
        ];
    }
}
