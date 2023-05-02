<?php

declare(strict_types = 1);

namespace App\DataFixtures;

use App\DataFixtures\Loader\BookFixtureLoader;
use App\DataFixtures\Traits\DevFixtureTrait;
use App\Entity\Author;
use App\Entity\Genre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use RuntimeException;

use function sprintf;

class BookFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    use DevFixtureTrait;

    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function getDependencies(): array
    {
        return [
            AuthorFixtures::class,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager): void
    {
        $loader = BookFixtureLoader::create($manager);

        $this->load1Book(clone $loader);
        $this->load2Book(clone $loader);
        $this->load3Book(clone $loader);
        $this->load4Book(clone $loader);
        $this->load5Book(clone $loader);
        $this->load6Book(clone $loader);
        $this->load7Book(clone $loader);
        $this->load8Book(clone $loader);
        $this->load9Book(clone $loader);
        $this->load10Book(clone $loader);
        $this->load11Book(clone $loader);
        $this->load12Book(clone $loader);
        $this->load13Book(clone $loader);
        $this->load14Book(clone $loader);

        $manager->flush();
    }

    private function getAuthor(string $reference): Author
    {
        $author = $this->getReference($reference);

        if (!$author instanceof Author) {
            throw new RuntimeException(sprintf('Not found Author by reference: "%s"', $reference));
        }

        return $author;
    }

    private function getGenre(string $reference): Genre
    {
        $genre = $this->getReference($reference);

        if (!$genre instanceof Genre) {
            throw new RuntimeException(sprintf('Not found Genre by reference: "%s"', $reference));
        }

        return $genre;
    }

    private function load1Book(BookFixtureLoader $loader): void
    {
        $loader
            ->setName($this->faker->title())
            ->setDescription($this->faker->realText())
            ->setAuthor($this->getAuthor(AuthorFixtures::AUTHOR_1_REFERENCE))
            ->addGenre($this->getGenre(GenreFixtures::HORROR_REFERENCE))
            ->load();
    }

    private function load2Book(BookFixtureLoader $loader): void
    {
        $loader
            ->setName($this->faker->title())
            ->setDescription($this->faker->realText())
            ->setAuthor($this->getAuthor(AuthorFixtures::AUTHOR_2_REFERENCE))
            ->addGenre($this->getGenre(GenreFixtures::NOVEL_REFERENCE))
            ->load();
    }

    private function load3Book(BookFixtureLoader $loader): void
    {
        $loader
            ->setName($this->faker->title())
            ->setDescription($this->faker->realText())
            ->setAuthor($this->getAuthor(AuthorFixtures::AUTHOR_3_REFERENCE))
            ->addGenre($this->getGenre(GenreFixtures::CHRONICLE_REFERENCE))
            ->load();
    }

    private function load4Book(BookFixtureLoader $loader): void
    {
        $loader
            ->setName($this->faker->title())
            ->setDescription($this->faker->realText())
            ->setAuthor($this->getAuthor(AuthorFixtures::AUTHOR_4_REFERENCE))
            ->addGenre($this->getGenre(GenreFixtures::BIOGRAPHY_REFERENCE))
            ->load();
    }

    private function load5Book(BookFixtureLoader $loader): void
    {
        $loader
            ->setName($this->faker->title())
            ->setDescription($this->faker->realText())
            ->setAuthor($this->getAuthor(AuthorFixtures::AUTHOR_1_REFERENCE))
            ->addGenre($this->getGenre(GenreFixtures::THRILLER_REFERENCE))
            ->load();
    }

    private function load6Book(BookFixtureLoader $loader): void
    {
        $loader
            ->setName($this->faker->title())
            ->setDescription($this->faker->realText())
            ->setAuthor($this->getAuthor(AuthorFixtures::AUTHOR_2_REFERENCE))
            ->addGenre($this->getGenre(GenreFixtures::COMEDY_REFERENCE))
            ->load();
    }

    private function load7Book(BookFixtureLoader $loader): void
    {
        $loader
            ->setName($this->faker->title())
            ->setDescription($this->faker->realText())
            ->setAuthor($this->getAuthor(AuthorFixtures::AUTHOR_3_REFERENCE))
            ->addGenre($this->getGenre(GenreFixtures::THRILLER_REFERENCE))
            ->load();
    }

    private function load8Book(BookFixtureLoader $loader): void
    {
        $loader
            ->setName($this->faker->title())
            ->setDescription($this->faker->realText())
            ->setAuthor($this->getAuthor(AuthorFixtures::AUTHOR_4_REFERENCE))
            ->addGenre($this->getGenre(GenreFixtures::CHRONICLE_REFERENCE))
            ->load();
    }

    private function load9Book(BookFixtureLoader $loader): void
    {
        $loader
            ->setName($this->faker->title())
            ->setDescription($this->faker->realText())
            ->setAuthor($this->getAuthor(AuthorFixtures::AUTHOR_1_REFERENCE))
            ->addGenre($this->getGenre(GenreFixtures::COMEDY_REFERENCE))
            ->load();
    }

    private function load10Book(BookFixtureLoader $loader): void
    {
        $loader
            ->setName($this->faker->title())
            ->setDescription($this->faker->realText())
            ->setAuthor($this->getAuthor(AuthorFixtures::AUTHOR_2_REFERENCE))
            ->addGenre($this->getGenre(GenreFixtures::BIOGRAPHY_REFERENCE))
            ->load();
    }

    private function load11Book(BookFixtureLoader $loader): void
    {
        $loader
            ->setName($this->faker->title())
            ->setDescription($this->faker->realText())
            ->setAuthor($this->getAuthor(AuthorFixtures::AUTHOR_3_REFERENCE))
            ->addGenre($this->getGenre(GenreFixtures::HORROR_REFERENCE))
            ->load();
    }

    private function load12Book(BookFixtureLoader $loader): void
    {
        $loader
            ->setName($this->faker->title())
            ->setDescription($this->faker->realText())
            ->setAuthor($this->getAuthor(AuthorFixtures::AUTHOR_4_REFERENCE))
            ->addGenre($this->getGenre(GenreFixtures::NOVEL_REFERENCE))
            ->load();
    }

    private function load13Book(BookFixtureLoader $loader): void
    {
        $loader
            ->setName($this->faker->title())
            ->setDescription($this->faker->realText())
            ->setAuthor($this->getAuthor(AuthorFixtures::AUTHOR_1_REFERENCE))
            ->addGenre($this->getGenre(GenreFixtures::PROSE_REFERENCE))
            ->load();
    }

    private function load14Book(BookFixtureLoader $loader): void
    {
        $loader
            ->setName($this->faker->title())
            ->setDescription($this->faker->realText())
            ->setAuthor($this->getAuthor(AuthorFixtures::AUTHOR_2_REFERENCE))
            ->addGenre($this->getGenre(GenreFixtures::POEMS_REFERENCE))
            ->load();
    }
}
