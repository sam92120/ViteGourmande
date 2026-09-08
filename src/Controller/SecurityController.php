<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private $authenticationUtils;
    public function __construct(AuthenticationUtils $authenticationUtils)
    {
        $this->authenticationUtils = $authenticationUtils; // permet de récupérer les erreurs de connexion et le dernier nom d'utilisateur saisi
    }
    
    #[Route(path: '/login', name: 'app_login')]

    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         
         if ($this->getUser()) {
            return $this->redirectToRoute('app_accueil');
         }

     
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();
    if ($error) {
        $this->addFlash('error', $error);
    } else {
        $this->addFlash('success','');
    }


        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
        
    }


    //chemin de la route pour se déconnecter
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
