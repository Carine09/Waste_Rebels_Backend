<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Location;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class UserController extends AbstractController
{
    #[Route('/user', name: 'show_all_users', methods: ['GET'])]
    public function showAllUsers(UserRepository $repository): JsonResponse
    {
        $users = $repository->findAll();
        

        $userData = [];
        foreach ($users as $user) {
            $userData[] = [
                'id' => $user->getId(),
                'role' => $user->getRole(),
                'firstname' => $user->getFirstname(),
                'lastname' => $user->getLastname(),
                'email' => $user->getEmail(),
                'is_active' => $user->getIsActive(),
                'location_id' => $user->getLocation()?->getId(),
                'location' => $user->getLocation() ? [
                    'id' => $user->getLocation()->getId(),
                    'city' => $user->getLocation()->getCity()
                ] : null,
                'created_at' => $user->getCreatedAt()?->format('Y-m-d H:i:s'),
                'updated_at' => $user->getUpdatedAt()?->format('Y-m-d H:i:s')
            ];
        }
        
        return $this->json($userData);
    }

    #[Route('/user', name: 'create_user', methods: ['POST'])]
    public function createUser(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return $this->json([
                'success' => false,
                'message' => 'Invalid JSON data'
            ], Response::HTTP_BAD_REQUEST);
        }

        if (empty($data['email']) || empty($data['firstname']) || empty($data['lastname'])) {
            return $this->json([
                'success' => false,
                'message' => 'Missing required fields: email, firstname, lastname'
            ], Response::HTTP_BAD_REQUEST);
        }

  
        $role = $data['role'] ?? 'Volunteer';
        if (!in_array($role, User::getAllowedRoles())) {
            return $this->json([
                'success' => false,
                'message' => 'Invalid role. Allowed roles are: ' . implode(', ', User::getAllowedRoles())
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $user = new User();
            $user->setRole($role);
            $user->setFirstname($data['firstname']);
            $user->setLastname($data['lastname']);
            $user->setEmail($data['email']);
            $user->setPassword($data['password'] ?? '');
            $user->setIsActive($data['is_active'] ?? true);

           
            if (!empty($data['location_id'])) {
                $locationRepository = $entityManager->getRepository(Location::class);
                $location = $locationRepository->find($data['location_id']);
                if (!$location) {
                    return $this->json([
                        'success' => false,
                        'message' => 'Location not found'
                    ], Response::HTTP_BAD_REQUEST);
                }
                $user->setLocation($location);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->json([
                'success' => true,
                'message' => 'User created successfully',
                'data' => [
                    'id' => $user->getId(),
                    'role' => $user->getRole(),
                    'firstname' => $user->getFirstname(),
                    'lastname' => $user->getLastname(),
                    'email' => $user->getEmail(),
                    'is_active' => $user->getIsActive(),
                    'location_id' => $user->getLocation()?->getId(),
                    'location' => $user->getLocation() ? [
                        'id' => $user->getLocation()->getId(),
                        'city' => $user->getLocation()->getCity()
                    ] : null,
                    'created_at' => $user->getCreatedAt()?->format('Y-m-d H:i:s')
                ]
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error creating user: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/user/{id}', name: 'delete_user', methods: ['GET','DELETE'])]
    public function deleteUser(int $id, UserRepository $repository, EntityManagerInterface $em): JsonResponse
    {
        $user = $repository->find($id);
        if (!$user) {
            return $this->json([
                'success' => false,
                'message' => 'User not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $em->remove($user);
        $em->flush();

        return $this->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }

    #[Route('/user', name: 'delete_all_users', methods: ['DELETE'])]
    public function deleteAllUsers(UserRepository $repository, EntityManagerInterface $em): JsonResponse
    {
        $users = $repository->findAll();
        foreach ($users as $user) {
            $em->remove($user);
        }
        $em->flush();

        return $this->json([
            'success' => true,
            'message' => 'All users deleted successfully'
        ]);
    }
}