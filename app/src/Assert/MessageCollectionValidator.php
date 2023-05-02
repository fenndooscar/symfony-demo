<?php

declare(strict_types = 1);

namespace App\Assert;

use App\Exception\MessageValidateException;
use App\State\Message;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Exception\RuntimeException;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use function is_iterable;
use function sprintf;

class MessageCollectionValidator extends ConstraintValidator
{
    public function __construct(private readonly ValidatorInterface $validator)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof MessageCollection) {
            return;
        }

        if (!is_iterable($value)) {
            throw new RuntimeException('$value must be iterable.');
        }

        if (empty($value)) {
            return;
        }

        foreach ($value as $item) {
            if (!$item instanceof Message) {
                $message = sprintf('$item must be of type %s', Message::class);

                throw new ValidationFailedException($item, ConstraintViolationList::createFromMessage($message));
            }

            if (($violations = $this->validator->validate($item))->count() > 0) {
                throw new MessageValidateException($item, $violations);
            }
        }
    }
}
