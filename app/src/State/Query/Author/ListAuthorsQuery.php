<?php

declare(strict_types = 1);

namespace App\State\Query\Author;

use App\State\Query;

readonly class ListAuthorsQuery implements Query
{
    public function __construct(
        public int $limit,
        public int $offset,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (int) ($data['limit'] ?? 0),
            (int) ($data['offset'] ?? 0),
        );
    }
}
