<?php

declare(strict_types = 1);

namespace App\Service\Author;

use App\Entity\Author;
use App\Service\Author\Dto\AuthorCreatorDto;

readonly class AuthorCreator
{
    public function create(AuthorCreatorDto $dto): Author
    {
        $author = new Author();
        $author->setFirstname($dto->firstname);
        $author->setFirstname($dto->lastname);

        return $author;
    }
}
