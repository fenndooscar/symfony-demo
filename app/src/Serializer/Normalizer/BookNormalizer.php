<?php

declare(strict_types = 1);

namespace App\Serializer\Normalizer;

use App\Entity\Author;
use App\Entity\Book;
use DateTimeInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

readonly class BookNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    public function __construct(private ObjectNormalizer $objectNormalizer)
    {
    }

    /**
     * @param array<string, mixed> $context
     *
     * @throws ExceptionInterface
     */
    public function normalize(mixed $object, string $format = null, array $context = []): mixed
    {
        $dateCallback = static function ($dateTime): string {
            return $dateTime instanceof DateTimeInterface ? $dateTime->format(DateTimeInterface::ATOM) : '';
        };

        $defaultContext = [
            [
                AbstractNormalizer::CALLBACKS => [
                    'createdAt' => $dateCallback,
                    'updatedAt' => $dateCallback,
                    'author'    => static function (Author $author): array {
                        return ['id' => $author->getId()];
                    },
                ],
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => static function (Book $book) {
                    return $book->getId();
                },
            ],
        ];

        return $this->objectNormalizer->normalize($object, $format, $defaultContext + $context);
    }

    /**
     * @param array<string, mixed> $context
     */
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof Book;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
