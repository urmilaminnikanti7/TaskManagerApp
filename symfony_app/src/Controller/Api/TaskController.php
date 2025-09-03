<?php

namespace App\Controller\Api;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/tasks', name: 'api_tasks_')]
class TaskController extends AbstractController
{
    // -----------------------------
    // List all tasks for a user
    // GET /api/tasks/{userId}
    // -----------------------------
    #[Route('/user/{userId}', name: 'list', methods: ['GET'])]
    public function list(int $userId, EntityManagerInterface $em): JsonResponse
    {
        $user = $em->getRepository(User::class)->find($userId);
        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        $tasks = $em->getRepository(Task::class)->findBy(['user' => $user]);

        $data = array_map(fn(Task $task) => [
            'id' => $task->getId(),
            'title' => $task->getTitle(),
            'description' => $task->getDescription(),
            'status' => $task->getStatus(),
            'createdAt' => $task->getCreatedAt()->format('Y-m-d H:i:s'),
            'updatedAt' => $task->getUpdatedAt()->format('Y-m-d H:i:s'),
        ], $tasks);

        return $this->json($data);
    }

    // -----------------------------
    // Get user
    // GET /api/users
    // -----------------------------
    #[Route('/{taskId}', name:'gettask', methods: ['GET'])]
    public function getTaskById(int $taskId, EntityManagerInterface $em): JsonResponse
    {
        $task = $em->getRepository(Task::class)->find($taskId);
        if (!$task) {
            return new JsonResponse(['error' => 'Task not found'], 404);
        }

        return $this->json([
            'id' => $task->getId(),
            'title' => $task->getTitle(),
            'status' => $task->getStatus(),
        ], 201);
    }

    // -----------------------------
    // List all tasks
    // GET /api/tasks
    // -----------------------------
    #[Route('', name: 'list_all', methods: ['GET'])]
    public function listAll(EntityManagerInterface $em): JsonResponse
    {
        $tasks = $em->getRepository(Task::class)->findAll();

        $data = array_map(fn(Task $task) => [
            'id' => $task->getId(),
            'title' => $task->getTitle(),
            'description' => $task->getDescription(),
            'status' => $task->getStatus(),
            'user' => $task->getUser() ? $task->getUser()->getId() : null,
            'createdAt' => $task->getCreatedAt()->format('Y-m-d H:i:s'),
            'updatedAt' => $task->getUpdatedAt()->format('Y-m-d H:i:s'),
        ], $tasks);

        return $this->json($data);
    }

    // -----------------------------
    // Create a new task for a user
    // POST /api/tasks
    // -----------------------------
    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request,
                           EntityManagerInterface $em,
                           ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['userId'])) {
            return $this->json(['error' => 'User ID required'], 400);
        }

        $user = $em->getRepository(User::class)->find($data['userId']);
        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        $task = new Task();
        $task->setTitle($data['title']);
        $task->setDescription($data['description'] ?? null);
        $task->setStatus($data['status']);
        $task->setUser($user);

        // Validate
        $errors = $validator->validate($task);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }

        $em->persist($task);
        $em->flush();

        return $this->json([
            'id' => $task->getId(),
            'title' => $task->getTitle(),
            'status' => $task->getStatus(),
        ], 201);
    }

    // -----------------------------
    // Update task status
    // PATCH /api/tasks/{taskId}
    // -----------------------------
    #[Route('/{taskId}', name: 'update', methods: ['PATCH'])]
    public function updateStatus(int $taskId, Request $request, EntityManagerInterface $em,ValidatorInterface $validator): JsonResponse
    {
        $task = $em->getRepository(Task::class)->find($taskId);
        if (!$task) {
            return $this->json(['error' => 'Task not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (!isset($data['status'])) {
            return $this->json(['error' => 'Status is required'], 400);
        }
        $task->setStatus($data['status']);
        // Validate
        $errors = $validator->validate($task);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }
        $em->flush();

        return $this->json(['message' => 'Task status updated']);
    }

    // -----------------------------
    // Delete a task
    // DELETE /api/tasks/{taskId}
    // -----------------------------
    #[Route('/{taskId}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $taskId, EntityManagerInterface $em): JsonResponse
    {
        $task = $em->getRepository(Task::class)->find($taskId);
        if (!$task) {
            return $this->json(['error' => 'Task not found'], 404);
        }

        $em->remove($task);
        $em->flush();

        return $this->json(['message' => 'Task deleted']);
    }
}
