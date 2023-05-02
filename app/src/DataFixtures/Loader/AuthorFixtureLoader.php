<?php

declare(strict_types = 1);

namespace App\DataFixtures\Loader;

use App\Entity\Author;
use Doctrine\Persistence\ObjectManager;
use RuntimeException;

use function is_string;

class AuthorFixtureLoader
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

    public function setFirstname(string $firstname): self
    {
        return $this->setValue('firstname', $firstname);
    }

    public function setLastname(string $lastname): self
    {
        return $this->setValue('lastname', $lastname);
    }

    public function load(bool $persist = true): Author
    {
        if (!isset($this->values['firstname']) || !is_string($this->values['firstname'])) {
            throw new RuntimeException('Firstname value is empty or not string');
        }

        if (!isset($this->values['lastname']) || !is_string($this->values['lastname'])) {
            throw new RuntimeException('Lastname value is empty or not string');
        }

        $author = new Author();
        $author->setFirstname($this->values['firstname']);
        $author->setLastname($this->values['lastname']);

        if (true === $persist) {
            $this->manager->persist($author);
        }

        return $author;
    }

    private function setValue(string $key, mixed $value): self
    {
        $this->values[$key] = $value;

        return $this;
    }
}
