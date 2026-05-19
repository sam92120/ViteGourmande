<?php

namespace App\Controller;

use App\Entity\Plat;
use App\Form\PlatType;
use App\Repository\PlatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/plat')]
final class PlatController extends AbstractController
{
    #[Route(name: 'app_plat_index', methods: ['GET'])]
    public function index(Request $request, PlatRepository $platRepository): Response
    {
        $filtres = [
            'type' => $request->query->get('type'), 
        ];
        return $this->render('plat/index.html.twig', [
            'plats' => $platRepository->findByType($filtres['type']),
        ]);
    }

    
    #[Route('/new', name: 'app_plat_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $plat = new Plat();
        $form = $this->createForm(PlatType::class, $plat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($plat);
            $entityManager->flush();

            return $this->redirectToRoute('app_plat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('plat/new.html.twig', [
            'plat' => $plat,
            'form' => $form,
        ]);
    }


    // une galerie de plats
#[Route('/galerie', name: 'app_plat_galerie', methods: ['GET'])]
public function galerie(PlatRepository $platRepository): Response
{
    return $this->render('plat/galerie.html.twig', [
        'platsPrincipaux' => $platRepository->findBy(['type' => 'plat']),
        'desserts' => $platRepository->findBy(['type' => 'dessert']),
        'entrees' => $platRepository->findBy(['type' => 'entree']),
    ]);
}

// Affichage d'un plat spécifique les desserts

#[Route('/desserts', name: 'app_plat_desserts', methods: ['GET'])]
public function desserts(PlatRepository $platRepository): Response
{
    $desserts = $platRepository->findBy(['type' => 'dessert']);

    return $this->render('plat/desserts.html.twig', [
        'desserts' => $desserts,
    ]);
}

    #[Route('/{id}', name: 'app_plat_show', methods: ['GET'])]
    public function show(Plat $plat): Response
    {
        return $this->render('plat/show.html.twig', [
            'plat' => $plat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_plat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Plat $plat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlatType::class, $plat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_plat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('plat/edit.html.twig', [
            'plat' => $plat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_plat_delete', methods: ['POST'])]
    public function delete(Request $request, Plat $plat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$plat->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($plat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_plat_index', [], Response::HTTP_SEE_OTHER);
    }

  

}