<?php

declare(strict_types = 1);

namespace App\State\Command\Book;

use App\State\Command;
use Symfony\Component\Validator\Constraints as Assert;

readonly class UpdateOneBookCommand implements Command
{
    /**
     * @param int[]|null $genres
     */
    public function __construct(
        #[Assert\Positive]
        public int $id,
        #[Assert\NotBlank(allowNull: true)]
        public ?string $name,
        #[Assert\NotBlank(allowNull: true)]
        public ?string $description,
        #[Assert\Positive]
        public ?int $authorId,
        #[Assert\NotBlank(allowNull: true)]
        public ?array $genres
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public static function fromArray(array $data): self
    {
        /** @var int[]|null $genres $genres */
        $genres = isset($data['genres']) ? (array) $data['genres'] : null;

        return new self(
            (int) ($data['id'] ?? 0),
            isset($data['name']) ? (string) $data['name'] : null,
            isset($data['description']) ? (string) $data['description'] : null,
            isset($data['authorId']) ? (int) $data['authorId'] : null,
            $genres,
        );
    }
}
