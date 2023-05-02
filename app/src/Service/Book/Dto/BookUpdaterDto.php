<?php

declare(strict_types = 1);

namespace App\Service\Book\Dto;

use App\Entity\Author;
use Doctrine\Common\Collections\Collection;

readonly class BookUpdaterDto
{
    public function __construct(
        public ?string $name,
        public ?string $description,
        public ?Author $author,
        public ?Collection $genres
    ) {
    }
}
