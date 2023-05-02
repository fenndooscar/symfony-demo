<?php

declare(strict_types = 1);

namespace App\State\Command\Author;

use App\Assert\MessageCollection;
use App\State\Command;

use function array_map;

readonly class UpdateManyAuthorsCommand implements Command
{
    /**
     * @param UpdateOneAuthorCommand[] $authors
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
        $authors = array_map(static function ($author): UpdateOneAuthorCommand {
            return UpdateOneAuthorCommand::fromArray($author);
        }, $data['authors'] ?? []);

        return new self(
            $authors
        );
    }
}
