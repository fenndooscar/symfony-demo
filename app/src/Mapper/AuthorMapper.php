<?php

declare(strict_types = 1);

namespace App\Mapper;

use App\Entity\Author;
use App\Entity\Book;
use DateTimeInterface;
use Doctrine\Common\Collections\ReadableCollection;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

readonly class AuthorMapper
{
    public function __construct(
        private ObjectNormalizer $normalizer
    ) {
    }

    /**
     * @throws ExceptionInterface
     *
     * @return array<string, mixed>
     */
    public function one(Author $author): array
    {
        return $this->normalizer->normalize($author, 'array', $this->makeContext());
    }

    /**
     * @param iterable<int, Author> $authors
     *
     * @throws ExceptionInterface
     *
     * @return array<int, array<string, mixed>>
     */
    public function many(iterable $authors): array
    {
        $context = $this->makeContext();
        $mapped  = [];

        foreach ($authors as $author) {
            $mapped[] = $this->normalizer->normalize($author, 'array', $context);
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
        $booksCallback = static function ($books): array {
            return $books instanceof ReadableCollection ? $books->map(static function ($book) {
                if ($book instanceof Book) {
                    return [
                        'id'   => $book->getId(),
                        'name' => $book->getName(),
                    ];
                }

                return [];
            })->toArray() : [];
        };

        return [
            AbstractNormalizer::CALLBACKS => [
                'createdAt' => $dateCallback,
                'updatedAt' => $dateCallback,
                'books'     => $booksCallback,
            ],
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => static function (Author $author) {
                return $author->getId();
            },
        ];
    }
}
