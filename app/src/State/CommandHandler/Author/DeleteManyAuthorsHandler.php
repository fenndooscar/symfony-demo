<?php

declare(strict_types = 1);

namespace App\State\CommandHandler\Author;

use App\Repository\AuthorRepositoryInterface;
use App\State\Command\Author\DeleteManyAuthorsCommand;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class DeleteManyAuthorsHandler
{
    public function __construct(
        private AuthorRepositoryInterface $authorRepository
    ) {
    }

    /**
     * @throws EntityNotFoundException
     */
    public function __invoke(DeleteManyAuthorsCommand $command): void
    {
        foreach ($command->authors as $authorCommand) {
            $this->authorRepository->remove($this->authorRepository->findOneById($authorCommand->id));
        }

        $this->authorRepository->remove(null, true);
    }
}
