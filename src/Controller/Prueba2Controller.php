<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class Prueba2Controller extends AbstractController
{
    #[Route('/prueba2', name: 'app_prueba2')]
    public function index(Security $security, UserRepository $userRepository): Response
    {
        $user = $security->getUser();
        $usuario = $userRepository->find(
            'id'
        );
        return $this->render('prueba2/index.html.twig', [
            'controller_name' => 'Prueba2Controller',
            'user' => $user->getUserIdentifier(),
        ]);
    }
}
