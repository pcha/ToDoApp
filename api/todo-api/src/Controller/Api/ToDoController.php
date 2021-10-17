<?php

namespace App\Controller\Api;

use App\Entity\ToDo;
use App\Exceptions\AttributeNotFoundException;
use App\Repository\ToDoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ToDoController extends AbstractController
{
    public function __construct(
        protected ToDoRepository $repository
    )
    {}

    #[Route('/todo', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $filters = [];
        $completedFilter = $request->query->get('completed');
        if (null !== $completedFilter) {
            $filters['completed'] = $completedFilter;
        }
        $tasks = $this->repository->findBy($filters);
        return $this->json($tasks);
    }

    #[Route('/todo/{id}', methods: ['GET'])]
    public function show(ToDo $task): Response
    {
        return $this->json($task);
    }

    #[Route('/todo/{id}', methods: ['DELETE'])]
    public function remove(ToDo $task): Response
    {
        $this->repository->remove($task);
        return $this->json(['deleted' => true]);
    }

    /**
     * @throws \Exception
     */
    #[Route('/todo', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $body = json_decode($request->getContent(), true);
        $title = $body['title'] ?? throw new AttributeNotFoundException('title');
        $completed = $body['completed'] ?? false;
        $task = $this->repository->create($title, $completed);;
        return $this->json($task, Response::HTTP_CREATED);
    }

    #[Route('/todo/{id}', methods: ['PUT'])]
    public function update(Request $request, ToDo $task): Response
    {
        $body = json_decode($request->getContent(), true);
        $title = $body['title'] ?? throw new AttributeNotFoundException('title');
        $completed = $body['completed'] ?? false;
        $this->repository->update($task, $title, $completed);
        return $this->json($task);
    }
}
