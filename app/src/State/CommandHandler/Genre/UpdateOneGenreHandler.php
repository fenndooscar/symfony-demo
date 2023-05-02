<?php

declare(strict_types = 1);

namespace App\State\CommandHandler\Genre;

use App\Repository\GenreRepositoryInterface;
use App\Service\Genre\Dto\GenreUpdaterDto;
use App\Service\Genre\GenreUpdater;
use App\State\Command\Genre\UpdateOneGenreCommand;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class UpdateOneGenreHandler
{
    public function __construct(
        private GenreRepositoryInterface $genreRepository,
        private GenreUpdater $genreUpdater
    ) {
    }

    /**
     * @throws EntityNotFoundException
     */
    public function __invoke(UpdateOneGenreCommand $command): int
    {
        $genre = $this->genreRepository->findOneById($command->id);

        $updated = $this->genreUpdater->update($genre, new GenreUpdaterDto(
            $command->name,
        ));

        if (true === $updated) {
            $this->genreRepository->save($genre, true);
        }

        return $genre->getId();
    }
}
