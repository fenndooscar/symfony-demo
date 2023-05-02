<?php

declare(strict_types = 1);

namespace App\State\Command\Book;

use App\State\Command;
use Symfony\Component\Validator\Constraints as Assert;

readonly class CreateOneBookCommand implements Command
{
    /**
     * @param int[] $genres
     */
    public function __construct(
        #[Assert\NotBlank]
        public string $name,
        #[Assert\NotBlank]
        public string $description,
        #[Assert\Positive]
        public int $authorId,
        #[Assert\NotBlank, Assert\All([new Assert\Positive()])]
        public array $genres
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public static function fromArray(array $data): self
    {
        /** @var int[] $genres */
        $genres = (array) ($data['genres'] ?? []);

        return new self(
            (string) ($data['name'] ?? ''),
            (string) ($data['description'] ?? ''),
            (int) ($data['authorId'] ?? 0),
            $genres,
        );
    }
}
