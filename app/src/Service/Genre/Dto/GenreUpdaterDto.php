<?php

declare(strict_types = 1);

namespace App\Service\Genre\Dto;

readonly class GenreUpdaterDto
{
    public function __construct(
        public ?string $name,
    ) {
    }
}
