<?php

declare(strict_types = 1);

namespace App\State\Command\Genre;

use App\State\Command;
use Symfony\Component\Validator\Constraints as Assert;

readonly class CreateOneGenreCommand implements Command
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (string) ($data['name'] ?? ''),
        );
    }
}
