<?php

declare(strict_types = 1);

namespace App\Subscriber;

use App\Exception\MessageValidateException;
use App\Http\Response\JsonResponder;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Throwable;

readonly class ExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private string $environment,
        private bool $debug,
        private JsonResponder $responder,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException'],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if ('dev' === $this->environment && true === $this->debug) {
            return;
        }

        $event->setResponse($this->getResponse($event->getThrowable()));
    }

    private function getResponse(Throwable $exception): Response
    {
        switch ($exception) {
            case $exception instanceof EntityNotFoundException:
                return $this->responder->notFound($exception->getMessage());
            case $exception instanceof ValidationFailedException:
            case $exception instanceof MessageValidateException:
                $errors = [];

                foreach ($exception->getViolations() as $violation) {
                    $errors[$violation->getPropertyPath()] = $violation->getMessage();
                }

                return $this->responder->validationFailed($errors);
            default:
                return $this->responder->internalServerError();
        }
    }
}
