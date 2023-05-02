<?php

declare(strict_types = 1);

namespace App\State\Command\Author;

use App\State\Command;
use Symfony\Component\Validator\Constraints as Assert;

readonly class UpdateOneAuthorCommand implements Command
{
    public function __construct(
        #[Assert\Positive]
        public int $id,
        #[Assert\NotBlank(allowNull: true)]
        public ?string $firstname,
        #[Assert\NotBlank(allowNull: true)]
        public ?string $lastname,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (int) ($data['id'] ?? 0),
            isset($data['firstname']) ? (string) $data['firstname'] : null,
            isset($data['lastname']) ? (string) $data['lastname'] : null,
        );
    }
}
