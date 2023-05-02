<?php

declare(strict_types = 1);

namespace App\Mapper;

use App\Entity\Genre;
use DateTimeInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

readonly class GenreMapper
{
    public function __construct(private ObjectNormalizer $normalizer)
    {
    }

    /**
     * @throws ExceptionInterface
     *
     * @return array<string, mixed>
     */
    public function one(Genre $genre): array
    {
        return $this->normalizer->normalize($genre, 'array', $this->makeContext());
    }

    /**
     * @param iterable<int, Genre> $genres
     *
     * @throws ExceptionInterface
     *
     * @return array<int, array<string, mixed>>
     */
    public function many(iterable $genres): array
    {
        $context = $this->makeContext();
        $mapped  = [];

        foreach ($genres as $genre) {
            $mapped[] = $this->normalizer->normalize($genre, 'array', $context);
        }

        return $mapped;
    }

    /**
     * @return array<string, mixed>
     */
    private function makeContext(): array
    {
        $dateCallback = static function ($dateTime): string {
            return $dateTime instanceof DateTimeInterface ? $dateTime->format(DateTimeInterface::ATOM) : '';
        };

        return [
            AbstractNormalizer::CALLBACKS => [
                'createdAt' => $dateCallback,
                'updatedAt' => $dateCallback,
            ],
        ];
    }
}
