<?php

declare(strict_types = 1);

namespace App\State;

use App\Exception\MessageValidateException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

use function array_shift;
use function count;

final class State
{
    use HandleTrait;

    public function __construct(
        private readonly ValidatorInterface $validator,
        MessageBusInterface $messageBus
    ) {
        $this->messageBus = $messageBus;
    }

    /**
     * @throws MessageValidateException
     * @throws Throwable
     */
    public function command(Command $command): mixed
    {
        return $this->dispatch($command);
    }

    /**
     * @throws MessageValidateException
     * @throws Throwable
     */
    public function query(Query $query): mixed
    {
        return $this->dispatch($query);
    }

    /**
     * @throws MessageValidateException
     * @throws Throwable
     */
    private function dispatch(Message $message): mixed
    {
        if (($violations = $this->validator->validate($message))->count() > 0) {
            throw new MessageValidateException($message, $violations);
        }

        try {
            return $this->handle($message);
        } catch (HandlerFailedException $exception) {
            if (1 === count($exceptions = $exception->getNestedExceptions())) {
                return array_shift($exceptions);
            }

            throw $exception;
        }
    }
}
