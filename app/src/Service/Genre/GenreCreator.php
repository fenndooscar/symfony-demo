<?php

declare(strict_types = 1);

namespace App\Service\Genre;

use App\Entity\Genre;
use App\Service\Genre\Dto\GenreCreatorDto;

readonly class GenreCreator
{
    public function create(GenreCreatorDto $dto): Genre
    {
        $genre = new Genre();
        $genre->setName($dto->name);

        return $genre;
    }
}
