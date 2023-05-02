<?php

declare(strict_types = 1);

namespace App\Service\Genre;

use App\Entity\Genre;
use App\Service\Genre\Dto\GenreUpdaterDto;

readonly class GenreUpdater
{
    public function update(Genre $genre, GenreUpdaterDto $dto): bool
    {
        $updated = false;

        if (null !== $dto->name) {
            $genre->setName($dto->name);
            $updated = true;
        }

        return $updated;
    }
}
