<?php

declare(strict_types = 1);

namespace App\Exception;

use Symfony\Component\Messenger\Exception\ValidationFailedException;

class MessageValidateException extends ValidationFailedException
{
}
