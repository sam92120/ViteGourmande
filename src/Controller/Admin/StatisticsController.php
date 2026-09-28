<?php

namespace App\Controller\Admin;

use App\Repository\MenuRepository;
use App\Service\RestaurantActivityService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/statistiques')]
class StatisticsController extends AbstractController
{
    #[Route('', name: 'admin_statistics', methods: ['GET'])]
    public function index(
        RestaurantActivityService $activityService,
        MenuRepository $menuRepository
    ): Response {
        $statistics = $activityService->getStatistics();

        $byType = $statistics['byType'];
        $topMenus = $statistics['topMenus'];

        // Nombre total de consultations de menus
        $totalViews = 0;

        foreach ($byType as $statistic) {
            if ($statistic['_id'] === 'menu_view') {
                $totalViews = $statistic['count'];
                break;
            }
        }

        // Nombre total d'activités
        $totalActivities = array_sum(
            array_column($byType, 'count')
        );

        // Récupération des IDs des menus consultés
        $menuIds = array_values(
            array_filter(
                array_column($topMenus, '_id'),
                static fn ($id) => $id !== null
            )
        );

        // Récupération des menus depuis MySQL
        $menus = [];

        if (!empty($menuIds)) {
            foreach ($menuRepository->findBy(['id' => $menuIds]) as $menu) {
                $menus[$menu->getId()] = $menu;
            }
        }

        // Nombre de menus différents consultés
        $uniqueMenus = count($menuIds);

        // Nombre maximal de consultations
        $maxCount = 0;

        foreach ($topMenus as $statistic) {
            $maxCount = max($maxCount, $statistic['count']);
        }

        // Préparation des données pour Twig
        foreach ($topMenus as &$statistic) {
            $menuId = $statistic['_id'];

            if (isset($menus[$menuId])) {
                $statistic['menuTitle'] = $menus[$menuId]->getTitre();
            } else {
                $statistic['menuTitle'] = 'Menu supprimé';
            }

            $statistic['percentage'] = $maxCount > 0
                ? round(($statistic['count'] / $maxCount) * 100)
                : 0;
        }

        unset($statistic);

        return $this->render('admin/statistics.html.twig', [
            'byType' => $byType,
            'topMenus' => $topMenus,
            'totalViews' => $totalViews,
            'uniqueMenus' => $uniqueMenus,
            'totalActivities' => $totalActivities,
        ]);
    }
}