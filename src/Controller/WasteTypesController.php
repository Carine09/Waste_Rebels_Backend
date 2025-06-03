<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WasteTypesController extends AbstractController
{
    #[Route('/waste/types', name: 'app_waste_types')]
    public function index(): Response
    {
        return $this->render('waste_types/index.html.twig', [
            'controller_name' => 'WasteTypesController',
        ]);
    }
}
