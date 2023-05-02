<?php

declare(strict_types = 1);

namespace App\DataFixtures\Traits;

trait DevFixtureTrait
{
    /**
     * @return string[]
     */
    public static function getGroups(): array
    {
        return [
            'dev',
        ];
    }
}
