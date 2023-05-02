<?php

declare(strict_types = 1);

namespace App\DataFixtures\Loader;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Genre;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;
use RuntimeException;

use function array_unique;
use function is_string;

class BookFixtureLoader
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

    public function setDescription(string $description): self
    {
        return $this->setValue('description', $description);
    }

    public function setAuthor(Author $author): self
    {
        return $this->setValue('author', $author);
    }

    public function addGenre(Genre $genre): self
    {
        $genres   = $this->hasValue('genres') ? (array) $this->getValue('genres') : [];
        $genres[] = $genre;

        return $this->setValue('genres', array_unique($genres));
    }

    public function load(): Book
    {
        if (!$this->hasValue('name') || !($name = $this->getValue('name')) || !is_string($name)) {
            throw new RuntimeException('Name value is empty or not string');
        }

        if (!$this->hasValue('description') || !($description = $this->getValue('description')) || !is_string($description)) {
            throw new RuntimeException('Description value is empty or not string');
        }

        if (!$this->hasValue('author') || !($author = $this->getValue('author')) || !$author instanceof Author) {
            throw new RuntimeException('Author value is empty or not Author type');
        }

        $book = new Book();
        $book
            ->setName($name)
            ->setDescription($description)
            ->setAuthor($author)
            ->setGenres(new ArrayCollection($this->values['genres'] ?? []));

        $this->manager->persist($book);

        return $book;
    }

    private function hasValue(string $key): bool
    {
        return isset($this->values[$key]);
    }

    private function getValue(string $key): mixed
    {
        return $this->values[$key];
    }

    private function setValue(string $key, mixed $value): self
    {
        $this->values[$key] = $value;

        return $this;
    }
}
