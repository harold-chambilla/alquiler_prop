<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiLoginController extends AbstractController
{
    #[Route('/api/profile', name: 'app_api_login')]
    public function index(): JsonResponse
    {
        $user = $this->getUser();
        
        if (!$user) {
            return $this->json([
                'message' => 'No se ha podido autenticar el usuario.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'email' => $user->getUserIdentifier(),
            'id' => $user->getId(),
        ]);
    }
}
