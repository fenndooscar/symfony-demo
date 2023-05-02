<?php

declare(strict_types = 1);

namespace Tests\Infra\Service;

use PHPUnit\Framework\MockObject\Stub\Stub;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ServiceManager
{
    /**
     * @param array<Stub> $stubs
     */
    public static function registerStubbedServices(ContainerInterface $container, array $stubs): void
    {
        foreach ($stubs as $name => $stub) {
            $container->set($name, $stub);
        }
    }
}
