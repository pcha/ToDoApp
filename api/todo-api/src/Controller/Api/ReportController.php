<?php

namespace App\Controller\Api;

use App\Repository\AuditRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    public function __construct(
        protected AuditRepository $repository
    )
    {}

    #[Route('/report', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $orderBy = [];
        if ($order = $request->query->get('sortByTask')) {
            $orderBy['task_id'] = $order > 0 ? 'asc' : 'desc';
        }
        if ($action = $request->query->get('action')) {
            $audits = $this->repository->findBy(['action' => $action], $orderBy);
        } else {
            $audits = $this->repository->findWithExistentTasks($orderBy);
        }
        return $this->json($audits);
    }

    #[Route('/report/{id}', methods: ['GET'])]
    public function listByTask(int $id): Response
    {
        return $this->json($this->repository->findBy(['task_id' => $id]));
    }
}
