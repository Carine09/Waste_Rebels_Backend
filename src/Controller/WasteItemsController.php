<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WasteItemsController extends AbstractController
{
    #[Route('/waste/items', name: 'app_waste_items')]
    public function index(): Response
    {
        return $this->render('waste_items/index.html.twig', [
            'controller_name' => 'WasteItemsController',
        ]);
    }
}
