<?php

declare(strict_types = 1);

namespace App\DataFixtures\Loader;

use App\Entity\Genre;
use Doctrine\Persistence\ObjectManager;
use RuntimeException;

use function is_string;

class GenreFixtureLoader
{
    /**
     * @var array<string, mixed>
     */
    private array $values;

    public function __construct(private readonly ObjectManager $manager)
    {
    }

    public static function create(ObjectManager $manager): self
    {
        return new self($manager);
    }

    public function setName(string $name): self
    {
        return $this->setValue('name', $name);
    }

    public function load(): Genre
    {
        if (!isset($this->values['name']) || !is_string($this->values['name'])) {
            throw new RuntimeException('Name value is empty or not string');
        }

        $genre = new Genre();
        $genre->setName($this->values['name']);

        $this->manager->persist($genre);

        return $genre;
    }

    private function setValue(string $key, mixed $value): self
    {
        $this->values[$key] = $value;

        return $this;
    }
}
