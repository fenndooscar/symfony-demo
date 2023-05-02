<?php

declare(strict_types = 1);

namespace App\State\CommandHandler\Book;

use App\Repository\BookRepositoryInterface;
use App\State\Command\Book\DeleteOneBookCommand;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class DeleteOneBookHandler
{
    public function __construct(
        private BookRepositoryInterface $bookRepository
    ) {
    }

    /**
     * @throws EntityNotFoundException
     */
    public function __invoke(DeleteOneBookCommand $command): void
    {
        $book = $this->bookRepository->findOneById($command->id);
        $this->bookRepository->remove($book, true);
    }
}
