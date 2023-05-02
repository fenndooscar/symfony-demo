<?php

declare(strict_types = 1);

namespace App\State\CommandHandler\Author;

use App\Repository\AuthorRepositoryInterface;
use App\State\Command\Author\DeleteOneAuthorCommand;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class DeleteOneAuthorHandler
{
    public function __construct(
        private AuthorRepositoryInterface $authorRepository
    ) {
    }

    /**
     * @throws EntityNotFoundException
     */
    public function __invoke(DeleteOneAuthorCommand $command): void
    {
        $this->authorRepository->remove($this->authorRepository->findOneById($command->id));
    }
}
