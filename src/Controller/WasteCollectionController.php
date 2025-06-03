<?php

namespace App\Controller;

use App\Entity\WasteCollection;
use App\Repository\WasteCollectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

final class WasteCollectionController extends AbstractController
{
    #[Route('/waste/collection', name: 'app_waste_collection', methods: ['POST'])]
    public function createUser(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
  
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json([
                'success' => false,
                'message' => 'Invalid JSON data'
            ], Response::HTTP_BAD_REQUEST);
        }
        try {
            $waste_collection = new WasteCollection();
            $entityManager->persist($waste_collection);
            $entityManager->flush();
            return $this->json([
                'success' => true,
                'message' => 'Waste collection created successfully',
                'data' => [
                    'id' => $waste_collection->getId(),
                    'user_id' => $waste_collection->getUserId(),
                    'location_id' => $waste_collection->getLocationId(),
                    'created_at' => $waste_collection->getCreatedAt()?->format('Y-m-d H:i:s')
                ]
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error creating waste$waste_collection: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
