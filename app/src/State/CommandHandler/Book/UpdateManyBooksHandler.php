<?php

declare(strict_types = 1);

namespace App\State\CommandHandler\Book;

use App\Repository\AuthorRepositoryInterface;
use App\Repository\BookRepositoryInterface;
use App\Repository\GenreRepositoryInterface;
use App\Service\Book\BookUpdater;
use App\Service\Book\Dto\BookUpdaterDto;
use App\State\Command\Book\UpdateManyBooksCommand;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

use function array_column;
use function is_array;
use function is_int;

#[AsMessageHandler]
readonly class UpdateManyBooksHandler
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
     *
     * @return int[]
     */
    public function __invoke(UpdateManyBooksCommand $command): array
    {
        $updated = false;
        $ids     = array_column($command->books, 'id');
        $books   = $this->bookRepository->findByIds($ids);

        $authorsIds = [];
        foreach ($command->books as $bookCommand) {
            if (null === $bookCommand->authorId) {
                continue;
            }

            $authorsIds[] = $bookCommand->authorId;
        }

        $authors = $this->authorRepository->findByIds($authorsIds);

        foreach ($command->books as $bookCommand) {
            $book = $books[$bookCommand->id];

            $author = null;
            if (is_int($bookCommand->authorId) && isset($authors[$bookCommand->authorId])) {
                $author = $authors[$bookCommand->authorId];
            }

            $genres = null;
            if (is_array($bookCommand->genres)) {
                $genres = new ArrayCollection($this->genreRepository->findByIds($bookCommand->genres));
            }

            $updated = $this->bookUpdater->update($book, new BookUpdaterDto(
                $bookCommand->name,
                $bookCommand->description,
                $author,
                $genres
            ));

            if (true === $updated) {
                $this->bookRepository->save($book);
            }
        }

        if (true === $updated) {
            $this->bookRepository->save(null, true);
        }

        return $ids;
    }
}
