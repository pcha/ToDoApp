<?php

namespace App\Tests\Controller\Api;

use App\Entity\ToDo;
use App\Repository\ToDoRepository;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function Webmozart\Assert\Tests\StaticAnalysis\false;
use function Webmozart\Assert\Tests\StaticAnalysis\true;

class ToDoControllerTest extends AbstractControllerTest
{

    #[ArrayShape(['id' => "null", 'title' => "string", 'completed' => "bool", 'createdAt' => "null|string", 'updatedAt' => "null|string"])]
    protected static function getTaskAsJson(ToDo $task): array
    {
        return [
            'id' => $task->getId(),
            'title' => $task->getTitle(),
            'completed' => $task->getCompleted(),
            'createdAt' => $task->getCreatedAt()?->format('c'),
            'updatedAt' => $task->getUpdatedAt()?->format('c')
        ];
    }

    protected function getTaskArrayAsJson(array $tasks): array
    {
        return array_map(fn($t) => $this->getTaskAsJson($t), $tasks);
    }

    public function testIndex(): void
    {
        $task1 = $this->toDoRepository->create("Test task 1");
        $task2 = $this->toDoRepository->create("Test task 2");
        $task3 = $this->toDoRepository->create("Test task 3", true);

        $expectedResponse = $this->getTaskArrayAsJson([$task1, $task2, $task3]);

        $this->client->jsonRequest('GET', '/api/todo');
        $response = $this->client->getResponse();

        $this->assertTrue($response->isOk());
        $this->assertJson($response->getContent());
        $this->assertJsonStringEqualsJsonString(json_encode($expectedResponse), $response->getContent());
    }

    public function testShowOk(): void
    {
        $task  = $this->toDoRepository->create("Test task");

        $this->client->jsonRequest('GET', "/api/todo/{$task->getId()}");
        $response = $this->client->getResponse();

        $expected = $this->getTaskAsJson($task);

        $this->assertTrue($response->isOk());
        $this->assertJson($response->getContent());
        $this->assertJsonStringEqualsJsonString(json_encode($expected), $response->getContent());
    }

    public function testShowNotFound(): void
    {
        $this->client->jsonRequest('GET', "/api/todo/0");
        $response = $this->client->getResponse();

        $this->assertHttpNotFound($response);
    }

    /**
     * @param array $reqBody
     * @param bool $badRequest
     * @dataProvider provideRequestBodies
     */
    public function testCreate(array $reqBody, bool $badRequest=false): void
    {
        $this->client->jsonRequest('POST', '/api/todo', $reqBody);
        $response = $this->client->getResponse();
        $task = $this->toDoRepository->findOneBy([], orderBy: ['id' => 'desc']);

        if ($badRequest) {
            $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
            return;
        }

        $this->assertDataImpactedToTask($reqBody, $task);
        $this->assertHttpCreated($response);
        $this->assertContentRepresentsTask($task, $response->getContent());
    }

    public function provideRequestBodies(): array
    {
        return [
            [[
                'title' => 'Test task',
                'completed' => true,
            ]],
            [[
                'title' => 'Test task',
                'completed' => false,
            ]],
            [[
                'title' => 'Test task',
            ]],
            [['completed' => true], true],
            [[], true],
            [[
                'title' => 'Test task',
                'completed' => false,
                'other_key' => 'come value',
            ], false],
        ];
    }

    /**
     * @param string $taskToEdit
     * @param array $reqBody
     * @param bool $badRequest
     * @throws \App\Exceptions\InvalidActionException
     * @throws \Doctrine\ORM\ORMException
     * @dataProvider provideForTestUpdate
     */
    public function testUpdate(string $taskToEdit, array $reqBody, bool $badRequest=false): void
    {
        $completedTask = $this->toDoRepository->create('Completed task', completed: true);
        $uncompletedTask = $this->toDoRepository->create('Ucompleted task', completed: false);

        $taskId = [
            'nonexistent' => 0,
            'completed' => $completedTask->getId(),
            'uncompleted' => $uncompletedTask->getId(),
        ][$taskToEdit];

        $this->client->jsonRequest('PUT', "/api/todo/$taskId", $reqBody);
        $response = $this->client->getResponse();


        if ($taskToEdit == 'nonexistent') {
            $this->assertHttpNotFound($response);
            return;
        }

        if ($badRequest) {
            $this->assertHttpBadRequest($response);
            return;
        }

        $task = $this->toDoRepository->find($taskId);
        $this->assertDataImpactedToTask($reqBody, $task);
        $this->assertHttpOk($response);
        $this->assertContentRepresentsTask($task, $response->getContent());

    }

    /**
     * @return array
     */
    public function provideForTestUpdate(): array
    {
        return array_merge(
            array_map(fn($args) => array_merge(['nonexistent'], $args), $this->provideRequestBodies()),
            array_map(fn($args) => array_merge(['completed'], $args), $this->provideRequestBodies()),
            array_map(fn($args) => array_merge(['uncompleted'], $args), $this->provideRequestBodies()),
        );
    }

    public function testDeleteOk(): void
    {
        $task = $this->toDoRepository->create('Test task');
        $taskId = $task->getId();

        $this->client->jsonRequest(Request::METHOD_DELETE, "/api/todo/{$taskId}");
        $response = $this->client->getResponse();

        $this->assertHttpOk($response);
        $this->assertNull($this->toDoRepository->find($taskId));
    }


    public function testDeleteNotFound(): void
    {
        $this->client->jsonRequest(Request::METHOD_DELETE, "/api/todo/0");
        $response = $this->client->getResponse();

        $this->assertHttpNotFound($response);
    }

    protected static function assertDataImpactedToTask(array $reqBody, ToDo $task): void
    {
        static::assertEquals($reqBody['title'], $task->getTitle());
        static::assertEquals($reqBody['completed'] ?? false, $task->getCompleted());
    }

    protected static function assertContentRepresentsTask(ToDo $task, string $content): void
    {
        static::assertJson($content);
        static::assertJsonStringEqualsJsonString(json_encode(static::getTaskAsJson($task)), $content);
    }
}
