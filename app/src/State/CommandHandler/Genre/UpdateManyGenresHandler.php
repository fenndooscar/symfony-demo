<?php

declare(strict_types = 1);

namespace App\State\CommandHandler\Genre;

use App\Repository\GenreRepositoryInterface;
use App\Service\Genre\Dto\GenreUpdaterDto;
use App\Service\Genre\GenreUpdater;
use App\State\Command\Genre\UpdateManyGenresCommand;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

use function array_column;

#[AsMessageHandler]
readonly class UpdateManyGenresHandler
{
    public function __construct(
        private GenreRepositoryInterface $genreRepository,
        private GenreUpdater $genreUpdater
    ) {
    }

    /**
     * @throws EntityNotFoundException
     *
     * @return int[]
     */
    public function __invoke(UpdateManyGenresCommand $command): array
    {
        $updated = false;
        $ids     = array_column($command->genres, 'id');
        $genres  = $this->genreRepository->findByIds($ids);

        foreach ($command->genres as $genreCommand) {
            $genre = $genres[$genreCommand->id];

            $updated = $this->genreUpdater->update($genre, new GenreUpdaterDto(
                $genreCommand->name,
            ));

            if (true === $updated) {
                $this->genreRepository->save($genre);
            }
        }

        if (true === $updated) {
            $this->genreRepository->save(null, true);
        }

        return $ids;
    }
}
