<?php

declare(strict_types = 1);

namespace App\State\Command\Book;

use App\Assert\MessageCollection;
use App\State\Command;

use function array_map;

readonly class CreateManyBooksCommand implements Command
{
    /**
     * @param CreateOneBookCommand[] $books
     */
    public function __construct(
        #[MessageCollection]
        public array $books
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public static function fromArray(array $data): self
    {
        $books = array_map(static function ($book): CreateOneBookCommand {
            return CreateOneBookCommand::fromArray($book);
        }, $data['books'] ?? []);

        return new self(
            $books
        );
    }
}
