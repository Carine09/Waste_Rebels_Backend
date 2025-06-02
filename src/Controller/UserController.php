<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class UserController extends AbstractController
{
    #[Route('/user', name: 'create_user', methods: ['POST'])]
    public function createUser(EntityManagerInterface $entityManager): JsonResponse
    {
        $user = new User();
            $user->setRole($data['role']);
            $user->setFirstname($data['firstname'] ?? '');
            $user->setLastname($data['lastname'] ?? '');
            $user->setEmail($data['email'] ?? '');
            $user->setPassword($data['password'] ?? '');
            $user->setIsActive($data['is_active'] ?? true);

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
                    'created_at' => $user->getCreatedAt()?->format('Y-m-d H:i:s')
                ]
            ], Response::HTTP_CREATED);
    }
}
