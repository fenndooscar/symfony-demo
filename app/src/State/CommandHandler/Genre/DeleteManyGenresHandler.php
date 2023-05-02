<?php

declare(strict_types = 1);

namespace App\State\CommandHandler\Genre;

use App\Repository\GenreRepositoryInterface;
use App\State\Command\Genre\DeleteManyGenresCommand;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

use function array_column;

#[AsMessageHandler]
readonly class DeleteManyGenresHandler
{
    public function __construct(
        private GenreRepositoryInterface $genreRepository,
    ) {
    }

    /**
     * @throws EntityNotFoundException
     */
    public function __invoke(DeleteManyGenresCommand $command): void
    {
        $genres = $this->genreRepository->findByIds(array_column($command->genres, 'id'));
        foreach ($genres as $genre) {
            $this->genreRepository->remove($genre);
        }

        $this->genreRepository->remove(null, true);
    }
}
