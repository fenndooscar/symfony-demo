<?php

declare(strict_types = 1);

namespace Tests\Infra\Case;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Request as BrowserRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Tests\Infra\Service\ServiceManager;

use function is_null;

abstract class IntegrationTestCase extends WebTestCase
{
    protected JsonEncoder $jsonEncoder;
    protected static ?KernelBrowser $client                 = null;
    protected static ?EntityManagerInterface $entityManager = null;

    /**
     * @throws \Doctrine\DBAL\Exception
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->jsonEncoder = new JsonEncoder();

        $this->buildClient();
        $this->stubAndRegisterServices();

        if (null === self::$entityManager) {
            self::$entityManager = self::getContainer()->get('doctrine')->getManager();
        }

        self::$client->disableReboot();
        self::$entityManager->getConnection()->beginTransaction();
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    protected function tearDown(): void
    {
        self::$entityManager->getConnection()->rollBack();
        self::$entityManager->close();

        self::$client        = null;
        self::$entityManager = null;

        parent::tearDown();
    }

    protected function makeRequest(BrowserRequest $request): Response
    {
        self::$client->request(
            $request->getMethod(),
            $request->getUri(),
            $request->getParameters(),
            $request->getFiles(),
            $request->getServer(),
            $request->getContent()
        );

        return self::$client->getResponse();
    }

    /**
     * @template T
     *
     * @param class-string<T> $name
     *
     * @throws Exception
     *
     * @return T
     */
    protected function getFromContainerByClassName(string $name): object
    {
        $object = self::getContainer()->get($name);
        if (is_null($object)) {
            throw new RuntimeException('Cannot fetch from container object named: ' . $name);
        }

        return $object;
    }

    protected function responseSuccessTest(Response $response, int $responseCode = Response::HTTP_OK): void
    {
        if (Response::HTTP_NO_CONTENT !== $responseCode) {
            $responseData = $this->jsonEncoder->decode($response->getContent(), $this->jsonEncoder::FORMAT);

            self::assertArrayHasKey('meta', $responseData);
            self::assertArrayHasKey('success', $responseData['meta']);
            self::assertTrue($responseData['meta']['success']);
            self::assertArrayHasKey('data', $responseData);
        }

        self::assertSame($responseCode, $response->getStatusCode(), 'raw message: ' . $response->getContent());
    }

    protected function responseFailureTest(Response $response, int $responseCode = Response::HTTP_BAD_REQUEST): void
    {
        $responseData = $this->jsonEncoder->decode($response->getContent(), $this->jsonEncoder::FORMAT);

        self::assertEquals($responseCode, $response->getStatusCode());
        self::assertFalse($responseData['meta']['success']);
        self::assertArrayHasKey('error', $responseData);
        self::assertArrayHasKey('message', $responseData['error']);
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        if (!self::$entityManager instanceof EntityManagerInterface) {
            throw new RuntimeException('$entityManager is null.');
        }

        return self::$entityManager;
    }

    protected function clearDB(EntityManagerInterface $entityManager, string $entityClass): void
    {
        $entityManager->createQueryBuilder()
            ->delete($entityClass, 'entity')
            ->getQuery()
            ->execute();
    }

    private function buildClient(): void
    {
        self::ensureKernelShutdown();

        if (null === self::$client) {
            self::$client = self::createClient();
        }

        self::$client->setServerParameter('CONTENT_TYPE', 'application/json');
    }

    private function stubAndRegisterServices(): void
    {
        ServiceManager::registerStubbedServices(
            self::getContainer(),
            [
            ]
        );
    }
}
