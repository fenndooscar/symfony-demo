<?php

declare(strict_types = 1);

namespace App\State\CommandHandler\Book;

use App\Repository\AuthorRepositoryInterface;
use App\Repository\BookRepositoryInterface;
use App\Repository\GenreRepositoryInterface;
use App\Service\Book\BookUpdater;
use App\Service\Book\Dto\BookUpdaterDto;
use App\State\Command\Book\UpdateOneBookCommand;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

use function is_array;
use function is_int;

#[AsMessageHandler]
readonly class UpdateOneBookHandler
{
    public function __construct(
        private BookRepositoryInterface $bookRepository,
        private AuthorRepositoryInterface $authorRepository,
        private GenreRepositoryInterface $genreRepository,
        private BookUpdater $bookUpdater
    ) {
    }

    /**
     * @throws EntityNotFoundException
     */
    public function __invoke(UpdateOneBookCommand $command): int
    {
        $book = $this->bookRepository->findOneById($command->id);

        $author = null;
        if (is_int($command->authorId)) {
            $author = $this->authorRepository->findOneById($command->authorId);
        }

        $genres = null;
        if (is_array($command->genres)) {
            $genres = new ArrayCollection($this->genreRepository->findByIds($command->genres));
        }

        $updated = $this->bookUpdater->update($book, new BookUpdaterDto(
            $command->name,
            $command->description,
            $author,
            $genres
        ));

        if (true === $updated) {
            $this->bookRepository->save($book, true);
        }

        return $book->getId();
    }
}
