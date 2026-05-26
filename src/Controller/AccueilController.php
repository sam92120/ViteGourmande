<?php

namespace App\Controller;

use App\Repository\AvisRepository;
use App\Repository\HoraireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(
        AvisRepository $avisRepository,
        HoraireRepository $horaireRepository
    ): Response {
        $allAvis = $avisRepository->findBy(
            ['isApproved' => true],
            ['id' => 'DESC']
        );

        $avisUniques = [];
        $usersDejaAffiches = [];

        foreach ($allAvis as $unAvis) {
            if ($unAvis->getUser() === null) {
                continue;
            }

            $userId = $unAvis->getUser()->getId();

            if (!in_array($userId, $usersDejaAffiches)) {
                $avisUniques[] = $unAvis;
                $usersDejaAffiches[] = $userId;
            }

            if (count($avisUniques) === 1000) {
                break;
            }
        }

        $horaires = $horaireRepository->findAll();

        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'avis' => $avisUniques,
            'user' => $this->getUser(),
            'horaires' => $horaires,
        ]);
    }
}