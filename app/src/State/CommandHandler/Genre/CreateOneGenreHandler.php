<?php

declare(strict_types = 1);

namespace App\State\CommandHandler\Genre;

use App\Repository\GenreRepositoryInterface;
use App\Service\Genre\Dto\GenreCreatorDto;
use App\Service\Genre\GenreCreator;
use App\State\Command\Genre\CreateOneGenreCommand;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class CreateOneGenreHandler
{
    public function __construct(
        private GenreRepositoryInterface $genreRepository,
        private GenreCreator $genreCreator
    ) {
    }

    public function __invoke(CreateOneGenreCommand $command): int
    {
        $genre = $this->genreCreator->create(new GenreCreatorDto(
            $command->name,
        ));

        $this->genreRepository->save($genre, true);

        return $genre->getId();
    }
}
