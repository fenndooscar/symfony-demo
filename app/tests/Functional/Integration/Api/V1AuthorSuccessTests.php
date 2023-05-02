<?php

declare(strict_types = 1);

namespace Tests\Functional\Integration\Api;

use App\DataFixtures\Loader\AuthorFixtureLoader;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Generator;
use RuntimeException;
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

    public function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();
    }

    public function test_show_success(): void
    {
        $entityManager = $this->getEntityManager();
        $loader        = new AuthorFixtureLoader($entityManager);

        $loader->setFirstname($this->faker->firstName());
        $loader->setLastname($this->faker->lastName());
        $author = $loader->load();

        $entityManager->flush();

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
        ];

        $content = json_decode($response->getContent(), true);
        $this->assertEquals($expected, $content);
    }

    public function test_list_success(): void
    {
        $entityManager = $this->getEntityManager();
        $loader        = new AuthorFixtureLoader($entityManager);

        $loader->setFirstname($this->faker->firstName());
        $loader->setLastname($this->faker->lastName());
        $author1 = $loader->load();

        $loader->setFirstname($this->faker->firstName());
        $loader->setLastname($this->faker->lastName());
        $author2 = $loader->load();

        $entityManager->flush();

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
            ],
            [
                'id'        => $author2->getId(),
                'firstname' => $author2->getFirstname(),
                'lastname'  => $author2->getLastname(),
                'createdAt' => $author2->getCreatedAt()->format(DateTimeInterface::ATOM),
                'updatedAt' => $author2->getCreatedAt()->format(DateTimeInterface::ATOM),
            ],
        ];

        $content = json_decode($response->getContent(), true);
        $this->assertEquals($expected, $content['data']);
    }

    public function test_create_success(): void
    {
        $entityManager = $this->getEntityManager();
        $loader        = new AuthorFixtureLoader($entityManager);

        $loader->setFirstname($this->faker->firstName());
        $loader->setLastname($this->faker->lastName());
        $author = $loader->load(false);

        $response = self::makeRequest(new BrowserRequest(
            '/api/v1/authors/create',
            HttpRequest::METHOD_POST,
            content: json_encode([
                'firstname' => $author->getFirstname(),
                'lastname'  => $author->getLastname(),
                'createdAt' => $author->getCreatedAt()->format(DateTimeInterface::ATOM),
                'updatedAt' => $author->getCreatedAt()->format(DateTimeInterface::ATOM),
            ])
        ));

        $this->responseSuccessTest($response, HttpResponse::HTTP_CREATED);
    }

    public function test_update_success(): void
    {
    }

    public function test_delete_success(): void
    {
    }

    private function getEntityManager(): EntityManagerInterface
    {
        if (!self::$entityManager instanceof EntityManagerInterface) {
            throw new RuntimeException('$entityManager is null.');
        }

        return self::$entityManager;
    }
}
