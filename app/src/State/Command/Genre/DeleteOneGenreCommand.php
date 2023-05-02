<?php

declare(strict_types = 1);

namespace App\State\Command\Genre;

use App\State\Command;
use Symfony\Component\Validator\Constraints as Assert;

readonly class DeleteOneGenreCommand implements Command
{
    public function __construct(
        #[Assert\Positive]
        public int $id,
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
