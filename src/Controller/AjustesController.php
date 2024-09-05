<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AjustesController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): RedirectResponse
    {
        
        return $this->redirectToRoute('app_login');
    }
}
