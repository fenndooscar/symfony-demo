<?php

declare(strict_types = 1);

namespace App\Service\Book;

use App\Entity\Book;
use App\Service\Book\Dto\BookCreatorDto;

readonly class BookCreator
{
    public function create(BookCreatorDto $dto): Book
    {
        $book = new Book();
        $book->setName($dto->name);
        $book->setDescription($dto->description);
        $book->setAuthor($dto->author);
        $book->setGenres($dto->genres);

        return $book;
    }
}
