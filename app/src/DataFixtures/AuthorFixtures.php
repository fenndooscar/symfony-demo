<?php

declare(strict_types = 1);

namespace App\DataFixtures;

use App\DataFixtures\Loader\AuthorFixtureLoader;
use App\DataFixtures\Traits\DevFixtureTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AuthorFixtures extends Fixture implements FixtureGroupInterface
{
    use DevFixtureTrait;
    public const AUTHOR_1_REFERENCE = 'author-1';
    public const AUTHOR_2_REFERENCE = 'author-2';
    public const AUTHOR_3_REFERENCE = 'author-3';
    public const AUTHOR_4_REFERENCE = 'author-4';

    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager): void
    {
        $loader = AuthorFixtureLoader::create($manager);

        $this->load1Author(clone $loader);
        $this->load2Author(clone $loader);
        $this->load3Author(clone $loader);
        $this->load4Author(clone $loader);

        $manager->flush();
    }

    private function load1Author(AuthorFixtureLoader $loader): void
    {
        $author = $loader
            ->setFirstname($this->faker->firstName())
            ->setLastname($this->faker->lastName())
            ->load();

        $this->addReference(self::AUTHOR_1_REFERENCE, $author);
    }

    private function load2Author(AuthorFixtureLoader $loader): void
    {
        $author = $loader
            ->setFirstname($this->faker->firstName())
            ->setLastname($this->faker->lastName())
            ->load();

        $this->addReference(self::AUTHOR_2_REFERENCE, $author);
    }

    private function load3Author(AuthorFixtureLoader $loader): void
    {
        $author = $loader
            ->setFirstname($this->faker->firstName())
            ->setLastname($this->faker->lastName())
            ->load();

        $this->addReference(self::AUTHOR_3_REFERENCE, $author);
    }

    private function load4Author(AuthorFixtureLoader $loader): void
    {
        $author = $loader
            ->setFirstname($this->faker->firstName())
            ->setLastname($this->faker->lastName())
            ->load();

        $this->addReference(self::AUTHOR_4_REFERENCE, $author);
    }
}
