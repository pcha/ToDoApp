<?php

namespace App\Tests\EventListener;

use App\Entity\Audit;
use App\Entity\ToDo;
use App\EventListener\CreateAuditRecord;
use App\Repository\AuditRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use function Webmozart\Assert\Tests\StaticAnalysis\null;
use function Webmozart\Assert\Tests\StaticAnalysis\true;

/**
 * Class CreateAuditRecordTest
 * @package App\Tests\EventListener
 * @group time-sensitive
 */
//class CreateAuditRecordTest extends KernelTestCase
//{
//    private function mockToDo(): MockObject|ToDo
//    {
//        $entity = $this->createMock(ToDo::class);
//        $entity->method("getId")->willReturn(1);
//        $entity->method("getTitle")->willReturn("Test Task");
//        return $entity;
//    }
//
//    /**
//     * @return ObjectManager|MockObject
//     */
//    private function mockObjectManager($auditRepository): MockObject|ObjectManager
//    {
//        $entityManager = $this->createMock(ObjectManager::class);
//        $entityManager->method("getRepository")
////            ->with(Audit::class)
//            ->willReturn($auditRepository);
//        $entityManager->method("persist");
//        $entityManager->method("flush")->willReturn(null);
//        return $entityManager;
//    }
//
//    /**
//     * @return LifecycleEventArgs|MockObject
//     */
//    private function mockLifecycleEventArgs($entityManager, $entity): LifecycleEventArgs|MockObject
//    {
//        $args = $this->createMock(LifecycleEventArgs::class);
//        $args->method('getObject')->willReturn($entity);
//        $args->method('getObjectManager')->willReturn($entityManager);
//        return $args;
//    }
//
//    /**
//     * @param string $event
//     * @param string $action
//     * @dataProvider provideForTestSubscription
//     */
////    public function testSubscription(string $event, $entity, string $action, $doNothing=false): void
////    {
////        $auditRepository = $this->createMock(AuditRepository::class);
////        $entityManager = $this->mockObjectManager($auditRepository);
////        $subscriber = new CreateAuditRecord();
////        $args = $this->mockLifecycleEventArgs($entityManager, $entity);
////
////        $times = $this->once();
////        if ($doNothing) {
////            $times = $this->never();
////        }
////
////        $auditRepository->expects($times)
////            ->method("createFor")
////            ->with($action, $entity)
////            ->willReturn($this->createMock(Audit::class));
////        $entityManager->expects($times)
////            ->method("flush");
////
//////        call_user_func([$subscriber, $event], $args);
//////        $subscriber->postPersist($args);
////
////        $this->assertContains($event, $subscriber->getSubscribedEvents());
////    }
//
////    public function provideForTestSubscription(): array
////    {
////        return [
////            [Events::postPersist, $this->mockToDo(), AuditRepository::ACTION_CREATE],
//////            [Events::postUpdate, $this->mockToDo(), AuditRepository::ACTION_UPDATE],
//////            [Events::postRemove, $this->mockToDo(), AuditRepository::ACTION_DELETE],
//////            [Events::postPersist, new Audit(), AuditRepository::ACTION_CREATE, true],
//////            [Events::postUpdate, new Audit(), AuditRepository::ACTION_UPDATE, true],
//////            [Events::postRemove, new Audit(), AuditRepository::ACTION_DELETE, true],
////        ];
////    }
//}
