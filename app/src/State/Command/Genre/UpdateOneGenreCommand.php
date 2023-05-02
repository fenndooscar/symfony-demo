<?php

declare(strict_types = 1);

namespace App\State\Command\Genre;

use App\State\Command;
use Symfony\Component\Validator\Constraints as Assert;

readonly class UpdateOneGenreCommand implements Command
{
    public function __construct(
        #[Assert\Positive]
        public int $id,
        #[Assert\NotBlank(allowNull: true)]
        public ?string $name,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (int) ($data['id'] ?? 0),
            isset($data['name']) ? (string) $data['name'] : null,
        );
    }
}
