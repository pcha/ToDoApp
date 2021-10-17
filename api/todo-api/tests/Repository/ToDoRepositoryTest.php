<?php

namespace App\Tests\Repository;

use App\Entity\Audit;
use App\Entity\ToDo;
use App\Repository\AuditRepository;
use App\Repository\ToDoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\PhpUnit\ClockMock;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use function RectorPrefix20211012\Stringy\create;
use function Webmozart\Assert\Tests\StaticAnalysis\false;
use function Webmozart\Assert\Tests\StaticAnalysis\null;
use function Webmozart\Assert\Tests\StaticAnalysis\true;

/**
 * Class ToDoRepositoryTest
 * @package App\Tests\Repository
 * @group time-sensitive
 */
class ToDoRepositoryTest extends AbstractRepositoryTest
{
    /**
     * @throws \Doctrine\ORM\ORMException
     * @group time-sensitive
     * @dataProvider provideForTestCreate
     */
    public function testCreate(string $title, ?bool $completed=null): void
    {
        $title = "Test task";
        $completed = false;
        $task = $this->toDoRepository->create($title, $completed);
        $this->assertNotNull($task->getId());
        $this->assertEquals($title, $task->getTitle());
        $this->assertEquals($completed??false, $task->getCompleted());

        $this->assertEqualsWithDelta(new \DateTimeImmutable(), $task->getCreatedAt(), 60);
        $this->assertEquals($task, $this->toDoRepository->find($task->getId()));
        $audits = $this->auditRepository->findBy(['task_id' => $task->getId()]);
        self::assertCount(1, $audits);
        AuditRepositoryTest::assertAuditFor($task, $audits[0], AuditRepositoryTest::CREATED);
    }

    /**
     * @return array[] arguments
     */
    public function provideForTestCreate(): array
    {
        return [
            ["Test task", false],
            ["Test task", true],
            ["Test task"],
        ];
    }

    /**
     * @param string|null $title
     * @param bool|null $completed
     * @param bool $startCompleted
     * @throws \Doctrine\ORM\ORMException
     * @dataProvider provideForTestUpdate
     */
    public function testUpdate(?string $title=null, ?bool $completed=null, bool $startCompleted=false): void
    {
        $originalTitle = "Test task";
        $task = $this->toDoRepository->create($originalTitle, $startCompleted);

        $this->toDoRepository->update($task, title: $title, completed: $completed);
        $this->assertEqualsWithDelta(new \DateTimeImmutable(), $task->getUpdatedAt(), 60);
        $this->assertEquals($title ?? $originalTitle, $task->getTitle());
        $this->assertEquals($completed ?? $startCompleted, $task->getCompleted());
        $this->assertEquals($task, $this->toDoRepository->find($task->getId()));

        $audit = $this->auditRepository->findOneBy(['task_id' => $task->getId()], ['id'=> 'desc']);
        AuditRepositoryTest::assertAuditFor($task, $audit, AuditRepositoryTest::UPDATED);
    }

    /**
     * @return array[]
     */
    public function provideForTestUpdate(): array
    {
        return [
            [null, true],
            ["New title", null],
            [null, false, true],
            ["new title", true],
        ];
    }

    public function testRemove(): void
    {
        $task = $this->toDoRepository->create("Test Task");
        $taskId = $task->getId();

        $this->toDoRepository->remove($task);

        $this->assertNull($this->toDoRepository->find($taskId));
        $audit = $this->auditRepository->findOneBy(['task_id' => $taskId], ['id' => 'desc']);
        AuditRepositoryTest::assertAuditFor($task, $audit, AuditRepositoryTest::DELETED, $taskId);
    }
}
