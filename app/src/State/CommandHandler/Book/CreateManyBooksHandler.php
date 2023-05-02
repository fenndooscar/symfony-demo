<?php

declare(strict_types = 1);

namespace App\State\CommandHandler\Book;

use App\Repository\AuthorRepositoryInterface;
use App\Repository\BookRepositoryInterface;
use App\Repository\GenreRepositoryInterface;
use App\Service\Book\BookCreator;
use App\Service\Book\Dto\BookCreatorDto;
use App\State\Command\Book\CreateManyBooksCommand;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class CreateManyBooksHandler
{
    public function __construct(
        private BookRepositoryInterface $bookRepository,
        private AuthorRepositoryInterface $authorRepository,
        private GenreRepositoryInterface $genreRepository,
        private BookCreator $bookCreator
    ) {
    }

    /**
     * @throws EntityNotFoundException
     *
     * @return int[]
     */
    public function __invoke(CreateManyBooksCommand $command): array
    {
        $ids = [];

        foreach ($command->books as $bookCommand) {
            $book = $this->bookCreator->create(new BookCreatorDto(
                $bookCommand->name,
                $bookCommand->description,
                $this->authorRepository->findOneById($bookCommand->authorId),
                new ArrayCollection($this->genreRepository->findByIds($bookCommand->genres))
            ));
            $this->bookRepository->save($book);

            $ids[] = $book->getId();
        }

        $this->bookRepository->save(null, true);

        return $ids;
    }
}
