<?php

namespace App\Controller\Api;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/users', name: 'api_users_')]
class UserController extends AbstractController
{
    // -----------------------------
    // List all users
    // GET /api/users
    // -----------------------------
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(EntityManagerInterface $em): JsonResponse
    {
        $users = $em->getRepository(User::class)->findAll();

        $data = array_map(fn(User $user) => [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
        ], $users);

        return $this->json($data);
    }

    // -----------------------------
    // Get user
    // GET /api/users
    // -----------------------------
    #[Route('/{userId}', name:'getuser', methods: ['GET'])]
    public function getUserById(int $userId, EntityManagerInterface $em): JsonResponse
    {
        $user = $em->getRepository(User::class)->find($userId);
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], 404);
        }

        return new JsonResponse([
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
        ]);
    }

    // -----------------------------
    // Create a new user
    // POST /api/users
    // -----------------------------
    #[Route('', name: 'create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        ValidatorInterface $validator,
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name'], $data['email'])) {
            return $this->json(['error' => 'Name & Emails are required'], 400);
        }

        $user = new User();
        $user->setName($data['name']);
        $user->setEmail($data['email']);

        // Validate
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }

        $em->persist($user);
        $em->flush();

        return $this->json([
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
        ], 201);
    }

    // -----------------------------
    // update user
    // PATCH /api/users/{userId}
    // -----------------------------
    #[Route('/{userId}', name: 'update', methods: ['PATCH'])]
    public function updateUser(
        int $userId,
        Request $request,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ): JsonResponse {

        $user = $em->getRepository(User::class)->find($userId);
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $user->setName($data['name']);
        }
        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }

        // validate before saving
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], 400);
        }

        $em->flush();

        return new JsonResponse([
            'message' => 'User updated successfully',
            'user' => [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
            ]
        ]);
    }

    // -----------------------------
    // Delete user
    // DELETE /api/users/{userId}
    // -----------------------------
    #[Route('/{userId}', name: 'delete', methods: ['DELETE'])]
    public function deleteUser(
        int $userId,
        EntityManagerInterface $em
    ): JsonResponse {

        $user = $em->getRepository(User::class)->find($userId);
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], 404);
        }

        $em->remove($user);
        $em->flush();

        return new JsonResponse(['message' => 'User deleted successfully']);
    }
}
