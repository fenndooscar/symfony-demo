<?php

declare(strict_types = 1);

namespace App\Service\Author\Dto;

readonly class AuthorUpdaterDto
{
    public function __construct(
        public ?string $firstname,
        public ?string $lastname,
    ) {
    }
}
