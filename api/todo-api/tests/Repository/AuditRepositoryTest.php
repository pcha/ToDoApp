<?php

namespace App\Tests\Repository;

use App\Entity\Audit;
use App\Entity\ToDo;
use App\Exceptions\InvalidActionException;
use App\Repository\AuditRepository;
use App\Repository\ToDoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Tests\ORM\Functional\Ticket\DateTime2;
use phpDocumentor\Reflection\Types\Self_;
use Symfony\Bridge\PhpUnit\ClockMock;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use function Patchwork\redefine;
use function Webmozart\Assert\Tests\StaticAnalysis\false;
use function Webmozart\Assert\Tests\StaticAnalysis\null;

/**
 * Class AuditRepositoryTest
 * @package App\Tests\Repository
 * @group time-sensitive
 */
class AuditRepositoryTest extends AbstractRepositoryTest
{
    const MOCKED_TODO_ID = 1;
    const MOCKED_TODO_TITLE = 'Mocked Task';
    const CREATED = "Created";
    const UPDATED = "Updated";
    const DELETED = "Deleted";

    protected function getMockedToDoTask(): ToDo
    {
        $task = $this->createMock(ToDo::class);
        $task->method('getId')->willReturn(self::MOCKED_TODO_ID);
        $task->method('getTitle')->willReturn(self::MOCKED_TODO_TITLE);
        return $task;
    }

    /**
     * @group time-sensitive
     * @dataProvider  providerForTestCreatedFor
     */
    public function testCreateFor(string $action, ?string $expectedDescriptionPrefix, bool $exceptionExpected = false): void
    {
        $todo = $this->getMockedToDoTask();
        try {
            $audit = $this->auditRepository->createFor($action, $todo);
            $this->assertFalse($exceptionExpected, "Exception expected");
            $this->assertAuditFor($todo, $audit, $expectedDescriptionPrefix);
            $this->assertEquals($audit, $this->auditRepository->find($audit->getId()));
        } catch (InvalidActionException $e) {
            $this->assertTrue($exceptionExpected, "Exception " . $e::class . " with message {$e->getMessage()} not expected");
        }
    }

    /**
     * @return array[] arguments
     */
    public function providerForTestCreatedFor(): array
    {
        return [
            [AuditRepository::ACTION_CREATE, self::CREATED],
            [AuditRepository::ACTION_UPDATE, self::UPDATED],
            [AuditRepository::ACTION_DELETE, self::DELETED],
            ["Unexistent action", null, true],
        ];
    }

    /**
     * @param array $expected
     * @param int|null $sortByTask
     * @throws InvalidActionException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @dataProvider provideForTestFindWithExistentTasks
     */
    public function testFindWithExistentTasks(array $expected, ?string $sortByTask=null): void
    {
        $task1 = $this->toDoRepository->create("Test task 1");
        $task2 = $this->toDoRepository->create("Test task 2");
        $task3 = $this->toDoRepository->create("Test task 3");
        $task4 = $this->toDoRepository->create("Test Task 4");
        $this->toDoRepository->update($task4, title: "Test task 4");
        $this->toDoRepository->update($task2, completed: true);
        $this->toDoRepository->update($task3, completed: true);
        $this->toDoRepository->remove($task3);

        $sort = [];
        if ($sortByTask) {
            $sort = ['task_id' => $sortByTask];
        }
        $audits = $this->auditRepository->findWithExistentTasks($sort);

        $this->assertCount(count($expected), $audits);
        foreach ($audits as $i => $audit) {
            $taskVarname = "task{$expected[$i]['taskNumber']}";
            $this->assertEquals($$taskVarname->getId(), $audit->getTaskId());
            $this->assertEquals($expected[$i]['action'], $audit->getAction());
        }
    }

    public function provideForTestFindWithExistentTasks(): array
    {
        $task1Creation = ['taskNumber' => 1, 'action' => AuditRepository::ACTION_CREATE];
        $task2Creation = ['taskNumber' => 2, 'action' => AuditRepository::ACTION_CREATE];
        $task4Creation = ['taskNumber' => 4, 'action' => AuditRepository::ACTION_CREATE];
        $task4Update = ['taskNumber' => 4, 'action' => AuditRepository::ACTION_UPDATE];
        $task2Update = ['taskNumber' => 2, 'action' => AuditRepository::ACTION_UPDATE];

        return [
            [
                [
                    $task1Creation,
                    $task2Creation,
                    $task4Creation,
                    $task4Update,
                    $task2Update,
                ],
            ],
            [
                [
                    $task1Creation,
                    $task2Creation,
                    $task2Update,
                    $task4Creation,
                    $task4Update,
                ], 'asc'
            ],
            [
                [
                    $task4Creation,
                    $task4Update,
                    $task2Creation,
                    $task2Update,
                    $task1Creation,
                ], 'desc'
            ]
        ];
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }

    /**
     *
     * @param ToDo $task
     * @param Audit $audit
     * @param string|null $expectedDescriptionPrefix
     * @param string $time
     */
    public static function assertAuditFor(\App\Entity\ToDo $task, Audit $audit, ?string $expectedDescriptionPrefix, ?int $taskId=null): void
    {
        static::assertEquals($taskId ?? $task->getId(), $audit->getTaskId());
        $descriptionParts = explode(' ', $audit->getDescription());
        $descrTime = array_pop($descriptionParts);
        $descrWithoutTime = implode(' ', $descriptionParts);
        static::assertEquals("$expectedDescriptionPrefix the task \"{$task->getTitle()}\" at", $descrWithoutTime);
        static::assertEqualsWithDelta(new \DateTimeImmutable(), \DateTimeImmutable::createFromFormat("H:i", $descrTime), 60);
    }
}
