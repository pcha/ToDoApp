<?php


namespace App\Tests\Controller\Api;


use App\Entity\ToDo;
use App\Repository\ToDoRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractControllerTest extends WebTestCase
{
    protected AbstractBrowser $client;
    protected ToDoRepository $toDoRepository;

    protected function setUp(): void
    {
        $this->client = $this->createClient();
        parent::setUp();
        $this->toDoRepository = $this->getContainer()
            ->get('doctrine')
            ->getManager()
            ->getRepository(ToDo::class);
    }


    /**
     * @param Response $response
     */
    protected static function assertHttpBadRequest(Response $response): void
    {
        static::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    /**
     * @param Response $response
     */
    protected static function assertHttpNotFound(Response $response): void
    {
        static::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * @param Response $response
     */
    protected static function assertHttpOk(Response $response): void
    {
        static::assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @param Response $response
     */
    protected static function assertHttpCreated(Response $response): void
    {
        static::assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }
}