<?php

declare(strict_types = 1);

namespace App\State;

interface Command extends Message
{
    /**
     * @param array<int|string, mixed> $data
     */
    public static function fromArray(array $data): self;
}
