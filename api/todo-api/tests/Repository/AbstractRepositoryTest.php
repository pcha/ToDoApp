<?php


namespace App\Tests\Repository;


use App\Entity\Audit;
use App\Entity\ToDo;
use App\Repository\AuditRepository;
use App\Repository\ToDoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class AbstractRepositoryTest extends KernelTestCase
{
    protected AuditRepository $auditRepository;
    protected ToDoRepository $toDoRepository;
    protected ?EntityManagerInterface $entityManager;

    public function setUp(): void
    {
        $this->entityManager = $this->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->toDoRepository = $this->entityManager->getRepository(ToDo::class);
        $this->auditRepository = $this->entityManager->getRepository(Audit::class);
    }
}