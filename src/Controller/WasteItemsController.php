<?php

namespace App\Controller;

use App\Entity\WasteItem;
use App\Entity\WasteType;
use App\Entity\WasteCollection;
use App\Repository\WasteItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

final class WasteItemsController extends AbstractController
{
    #[Route('/waste/item', name: 'add_waste_item', methods: ['POST'])]
    public function addWasteItem(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return $this->json([
                'success' => false,
                'message' => 'Invalid JSON data'
            ], Response::HTTP_BAD_REQUEST);
        }

        if (empty($data['waste_type_id']) || empty($data['waste_collection_id']) || !isset($data['amount'])) {
            return $this->json([
                'success' => false,
                'message' => 'Missing required fields: waste_type_id, waste_collection_id, amount'
            ], Response::HTTP_BAD_REQUEST);
        }


        if (!is_numeric($data['amount']) || $data['amount'] <= 0) {
            return $this->json([
                'success' => false,
                'message' => 'Amount must be a positive number'
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $wasteTypeRepository = $entityManager->getRepository(WasteType::class);
            $wasteType = $wasteTypeRepository->find($data['waste_type_id']);
            if (!$wasteType) {
                return $this->json([
                    'success' => false,
                    'message' => 'Waste type not found'
                ], Response::HTTP_BAD_REQUEST);
            }

            $wasteCollectionRepository = $entityManager->getRepository(WasteCollection::class);
            $wasteCollection = $wasteCollectionRepository->find($data['waste_collection_id']);
            if (!$wasteCollection) {
                return $this->json([
                    'success' => false,
                    'message' => 'Collection not found'
                ], Response::HTTP_BAD_REQUEST);
            }

            $wasteItem = new WasteItem();
            $wasteItem->setWasteType($wasteType);
            $wasteItem->setWasteCollection($wasteCollection);
            $wasteItem->setAmount((float)$data['amount']); 

            $entityManager->persist($wasteItem); 
            $entityManager->flush();

            return $this->json([
                'success' => true,
                'message' => 'Waste item added successfully',
                'data' => [
                    'id' => $wasteItem->getId(),
                    'waste_type_id' => $wasteItem->getWasteType()->getId(), // CORRECTION : Noms de clés plus clairs
                    'waste_collection_id' => $wasteItem->getWasteCollection()->getId(),
                    'amount' => $wasteItem->getAmount(),
                ]
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error creating waste item: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/waste/item/{id}', name: 'delete_waste_item', methods: ['DELETE'])]
    public function deleteWasteItem(int $id, WasteItemRepository $repository, EntityManagerInterface $em): JsonResponse
    {
        $wasteItem = $repository->find($id);
        if (!$wasteItem) {
            return $this->json([
                'success' => false,
                'message' => 'Waste item not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $em->remove($wasteItem);
        $em->flush();

        return $this->json([
            'success' => true,
            'message' => 'Waste item deleted successfully'
        ]);
    }
}