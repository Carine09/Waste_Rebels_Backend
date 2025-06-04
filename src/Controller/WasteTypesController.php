<?php

namespace App\Controller;

use App\Entity\WasteType;
use App\Entity\WasteItem;
use App\Repository\WasteTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

final class WasteTypesController extends AbstractController
{
    #[Route('/waste/type', name: 'show_all_waste_types', methods: ['GET'])]
    public function showAllWasteTypes(WasteTypeRepository $repository): JsonResponse
    {
        $wasteTypes = $repository->findAll();
        
       
        $wasteTypeData = [];
        foreach ($wasteTypes as $types) {
            $wasteTypeData[] = [
                'id' => $types->getId(),
                'value' => $types->getValue(),
            ];
        }
        
        return $this->json($wasteTypeData);
    }

    #[Route('/waste/type', name: 'add_waste_type', methods: ['POST'])]
    public function addWasteType(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return $this->json([
                'success' => false,
                'message' => 'Invalid JSON data'
            ], Response::HTTP_BAD_REQUEST);
        }

        if (empty($data['value'])) {
            return $this->json([
                'success' => false,
                'message' => 'Missing required fields: value'
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $wasteType = new WasteType();
            $wasteType->setValue($data['value']);

            $entityManager->persist($wasteType);
            $entityManager->flush();

            return $this->json([
                'success' => true,
                'message' => 'Waste type added successfully',
                'data' => [
                    'id' => $wasteType->getId(),
                    'value' => $wasteType->getValue(),
                ]
            ], Response::HTTP_CREATED);

        } catch (\InvalidArgumentException $e) {
            return $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
            
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error creating waste type: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}