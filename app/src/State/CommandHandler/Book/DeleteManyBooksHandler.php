<?php

declare(strict_types = 1);

namespace App\State\CommandHandler\Book;

use App\Repository\BookRepositoryInterface;
use App\State\Command\Book\DeleteManyBooksCommand;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

use function array_column;

#[AsMessageHandler]
readonly class DeleteManyBooksHandler
{
    public function __construct(
        private BookRepositoryInterface $bookRepository,
    ) {
    }

    /**
     * @throws EntityNotFoundException
     */
    public function __invoke(DeleteManyBooksCommand $command): void
    {
        $books = $this->bookRepository->findByIds(array_column($command->books, 'id'));
        foreach ($books as $book) {
            $this->bookRepository->remove($book);
        }

        $this->bookRepository->remove(null, true);
    }
}
