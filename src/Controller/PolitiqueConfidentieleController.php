<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PolitiqueConfidentieleController extends AbstractController
{
    #[Route('/politique/confidentiele', name: 'app_politique_confidentiele')]
    public function index(): Response
    {
        return $this->render('politique_confidentiele/index.html.twig', [
            'controller_name' => 'PolitiqueConfidentieleController',
        ]);
    }
}
