<?php

declare(strict_types = 1);

namespace App\Service\Author;

use App\Entity\Author;
use App\Service\Author\Dto\AuthorUpdaterDto;

readonly class AuthorUpdater
{
    public function update(Author $author, AuthorUpdaterDto $dto): bool
    {
        $updated = false;

        if (null !== $dto->firstname) {
            $author->setFirstname($dto->firstname);
            $updated = true;
        }

        if (null !== $dto->lastname) {
            $author->setLastname($dto->lastname);
            $updated = true;
        }

        return $updated;
    }
}
