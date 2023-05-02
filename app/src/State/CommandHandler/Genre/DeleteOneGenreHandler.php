<?php

declare(strict_types = 1);

namespace App\State\CommandHandler\Genre;

use App\Repository\GenreRepositoryInterface;
use App\State\Command\Genre\DeleteOneGenreCommand;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class DeleteOneGenreHandler
{
    public function __construct(
        private GenreRepositoryInterface $genreRepository
    ) {
    }

    /**
     * @throws EntityNotFoundException
     */
    public function __invoke(DeleteOneGenreCommand $command): void
    {
        $genre = $this->genreRepository->findOneById($command->id);
        $this->genreRepository->remove($genre, true);
    }
}
