<?php

declare(strict_types = 1);

namespace App\State\CommandHandler\Author;

use App\Repository\AuthorRepositoryInterface;
use App\Service\Author\AuthorCreator;
use App\Service\Author\Dto\AuthorCreatorDto;
use App\State\Command\Author\CreateOneAuthorCommand;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class CreateOneAuthorHandler
{
    public function __construct(
        private AuthorCreator $authorCreator,
        private AuthorRepositoryInterface $authorRepository
    ) {
    }

    public function __invoke(CreateOneAuthorCommand $command): int
    {
        $author = $this->authorCreator->create(new AuthorCreatorDto(
            $command->firstname,
            $command->lastname,
        ));
        $this->authorRepository->save($author);

        return $author->getId();
    }
}
