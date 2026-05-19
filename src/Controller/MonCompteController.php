<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ProfileFormType; 
use App\Form\ChangePasswordFormType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;



final class MonCompteController extends AbstractController
{
    #[Route('/moncompte', name: 'app_moncompte')]
    
    public function index(): Response
    {
            $user = $this->getUser();
            if (!$user) {
                return $this->redirectToRoute('app_login');
            }
        return $this->render('moncompte/index.html.twig', [
            'controller_name' => 'MonCompteController',
             "user" => $user,
        ]);
    }



    #[Route('/moncompte/profil', name: 'app_moncompte_profil')]
    public function profil(): Response
    {
        $user = $this->getUser();
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('moncompte/profil.html.twig', [
            'controller_name' => 'MonCompteController',
             "user" => $user,
        ]);
    }
    

    #[Route('/moncompte/profil/modifier', name: 'app_moncompte_profil_modifier')]
public function modifierProfil(
    Request $request,
    EntityManagerInterface $entityManager
): Response {
    $user = $this->getUser();

    if (!$user) {
        return $this->redirectToRoute('app_login');
    }

    $form = $this->createForm(ProfileFormType::class, $user, [
    'csrf_token_id' => 'profile_form',
]);
    $form->handleRequest($request);
    
    
  if ($form->isSubmitted() && $form->isValid()) {
    $entityManager->persist($user);
    $entityManager->flush();
    

    $this->addFlash('success', 'Votre profil a bien été modifié.');

    return $this->redirectToRoute('app_moncompte_profil');
}

    return $this->render('moncompte/modifier_profil.html.twig', [
        'profileForm' => $form,
        'user' => $user,
    ]);
}


#[Route('/moncompte/profil/modifier/motdepasse', name: 'app_moncompte_profil_modifier_motdepasse')]
public function modifierMotDePasse(
    Request $request,
    EntityManagerInterface $entityManager,
    UserPasswordHasherInterface $passwordHasher
): Response {
    /** @var User $user */
    $user = $this->getUser();

    if (!$user) {
        return $this->redirectToRoute('app_login');
    }

    $form = $this->createForm(ChangePasswordFormType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $plainPassword = $form->get('plainPassword')->getData();

        $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);

        $entityManager->flush();

        return $this->redirectToRoute('app_moncompte_profil');
    }

    return $this->render('moncompte/modifier_motdepasse.html.twig', [
        'form' => $form->createView(),
    ]);
}
}
