<?php

declare(strict_types = 1);

namespace Tests\Functional\Integration\Api;

use App\DataFixtures\Loader\AuthorFixtureLoader;
use App\Entity\Author;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\BrowserKit\Request as BrowserRequest;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Tests\Infra\Case\IntegrationTestCase;

use function json_decode;
use function json_encode;
use function sprintf;

class V1AuthorSuccessTests extends IntegrationTestCase
{
    private Generator $faker;
    private EntityManagerInterface $manager;

    public function setUp(): void
    {
        parent::setUp();

        $this->faker   = Factory::create();
        $this->manager = $this->getEntityManager();

        $this->clearDB($this->manager, Author::class);
    }

    public function test_show_success(): void
    {
        $loader = new AuthorFixtureLoader($this->manager);

        $loader->setFirstname($this->faker->firstName());
        $loader->setLastname($this->faker->lastName());
        $author = $loader->load();

        $this->manager->flush();

        $response = self::makeRequest(new BrowserRequest(
            sprintf('/api/v1/authors/show/%d', $author->getId()),
            HttpRequest::METHOD_GET,
        ));

        $this->responseSuccessTest($response);

        $expected = [
            'id'        => $author->getId(),
            'firstname' => $author->getFirstname(),
            'lastname'  => $author->getLastname(),
            'createdAt' => $author->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => $author->getCreatedAt()->format(DateTimeInterface::ATOM),
            'books'     => [],
        ];

        $content = json_decode($response->getContent(), true);
        $this->assertEquals($expected, $content['data']);
    }

    public function test_list_success(): void
    {
        $loader = new AuthorFixtureLoader($this->manager);

        $loader->setFirstname($this->faker->firstName());
        $loader->setLastname($this->faker->lastName());
        $author1 = $loader->load();

        $loader->setFirstname($this->faker->firstName());
        $loader->setLastname($this->faker->lastName());
        $author2 = $loader->load();

        $this->manager->flush();

        $limit  = 10;
        $offset = 0;

        $response = self::makeRequest(new BrowserRequest(
            '/api/v1/authors/list',
            HttpRequest::METHOD_GET,
            ['limit' => $limit, 'offset' => $offset]
        ));

        $this->responseSuccessTest($response);

        $expected = [
            [
                'id'        => $author1->getId(),
                'firstname' => $author1->getFirstname(),
                'lastname'  => $author1->getLastname(),
                'createdAt' => $author1->getCreatedAt()->format(DateTimeInterface::ATOM),
                'updatedAt' => $author1->getCreatedAt()->format(DateTimeInterface::ATOM),
                'books'     => [],
            ],
            [
                'id'        => $author2->getId(),
                'firstname' => $author2->getFirstname(),
                'lastname'  => $author2->getLastname(),
                'createdAt' => $author2->getCreatedAt()->format(DateTimeInterface::ATOM),
                'updatedAt' => $author2->getCreatedAt()->format(DateTimeInterface::ATOM),
                'books'     => [],
            ],
        ];

        $content = json_decode($response->getContent(), true);
        $this->assertEquals($expected, $content['data']);
    }

    public function test_create_success(): void
    {
        $loader = new AuthorFixtureLoader($this->manager);

        $loader->setFirstname($this->faker->firstName());
        $loader->setLastname($this->faker->lastName());
        $author = $loader->load(false);

        $response = self::makeRequest(new BrowserRequest(
            '/api/v1/authors/create',
            HttpRequest::METHOD_POST,
            content: json_encode(['authors' => [[
                'firstname' => $author->getFirstname(),
                'lastname'  => $author->getLastname(),
                'createdAt' => $author->getCreatedAt()->format(DateTimeInterface::ATOM),
                'updatedAt' => $author->getCreatedAt()->format(DateTimeInterface::ATOM),
            ]]])
        ));

        $this->responseSuccessTest($response, HttpResponse::HTTP_CREATED);
    }

    public function test_update_success(): void
    {
        $loader = new AuthorFixtureLoader($this->manager);

        $loader->setFirstname($this->faker->firstName());
        $loader->setLastname($this->faker->lastName());
        $oldAuthor = $loader->load();

        $this->manager->flush();

        $loader->setFirstname($this->faker->firstName());
        $loader->setLastname($this->faker->lastName());
        $newAuthor = $loader->load(false);

        $response = self::makeRequest(new BrowserRequest(
            '/api/v1/authors/update',
            HttpRequest::METHOD_PATCH,
            content: json_encode(['authors' => [[
                'id'        => $oldAuthor->getId(),
                'firstname' => $newAuthor->getFirstname(),
                'lastname'  => $newAuthor->getLastname(),
                'createdAt' => $newAuthor->getCreatedAt()->format(DateTimeInterface::ATOM),
                'updatedAt' => $newAuthor->getCreatedAt()->format(DateTimeInterface::ATOM),
            ]]])
        ));

        $this->responseSuccessTest($response);
    }

    public function test_delete_success(): void
    {
        $loader = new AuthorFixtureLoader($this->manager);

        $loader->setFirstname($this->faker->firstName());
        $loader->setLastname($this->faker->lastName());
        $author = $loader->load();
        $this->manager->flush();

        $response = self::makeRequest(new BrowserRequest(
            '/api/v1/authors/delete',
            HttpRequest::METHOD_DELETE,
            content: json_encode([
                'authors' => [
                    ['id' => $author->getId()],
                ],
            ])
        ));

        $this->responseSuccessTest($response, HttpResponse::HTTP_NO_CONTENT);
    }
}
