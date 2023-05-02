<?php

declare(strict_types = 1);

namespace App\DataFixtures;

use App\DataFixtures\Loader\GenreFixtureLoader;
use App\DataFixtures\Traits\DevFixtureTrait;
use App\Entity\Genre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class GenreFixtures extends Fixture implements FixtureGroupInterface
{
    use DevFixtureTrait;
    public const NOVEL_REFERENCE     = 'novel';
    public const HORROR_REFERENCE    = 'horror';
    public const CHRONICLE_REFERENCE = 'chronicle';
    public const BIOGRAPHY_REFERENCE = 'biography';
    public const THRILLER_REFERENCE  = 'thriller';
    public const COMEDY_REFERENCE    = 'comedy';
    public const PROSE_REFERENCE     = 'prose';
    public const POEMS_REFERENCE     = 'poems';

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager): void
    {
        $loader = GenreFixtureLoader::create($manager);

        $this->loadNovelGenre(clone $loader);
        $this->loadHorrorGenre(clone $loader);
        $this->loadChronicleGenre(clone $loader);
        $this->loadBiographyGenre(clone $loader);
        $this->loadThrillerGenre(clone $loader);
        $this->loadComedyGenre(clone $loader);
        $this->loadProseGenre(clone $loader);
        $this->loadPoemsGenre(clone $loader);

        $manager->flush();
    }

    private function loadNovelGenre(GenreFixtureLoader $loader): Genre
    {
        $genre = $loader->setName('novel')->load();
        $this->addReference(self::NOVEL_REFERENCE, $genre);

        return $genre;
    }

    private function loadHorrorGenre(GenreFixtureLoader $loader): Genre
    {
        $genre = $loader->setName('horror')->load();
        $this->addReference(self::HORROR_REFERENCE, $genre);

        return $genre;
    }

    private function loadChronicleGenre(GenreFixtureLoader $loader): Genre
    {
        $genre = $loader->setName('chronicle')->load();
        $this->addReference(self::CHRONICLE_REFERENCE, $genre);

        return $genre;
    }

    private function loadBiographyGenre(GenreFixtureLoader $loader): Genre
    {
        $genre = $loader->setName('biography')->load();
        $this->addReference(self::BIOGRAPHY_REFERENCE, $genre);

        return $genre;
    }

    private function loadThrillerGenre(GenreFixtureLoader $loader): Genre
    {
        $genre = $loader->setName('thriller')->load();
        $this->addReference(self::THRILLER_REFERENCE, $genre);

        return $genre;
    }

    private function loadComedyGenre(GenreFixtureLoader $loader): Genre
    {
        $genre = $loader->setName('comedy')->load();
        $this->addReference(self::COMEDY_REFERENCE, $genre);

        return $genre;
    }

    private function loadProseGenre(GenreFixtureLoader $loader): Genre
    {
        $genre = $loader->setName('prose')->load();
        $this->addReference(self::PROSE_REFERENCE, $genre);

        return $genre;
    }

    private function loadPoemsGenre(GenreFixtureLoader $loader): Genre
    {
        $genre = $loader->setName('poems')->load();
        $this->addReference(self::POEMS_REFERENCE, $genre);

        return $genre;
    }
}
