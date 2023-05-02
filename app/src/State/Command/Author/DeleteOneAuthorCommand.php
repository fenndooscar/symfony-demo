<?php

declare(strict_types = 1);

namespace App\State\Command\Author;

use App\State\Command;
use Symfony\Component\Validator\Constraints as Assert;

readonly class DeleteOneAuthorCommand implements Command
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
