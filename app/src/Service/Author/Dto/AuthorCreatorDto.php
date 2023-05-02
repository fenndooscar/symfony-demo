<?php

declare(strict_types = 1);

namespace App\Service\Author\Dto;

readonly class AuthorCreatorDto
{
    public function __construct(
        public string $firstname,
        public string $lastname,
    ) {
    }
}
