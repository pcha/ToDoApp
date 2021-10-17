<?php
namespace App\EventListener;

use App\Entity\Audit;
use App\Entity\ToDo;
use App\Repository\AuditRepository;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

$count = 0;

class CreateAuditRecord implements \Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface
{
    const ACTIONS = [
        "onPersist" => ["create", "Created"],
        "onUpdate" => ["update", "Updated"],
        "onRemove" => ["delete",  "Deleted"],
    ];

    /**
     * @inheritDoc
     */
    public function getSubscribedEvents()
    {
        return [
//            Events::onFlush,
//            Events::postPersist,
//            Events::postUpdate,
//            Events::postRemove,
        ];
    }

//    public function onFlush(OnFlushEventArgs $args): void
//    {
//        $entityManager = $args->getEntityManager();
//        $unitOfWork = $entityManager->getUnitOfWork();
//        $insertions = $unitOfWork->getScheduledEntityInsertions();
//        foreach ($insertions as $entity) {
//            if ($entity instanceof ToDo) {
//                $unitOfWork->computeChangeSet($entityManager->getClassMetadata(get_class($entity)), $entity);
//            }
//            dump($unitOfWork->);
//        }
//        die();
//    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->createAudit($args, AuditRepository::ACTION_CREATE);
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->createAudit($args, AuditRepository::ACTION_UPDATE);
    }

    public function postRemove(LifecycleEventArgs $args): void{
        $this->createAudit($args, AuditRepository::ACTION_DELETE);
    }

    /**
     * @param LifecycleEventArgs $args
     * @param string $action
     * @throws \Doctrine\ORM\ORMException
     */
//    private function createAudit(LifecycleEventArgs $args, string $action): void
//    {
//        global $count;
//        $count++;
//        if ($count >=10) return;
////        throw new \Exception("fadfa");
//        dump("createAudit");
////        dump($args);
//        dump($action);
//        $entity = $args->getObject();
//        dump($entity::class);
//        if (!$entity instanceof ToDo) {
//            dump("return");
//            return;
//        }
//        /**
//         * @var AuditRepository $auditRepository
//         */
//        $auditRepository = $args->getObjectManager()
//            ->getRepository(Audit::class);
//
//        $auditRepository->createFor($action, $entity);
////        return;
//        $objectManager = $args->getObjectManager();
////        dump($objectManager::class);
////        $objectManager->flush();
//    }

}