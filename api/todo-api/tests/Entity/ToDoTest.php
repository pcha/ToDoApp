<?php
//
//namespace App\Tests\Entity;
//
//use App\Entity\Audit;
//use App\Entity\ToDo;
//use Doctrine\ORM\EntityManagerInterface;
//use RectorPrefix20211012\Nette\Utils\DateTime;
//use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
//use Symfony\Component\DependencyInjection\ContainerInterface;
//use function Webmozart\Assert\Tests\StaticAnalysis\startsWithLetter;
//
//class ToDoTest extends KernelTestCase
//{
//    /**
//     * @var EntityManagerInterface
//     */
//    protected $entityManager;
//
//    protected function setUp(): void
//    {
//        self::bootKernel();
//        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
//    }
////
////    public function testSetCreatedAtOnCreation(): void
////    {
////        $task = new ToDo();
////        $task->setTitle("Test task");
////        $task->setCompleted(false);
////        $this->entityManager->persist($task);
////        $this->entityManager->flush();
////
////        $this->assertEqualsWithDelta(new \DateTimeImmutable(), $task->getCreatedAt(), 60);
////        dump($this->entityManager->getRepository(Audit::class)->findAll());
////    }
////
////    public function testSetUpdatedAtOnUpdate(): void
////    {
////        // Given
////        $todo = new ToDo();
////        $todo->setTitle("test task");
////        $this->entityManager->persist($todo);
////        $this->entityManager->flush();
////
////        // When
////        /**
////         * @var ToDo $todo
////         */
////        $todo = $this->entityManager->getRepository(ToDo::class)->findOneBy([]);
////        $todo->setCompleted(true);
////        $this->entityManager->persist($todo);
////        $this->entityManager->flush();
////
////        // Then
////        $this->assertEqualsWithDelta(new \DateTimeImmutable(), $todo->getUpdatedAt(), 60);
////    }
//}
