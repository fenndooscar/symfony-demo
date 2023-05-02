<?php

declare(strict_types = 1);

namespace App\State\CommandHandler\Book;

use App\Repository\AuthorRepositoryInterface;
use App\Repository\BookRepositoryInterface;
use App\Repository\GenreRepositoryInterface;
use App\Service\Book\BookCreator;
use App\Service\Book\Dto\BookCreatorDto;
use App\State\Command\Book\CreateOneBookCommand;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class CreateOneBookHandler
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
     */
    public function __invoke(CreateOneBookCommand $command): int
    {
        $book = $this->bookCreator->create(new BookCreatorDto(
            $command->name,
            $command->description,
            $this->authorRepository->findOneById($command->authorId),
            new ArrayCollection($this->genreRepository->findByIds($command->genres))
        ));

        $this->bookRepository->save($book, true);

        return $book->getId();
    }
}
