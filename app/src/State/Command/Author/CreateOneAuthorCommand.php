<?php

declare(strict_types = 1);

namespace App\State\Command\Author;

use App\State\Command;
use Symfony\Component\Validator\Constraints as Assert;

readonly class CreateOneAuthorCommand implements Command
{
    public function __construct(
        #[Assert\NotBlank]
        public string $firstname,
        #[Assert\NotBlank]
        public string $lastname
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (string) ($data['firstname'] ?? ''),
            (string) ($data['lastname'] ?? '')
        );
    }
}
