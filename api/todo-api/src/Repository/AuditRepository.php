<?php

namespace App\Repository;

use App\Entity\Audit;
use App\Entity\ToDo;
use App\Exceptions\InvalidActionException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use function Webmozart\Assert\Tests\StaticAnalysis\null;

/**
 * @method Audit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Audit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Audit[]    findAll()
 * @method Audit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Audit[]    findByTaskId(int $task_id)
 * @method Audit|null findOneByTaskId(int $task_id)
 */
class AuditRepository extends ServiceEntityRepository
{
    const ACTION_CREATE = "create";
    const ACTION_UPDATE = "update";
    const ACTION_DELETE = "delete";
    const AVAILABLE_ACTIONS = [
        self::ACTION_CREATE,
        self::ACTION_UPDATE,
        self::ACTION_DELETE,
    ];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Audit::class);
    }

    /**
     * @throws InvalidActionException
     * @throws \Doctrine\ORM\ORMException
     */
    public function createFor(string $action, ToDo $task): Audit
    {
        $action = strtolower($action);
        if (!in_array($action, self::AVAILABLE_ACTIONS)) {
            throw new InvalidActionException($action);
        }

        $past = ucfirst($action) . 'd';

        $dateTime = new \DateTimeImmutable();
        $timeStr = $dateTime->format("H:i");
        $audit = new Audit();
        $audit->setAction($action)
            ->setDescription("{$past} the task \"{$task->getTitle()}\" at {$timeStr}")
            ->setTaskId($task->getId())
            ->setPerformedAt($dateTime);
        $this->getEntityManager()->persist($audit);
        $this->getEntityManager()->flush();
        return $audit;
    }

    /**
     * @return Audit[]
     */
    public function findWithExistentTasks(array $sortBy=[]): array
    {
        $queryBuilder = $this->createQueryBuilder('a')
            ->join(ToDo::class, 't', Join::WITH, 'a.task_id = t.id');

        foreach ($sortBy as $field => $order) {
            $queryBuilder->orderBy("a.{$field}", $order);
        }
        return $queryBuilder
            ->getQuery()
            ->getResult();
    }
}
