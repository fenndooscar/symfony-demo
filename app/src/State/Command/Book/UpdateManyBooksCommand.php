<?php

declare(strict_types = 1);

namespace App\State\Command\Book;

use App\Assert\MessageCollection;
use App\State\Command;

use function array_map;

readonly class UpdateManyBooksCommand implements Command
{
    /**
     * @param UpdateOneBookCommand[] $books
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
        $books = array_map(static function ($book): UpdateOneBookCommand {
            return UpdateOneBookCommand::fromArray($book);
        }, $data['books'] ?? []);

        return new self(
            $books
        );
    }
}
