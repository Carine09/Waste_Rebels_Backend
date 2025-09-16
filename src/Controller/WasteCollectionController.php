<?php

namespace App\Controller;

use App\Entity\WasteCollection;
use App\Entity\User;
use App\Entity\Location;
use App\Repository\WasteCollectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

final class WasteCollectionController extends AbstractController
{
    #[Route('/waste/collection', name: 'show_all_waste_collections', methods: ['GET'])]
    public function showAllWasteCollections(WasteCollectionRepository $repository): JsonResponse
    {

    $wasteCollections = $repository->findAllWithItems();

    $data = [];
    foreach ($wasteCollections as $collection) {
        $items = [];
        foreach ($collection->getWasteItems() as $item) {
            $items[] = [
                'id' => $item->getId(),
                'amount' => $item->getAmount(),
                'waste_type' => [
                    'id' => $item->getWasteType()->getId(),
                    'value' => $item->getWasteType()->getValue(),
                ],
            ];
        }

        $data[] = [
            'id' => $collection->getId(),
            'user' => [
                'id' => $collection->getUser()->getId(),
                'firstname' => $collection->getUser()->getFirstname(),
                'lastname' => $collection->getUser()->getLastname(),
                'email' => $collection->getUser()->getEmail(),
            ],
            'location' => [
                'id' => $collection->getLocation()->getId(),
                'city' => $collection->getLocation()->getCity(),
            ],
            'created_at' => $collection->getCreatedAt()->format('Y-m-d H:i:s'),
            'waste_items_count' => count($items),
            'waste_items' => $items, // ✅ détails des items ajoutés
        ];
    }

    return $this->json([
        'success' => true,
        'data' => $data,
    ]);
    }

    #[Route('/waste/collection', name: 'create_waste_collection', methods: ['POST'])]
    public function createWasteCollection(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
    $data = json_decode($request->getContent(), true);

    if (!$data) {
        return $this->json([
            'success' => false,
            'message' => 'Invalid JSON data',
        ], Response::HTTP_BAD_REQUEST);
    }

    if (empty($data['user_id']) || empty($data['location_id'])) {
        return $this->json([
            'success' => false,
            'message' => 'Missing required fields: user_id, location_id',
        ], Response::HTTP_BAD_REQUEST);
    }

    try {
        $userRepository = $entityManager->getRepository(User::class);
        $user = $userRepository->find($data['user_id']);
        if (!$user) {
            return $this->json(['success' => false, 'message' => 'User not found'], Response::HTTP_BAD_REQUEST);
        }

        $locationRepository = $entityManager->getRepository(Location::class);
        $location = $locationRepository->find($data['location_id']);
        if (!$location) {
            return $this->json(['success' => false, 'message' => 'Location not found'], Response::HTTP_BAD_REQUEST);
        }

        
        $wasteCollection = new WasteCollection();
        $wasteCollection->setUser($user);
        $wasteCollection->setLocation($location);

        $entityManager->persist($wasteCollection);

        
        if (!empty($data['waste_items']) && is_array($data['waste_items'])) {
            foreach ($data['waste_items'] as $itemData) {
                if (!isset($itemData['waste_type'], $itemData['amount'])) {
                    continue;
                }

                $wasteTypeRepo = $entityManager->getRepository(\App\Entity\WasteType::class);
                $wasteType = $wasteTypeRepo->findOneBy(['value' => $itemData['waste_type']]);

                if (!$wasteType) {
                    continue;
                }

                $wasteItem = new \App\Entity\WasteItem();
                $wasteItem->setWasteCollection($wasteCollection);
                $wasteItem->setWasteType($wasteType);
                $wasteItem->setAmount((float) $itemData['amount']);

                $entityManager->persist($wasteItem);
            }
        }

        $entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => 'Waste collection created successfully',
            'data' => [
                'id' => $wasteCollection->getId(),
                'user' => [
                    'id' => $wasteCollection->getUser()->getId(),
                    'firstname' => $wasteCollection->getUser()->getFirstname(),
                ],
                'location' => [
                    'id' => $wasteCollection->getLocation()->getId(),
                    'city' => $wasteCollection->getLocation()->getCity(),
                ],
                'created_at' => $wasteCollection->getCreatedAt()->format('Y-m-d H:i:s'),
                'waste_items_count' => count($wasteCollection->getWasteItems()),
            ]
        ], Response::HTTP_CREATED);

    } catch (\Exception $e) {
        return $this->json([
            'success' => false,
            'message' => 'Error creating waste collection: ' . $e->getMessage(),
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}

    #[Route('/waste/collection/{id}', name: 'show_waste_collection', methods: ['GET'])]
    public function showWasteCollection(int $id, WasteCollectionRepository $repository): JsonResponse
    {
    
    $wasteCollection = $repository->find($id);

    if (!$wasteCollection) {
        return $this->json([
            'success' => false,
            'message' => 'Waste collection not found',
        ], Response::HTTP_NOT_FOUND);
    }

 
    $items = [];
    foreach ($wasteCollection->getWasteItems() as $item) {
        $items[] = [
            'id' => $item->getId(),
            'amount' => $item->getAmount(),
            'waste_type' => [
                'id' => $item->getWasteType()->getId(),
                'value' => $item->getWasteType()->getValue(),
            ],
        ];
    }


    $data = [
        'id' => $wasteCollection->getId(),
        'user' => [
            'id' => $wasteCollection->getUser()->getId(),
            'firstname' => $wasteCollection->getUser()->getFirstname(),
            'lastname' => $wasteCollection->getUser()->getLastname(),
            'email' => $wasteCollection->getUser()->getEmail(),
        ],
        'location' => [
            'id' => $wasteCollection->getLocation()->getId(),
            'city' => $wasteCollection->getLocation()->getCity(),
        ],
        'created_at' => $wasteCollection->getCreatedAt()->format('Y-m-d H:i:s'),
        'waste_items_count' => count($items),
        'waste_items' => $items,
    ];

    return $this->json([
        'success' => true,
        'data' => $data,
    ]);
    }


    #[Route('/waste/collection/{id}', name: 'delete_waste_collection', methods: ['DELETE'])]
    public function deleteWasteCollection(int $id, WasteCollectionRepository $repository, EntityManagerInterface $em): JsonResponse
    {
        $wasteCollection = $repository->find($id);
        if (!$wasteCollection) {
            return $this->json([
                'success' => false,
                'message' => 'Waste collection not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $em->remove($wasteCollection);
        $em->flush();

        return $this->json([
            'success' => true,
            'message' => 'Waste collection deleted successfully'
        ]);
    }

    #[Route('/waste/collection', name: 'delete_all_collections', methods: ['DELETE'])]
    public function deleteAllCollections(WasteCollectionRepository $repository, EntityManagerInterface $em): JsonResponse
    {
        $wasteCollections = $repository->findAll();
        foreach ($wasteCollections as $collections) {
            $em->remove($collections);
        }
        $em->flush();

        return $this->json([
            'success' => true,
            'message' => 'All collections deleted successfully'
        ]);
    }
}