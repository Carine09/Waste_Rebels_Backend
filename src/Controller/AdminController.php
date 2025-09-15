<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/api/admin-only', name: 'api_admin_only', methods: ['GET'])]
    public function adminOnly(): JsonResponse
    {
        // Cette méthode bloque automatiquement l'accès si l'utilisateur n'est pas ROLE_ADMIN
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->json([
            'message' => 'Welcome, Admin !',
        ]);
    }
}
