<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Menu;
use App\Repository\PlatRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\MenuRepository;


#[Route('/commande')]
final class CommandeController extends AbstractController
{
 #[Route(name: 'app_commande_index', methods: ['GET'])]
public function index(CommandeRepository $commandeRepository): Response
{
    return $this->render('commande/index.html.twig', [
        'commandes' => $commandeRepository->findBy([
            'user' => $this->getUser()
        ]),
    ]);
}


#[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $menuId = $request->query->get('menu');

    $commande = new Commande();

    // ici tu peux utiliser $menuId

    $form = $this->createForm(CommandeType::class, $commande);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($commande);
        $entityManager->flush();

        return $this->redirectToRoute('app_commande_index');
    }

    return $this->render('commande/new.html.twig', [
        'commande' => $commande,
        'form' => $form,
    ]);
}


    

    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }

    

//menu

#[Route('/menu/{id}', name: 'app_commande_menu', methods: ['GET'])]
#[IsGranted('ROLE_USER')]   
public function menu(Menu $menu): Response
{
    $user = $this->getUser();
    $platsPrincipaux = $menu->getPlats()->filter(fn($plat) => $plat->getType() === 'plat');
    $entrees = $menu->getPlats()->filter(fn($plat) => $plat->getType() === 'entree');
    $desserts = $menu->getPlats()->filter(fn($plat) => $plat->getType() === 'dessert');

   return $this->render('commande/menu.html.twig', [
    'menu' => $menu,
    'user' => $this->getUser(),
    'platsPrincipaux' => $platsPrincipaux,
    'entrees' => $entrees,
    'desserts' => $desserts,
]);
}

#[Route('/menu/{id}/valider', name: 'app_commande_menu_valider', methods: ['POST'])]
#[IsGranted('ROLE_USER')]
public function validerMenu(
    Menu $menu,
    Request $request,
    EntityManagerInterface $entityManager,
    PlatRepository $platRepository
): Response {

    $commande = new Commande();

    $platPrincipal = $platRepository->find(
        $request->request->get('plat_principal_id')
    );

    $entree = $platRepository->find(
        $request->request->get('entree_id')
    );

    $dessert = $platRepository->find(
        $request->request->get('dessert_id')
    );

    // Nombre de personnes
    $nbPers = max(
        $menu->getNbPersMin(),
        (int) $request->request->get(
            'nb_pers',
            $menu->getNbPersMin()
        )
    );

    // Calcul prix menu
    $prixMenu = $menu->getPrixParPers() * $nbPers;

    // Réduction 10%
    if ($nbPers >= $menu->getNbPersMin() + 5) {
        $prixMenu *= 0.90;
    }

    // Livraison
    $distanceKm = (float) $request->request->get(
        'distance_km',
        0
    );

    $prixLivraison = 0;

    if (
        strtolower($request->request->get('ville')) !== 'bordeaux'
    ) {
        $prixLivraison = 5 + (0.59 * $distanceKm);
    }

    // Remplissage commande
    $commande->setMenu($menu);
    $commande->setUser($this->getUser());
    $commande->setPlatPrincipal($platPrincipal);
    $commande->setEntree($entree);
    $commande->setDessert($dessert);

    $commande->setStatus('en_attente');

    $commande->setDateCommande(new \DateTime());

   $commande->setDatePretation(
    new \DateTime($request->request->get('date_prestation'))
);

$heureLivraison = \DateTime::createFromFormat(
    'H:i',
    $request->request->get('heure_livraison')
);

$commande->setHeureLivraison($heureLivraison);

    $commande->setNumeroCommande(
        random_int(100000, 999999)
    );

    $commande->setNbPers($nbPers);

    // IMPORTANT
    $commande->setPrixMenu($prixMenu);

  $commande->setPrixLivraison($prixLivraison);

if ($menu->getQuantiteRestante() < $nbPers) {
    $this->addFlash('danger', 'Quantité insuffisante.');

    return $this->redirectToRoute('app_menu_show', [
        'id' => $menu->getId()
    ]);
}

$menu->setQuantiteRestante(
    $menu->getQuantiteRestante() - $nbPers
);

$entityManager->persist($commande);
$entityManager->flush();

    return $this->redirectToRoute(
        'app_commande_show',
        [
            'id' => $commande->getId()
        ]
    );
}


#[Route('/{id}/valider', name: 'app_commande_valider', methods: ['POST'])]
#[IsGranted('ROLE_EMPLOYE')]
public function validerCommande(
    Commande $commande,
    EntityManagerInterface $entityManager
): Response {
    $commande->setStatus('validee');

    $entityManager->flush();

    $this->addFlash('success', 'Commande validée.');

    return $this->redirectToRoute('app_commande_show', [
        'id' => $commande->getId()
    ]);
}
}
