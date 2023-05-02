<?php

declare(strict_types = 1);

namespace App\Mapper;

use App\Entity\Author;
use App\Entity\Book;
use DateTimeInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

readonly class BookMapper
{
    public function __construct(private ObjectNormalizer $normalizer)
    {
    }

    /**
     * @throws ExceptionInterface
     *
     * @return array<string, mixed>
     */
    public function one(Book $book): array
    {
        return $this->normalizer->normalize($book, 'array', $this->makeContext());
    }

    /**
     * @param iterable<int, Book> $books
     *
     * @throws ExceptionInterface
     *
     * @return array<int, array<string, mixed>>
     */
    public function many(iterable $books): array
    {
        $context = $this->makeContext();
        $mapped  = [];

        foreach ($books as $book) {
            $mapped[] = $this->normalizer->normalize($book, 'array', $context);
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
        $authorCallback = static function (Author $author): array {
            return ['id' => $author->getId()];
        };

        return [
            AbstractNormalizer::CALLBACKS => [
                'createdAt' => $dateCallback,
                'updatedAt' => $dateCallback,
                'author'    => $authorCallback,
            ],
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => static function (Book $book) {
                return $book->getId();
            },
        ];
    }
}
