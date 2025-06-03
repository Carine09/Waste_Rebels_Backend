<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class LocationsController extends AbstractController
{
    #[Route('/location', name: 'show_all_locations', methods: ['GET'])]
    public function showAllLocations(LocationRepository $repository): JsonResponse
    {
        $locations = $repository->findAll();
        
        // Format the response to include user count
        $locationData = [];
        foreach ($locations as $location) {
            $locationData[] = [
                'id' => $location->getId(),
                'city' => $location->getCity(),
                'user_count' => $location->getUsers()->count()
            ];
        }
        
        return $this->json($locationData);
    }

    #[Route('/location', name: 'create_location', methods: ['POST'])]
    public function createLocation(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return $this->json([
                'success' => false,
                'message' => 'Invalid JSON data'
            ], Response::HTTP_BAD_REQUEST);
        }

        if (empty($data['city'])) {
            return $this->json([
                'success' => false,
                'message' => 'Missing required fields: city'
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $location = new Location();
            $location->setCity($data['city']);
            
            $entityManager->persist($location);
            $entityManager->flush();

            return $this->json([
                'success' => true,
                'message' => 'Location created successfully',
                'data' => [
                    'id' => $location->getId(),
                    'city' => $location->getCity(),
                    'user_count' => 0
                ]
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error creating location: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/location/{id}', name: 'show_location', methods: ['GET'])]
    public function showLocation(int $id, LocationRepository $repository): JsonResponse
    {
        $location = $repository->find($id);
        if (!$location) {
            return $this->json([
                'success' => false,
                'message' => 'Location not found'
            ], Response::HTTP_NOT_FOUND);
        }

        // Get users for this location
        $users = [];
        foreach ($location->getUsers() as $user) {
            $users[] = [
                'id' => $user->getId(),
                'firstname' => $user->getFirstname(),
                'lastname' => $user->getLastname(),
                'email' => $user->getEmail(),
                'role' => $user->getRole(),
                'is_active' => $user->getIsActive()
            ];
        }

        return $this->json([
            'success' => true,
            'data' => [
                'id' => $location->getId(),
                'city' => $location->getCity(),
                'user_count' => count($users),
                'users' => $users
            ]
        ]);
    }

    #[Route('/location/{id}', name: 'delete_location', methods: ['DELETE'])]
    public function deleteLocation(int $id, LocationRepository $repository, EntityManagerInterface $em): JsonResponse
    {
        $location = $repository->find($id);
        if (!$location) {
            return $this->json([
                'success' => false,
                'message' => 'Location not found'
            ], Response::HTTP_NOT_FOUND);
        }

        // Check if location has users - you might want to prevent deletion
        if ($location->getUsers()->count() > 0) {
            return $this->json([
                'success' => false,
                'message' => 'Cannot delete location with existing users. Remove users first.'
            ], Response::HTTP_BAD_REQUEST);
        }

        $em->remove($location);
        $em->flush();

        return $this->json([
            'success' => true,
            'message' => 'Location deleted successfully'
        ]);
    }
}