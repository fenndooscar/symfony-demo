<?php

declare(strict_types = 1);

namespace App\State\Command\Genre;

use App\Assert\MessageCollection;
use App\State\Command;

use function array_map;

readonly class DeleteManyGenresCommand implements Command
{
    /**
     * @param DeleteOneGenreCommand[] $genres
     */
    public function __construct(
        #[MessageCollection]
        public array $genres
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public static function fromArray(array $data): self
    {
        $genres = array_map(static function ($genre): DeleteOneGenreCommand {
            return DeleteOneGenreCommand::fromArray($genre);
        }, $data['genres'] ?? []);

        return new self(
            $genres
        );
    }
}
