<?php

namespace App\Tests\Entity;

use App\Entity\ToDo;
use Doctrine\ORM\EntityManagerInterface;
use RectorPrefix20211012\Nette\Utils\DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ToDoPhpTest extends KernelTestCase
{
    public function testSetCreatedAtOnCreation(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        /**
         * @var EntityManagerInterface $em
         */
        $em = $container->get(EntityManagerInterface::class);

        $task = new ToDo();
        $task->setTitle("Test task");
        $task->setCompleted(false);
        $em->persist($task);
        $em->flush();

        $this->assertEqualsWithDelta(new \DateTimeImmutable(), $task->getCreatedAt(), 60);
    }
}
