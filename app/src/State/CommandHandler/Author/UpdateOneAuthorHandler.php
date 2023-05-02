<?php

declare(strict_types = 1);

namespace App\State\CommandHandler\Author;

use App\Repository\AuthorRepositoryInterface;
use App\Service\Author\AuthorUpdater;
use App\Service\Author\Dto\AuthorUpdaterDto;
use App\State\Command\Author\UpdateOneAuthorCommand;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class UpdateOneAuthorHandler
{
    public function __construct(
        private AuthorUpdater $authorUpdater,
        private AuthorRepositoryInterface $authorRepository
    ) {
    }

    /**
     * @throws EntityNotFoundException
     */
    public function __invoke(UpdateOneAuthorCommand $command): int
    {
        $author  = $this->authorRepository->findOneById($command->id);
        $updated = $this->authorUpdater->update($author, new AuthorUpdaterDto(
            $command->firstname,
            $command->lastname,
        ));

        if (true === $updated) {
            $this->authorRepository->save($author, true);
        }

        return $command->id;
    }
}
