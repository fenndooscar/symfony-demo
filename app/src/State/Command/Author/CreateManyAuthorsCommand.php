<?php

declare(strict_types = 1);

namespace App\State\Command\Author;

use App\Assert\MessageCollection;
use App\State\Command;

use function array_map;

readonly class CreateManyAuthorsCommand implements Command
{
    /**
     * @param CreateOneAuthorCommand[] $authors
     */
    public function __construct(
        #[MessageCollection]
        public array $authors
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public static function fromArray(array $data): Command
    {
        $authors = array_map(static function ($author): CreateOneAuthorCommand {
            return CreateOneAuthorCommand::fromArray($author);
        }, $data['authors'] ?? []);

        return new self(
            $authors
        );
    }
}
