<?php

declare(strict_types = 1);

namespace App\State\CommandHandler\Author;

use App\Repository\AuthorRepositoryInterface;
use App\Service\Author\AuthorUpdater;
use App\Service\Author\Dto\AuthorUpdaterDto;
use App\State\Command\Author\UpdateManyAuthorsCommand;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

use function array_column;

#[AsMessageHandler]
readonly class UpdateManyAuthorHandler
{
    public function __construct(
        private AuthorRepositoryInterface $authorRepository,
        private AuthorUpdater $authorUpdater
    ) {
    }

    /**
     * @throws EntityNotFoundException
     *
     * @return int[]
     */
    public function __invoke(UpdateManyAuthorsCommand $command): array
    {
        $updated = false;
        $ids     = array_column($command->authors, 'id');
        $authors = $this->authorRepository->findByIds($ids);

        foreach ($command->authors as $authorCommand) {
            $author = $authors[$authorCommand->id];

            $updated = $this->authorUpdater->update($author, new AuthorUpdaterDto(
                $authorCommand->firstname,
                $authorCommand->lastname,
            ));

            if (true === $updated) {
                $this->authorRepository->save($author);
            }
        }

        if (true === $updated) {
            $this->authorRepository->save(null, true);
        }

        return $ids;
    }
}
