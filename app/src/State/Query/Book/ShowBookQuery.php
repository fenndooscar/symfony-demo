<?php

declare(strict_types = 1);

namespace App\State\Query\Book;

use App\State\Query;
use Symfony\Component\Validator\Constraints as Assert;

readonly class ShowBookQuery implements Query
{
    public function __construct(
        #[Assert\Positive]
        public int $id
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (int) ($data['id'] ?? 0)
        );
    }
}
