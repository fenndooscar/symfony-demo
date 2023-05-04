<?php

declare(strict_types = 1);

namespace App\Serializer\Normalizer;

use App\Entity\Author;
use App\Entity\Book;
use DateTimeInterface;
use Doctrine\Common\Collections\ReadableCollection;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

use function is_iterable;

class AuthorNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private Serializer $serializer;

    public function __construct(ObjectNormalizer $objectNormalizer)
    {
        $this->serializer = new Serializer([
            $objectNormalizer,
        ]);
    }

    public function normalize(mixed $object, string $format = null, array $context = []): mixed
    {
        $dateCallback = static function ($dateTime): string {
            return $dateTime instanceof DateTimeInterface ? $dateTime->format(DateTimeInterface::ATOM) : '';
        };

        $booksCallback = static function ($books): array {
            $bookMapper = static function (mixed $book) {
                return $book instanceof Book
                    ? ['id' => $book->getId(), 'name' => $book->getName()]
                    : [];
            };

            return $books instanceof ReadableCollection && $books->count() > 0
                ? $books->map($bookMapper)->toArray()
                : [];
        };

        $defaultContext = [
            AbstractNormalizer::CALLBACKS => [
                'createdAt' => $dateCallback,
                'updatedAt' => $dateCallback,
                'books'     => $booksCallback,
            ],
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => static function (Author $author) {
                return $author->getId();
            },
        ];

        $context = $defaultContext + $context;

        if ($object instanceof Author) {
            return $this->serializer->normalize($object, $format, $context);
        }

        if (is_iterable($object)) {
            $genFactory = function ($objects, $format, $context) {
                foreach ($objects as $object) {
                    yield $this->serializer->normalize($object, $format, $context);
                }
            };

            $normalized = [];
            $generator  = $genFactory($object, $format, $context);

            while ($generator->valid()) {
                $normalized[] = $generator->current();

                $generator->next();
            }

            return $normalized;
        }

        return [];
    }

    /**
     * @param array<string, mixed> $context
     */
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof Author;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
