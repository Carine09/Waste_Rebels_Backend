<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class VolunteerController extends AbstractController
{
    #[Route('/api/volunteer-only', name: 'api_volunteer_only', methods: ['GET'])]
    public function volunteerOnly(): JsonResponse
    {
        // Bloque l’accès si l’utilisateur n’a pas ROLE_VOLUNTEER
        $this->denyAccessUnlessGranted('ROLE_VOLUNTEER');

        return $this->json([
            'message' => 'Welcome, Volunteer !',
        ]);
    }
}
