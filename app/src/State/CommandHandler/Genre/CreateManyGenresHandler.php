<?php

declare(strict_types = 1);

namespace App\State\CommandHandler\Genre;

use App\Repository\GenreRepositoryInterface;
use App\Service\Genre\Dto\GenreCreatorDto;
use App\Service\Genre\GenreCreator;
use App\State\Command\Genre\CreateManyGenresCommand;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class CreateManyGenresHandler
{
    public function __construct(
        private GenreRepositoryInterface $genreRepository,
        private GenreCreator $genreCreator
    ) {
    }

    /**
     * @return int[]
     */
    public function __invoke(CreateManyGenresCommand $command): array
    {
        $ids = [];

        foreach ($command->genres as $genreCommand) {
            $genre = $this->genreCreator->create(new GenreCreatorDto(
                $genreCommand->name,
            ));
            $this->genreRepository->save($genre);

            $ids[] = $genre->getId();
        }

        $this->genreRepository->save(null, true);

        return $ids;
    }
}
