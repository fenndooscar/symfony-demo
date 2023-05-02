<?php

declare(strict_types = 1);

namespace App\State\CommandHandler\Author;

use App\Repository\AuthorRepositoryInterface;
use App\Service\Author\AuthorCreator;
use App\Service\Author\Dto\AuthorCreatorDto;
use App\State\Command\Author\CreateManyAuthorsCommand;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class CreateManyAuthorsHandler
{
    public function __construct(
        private AuthorCreator $authorCreator,
        private AuthorRepositoryInterface $authorRepository
    ) {
    }

    /**
     * @return int[]
     */
    public function __invoke(CreateManyAuthorsCommand $command): array
    {
        $ids = [];
        foreach ($command->authors as $authorCommand) {
            $author = $this->authorCreator->create(new AuthorCreatorDto(
                $authorCommand->firstname,
                $authorCommand->lastname,
            ));
            $this->authorRepository->save($author);

            $ids[] = $author->getId();
        }

        $this->authorRepository->save(null, true);

        return $ids;
    }
}
