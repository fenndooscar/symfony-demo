<?php

declare(strict_types = 1);

namespace App\State\Command\Author;

use App\Assert\MessageCollection;
use App\State\Command;

use function array_map;

readonly class DeleteManyAuthorsCommand implements Command
{
    /**
     * @param DeleteOneAuthorCommand[]$authors
     */
    public function __construct(
        #[MessageCollection]
        public array $authors
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public static function fromArray(array $data): self
    {
        $authors = array_map(static function ($author): DeleteOneAuthorCommand {
            return DeleteOneAuthorCommand::fromArray($author);
        }, $data['authors'] ?? []);

        return new self(
            $authors
        );
    }
}
