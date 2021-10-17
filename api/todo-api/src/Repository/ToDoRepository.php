<?php

namespace App\Repository;

use App\Entity\Audit;
use App\Entity\ToDo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use http\Encoding\Stream\Enbrotli;
use function Webmozart\Assert\Tests\StaticAnalysis\false;
use function Webmozart\Assert\Tests\StaticAnalysis\null;

/**
 * @method ToDo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ToDo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ToDo[]    findAll()
 * @method ToDo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ToDoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ToDo::class);
    }

    /**
     * @throws ORMException
     * @throws \App\Exceptions\InvalidActionException
     */
    public function create(string $title, bool $completed=false): ToDo
    {
        $task = new ToDo();
        $task->setTitle($title)
            ->setCompleted($completed)
            ->setCreatedAt(new \DateTimeImmutable());
        $this->getEntityManager()->persist($task);
        $this->getEntityManager()->flush();
        $this->createAudit(AuditRepository::ACTION_CREATE, $task);
        return $task;
    }

    /**
     * @param ToDo $task
     * @param string|null $title
     * @param bool|null $completed
     * @throws ORMException
     * @throws \App\Exceptions\InvalidActionException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(ToDo $task, ?string $title=null, ?bool $completed=null): void
    {
        if (null !== $title) $task->setTitle($title);
        if (null !== $completed) $task->setCompleted($completed);
        $task->setUpdatedAt(new \DateTimeImmutable());
        $this->getEntityManager()->persist($task);

        $this->createAudit(AuditRepository::ACTION_UPDATE, $task);
    }

    /**
     * @param ToDo $task
     * @throws ORMException
     * @throws \App\Exceptions\InvalidActionException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function remove(ToDo $task):void
    {
        $this->getEntityManager()->remove($task);

        $this->createAudit(AuditRepository::ACTION_DELETE, $task);
    }

    /**
     * @param string $action
     * @param ToDo $task
     * @throws ORMException
     * @throws \App\Exceptions\InvalidActionException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function createAudit(string $action, ToDo $task): void
    {
        /**
         * @var AuditRepository $auditRepo
         */
        $auditRepo = $this->getEntityManager()->getRepository(Audit::class);
        $auditRepo->createFor($action, $task);
    }
}
