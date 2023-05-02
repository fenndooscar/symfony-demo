<?php

declare(strict_types = 1);

namespace App\Service\Book;

use App\Entity\Book;
use App\Service\Book\Dto\BookUpdaterDto;

readonly class BookUpdater
{
    public function update(Book $book, BookUpdaterDto $dto): bool
    {
        $updated = false;

        if (null !== $dto->name) {
            $book->setName($dto->name);
            $updated = true;
        }

        if (null !== $dto->description) {
            $book->setDescription($dto->description);
            $updated = true;
        }

        if (null !== $dto->author) {
            $book->setAuthor($dto->author);
            $updated = true;
        }

        if (null !== $dto->genres) {
            $book->setGenres($dto->genres);
            $updated = true;
        }

        return $updated;
    }
}
