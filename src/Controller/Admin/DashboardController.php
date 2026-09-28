<?php

namespace App\Controller\Admin;

use App\Repository\AvisRepository;
use App\Service\RestaurantActivityService;
use App\Repository\CommandeRepository;
use App\Repository\MenuRepository;
use App\Repository\PlatRepository;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
   public function __construct(
    private CommandeRepository $commandeRepository,
    private AvisRepository $avisRepository,
    private MenuRepository $menuRepository,
    private PlatRepository $platRepository,
    private UserRepository $userRepository,
    private RestaurantActivityService $activityService,
) {
}


   public function index(): Response
{
    // Statistiques MySQL
    $nbCommandes = $this->commandeRepository->count([]);
    $nbAvisEnAttente = $this->avisRepository->count(['isApproved' => false]);
    $nbMenus = $this->menuRepository->count([]);
    $nbPlats = $this->platRepository->count([]);
    $nbUsers = $this->userRepository->count([]);

    // Statistiques MongoDB
    $statistics = $this->activityService->getStatistics();

    $byType = $statistics['byType'];
    $topMenus = $statistics['topMenus'];

    // Nombre total de consultations de menus
    $totalViews = 0;

    // Nombre total d'activités
    $totalActivities = 0;

    foreach ($byType as $stat) {
        $count = (int) $stat['count'];

        $totalActivities += $count;

        if (($stat['_id'] ?? null) === 'menu_view') {
            $totalViews = $count;
        }
    }

    // Récupération des menus concernés depuis MySQL
    $menuIds = [];

    foreach ($topMenus as $stat) {
        if (isset($stat['_id'])) {
            $menuIds[] = (int) $stat['_id'];
        }
    }

    $menus = $this->menuRepository->findBy([
        'id' => $menuIds,
    ]);

    // Associer les titres MySQL aux statistiques MongoDB
    $menuTitles = [];

    foreach ($menus as $menu) {
        $menuTitles[$menu->getId()] = $menu->getTitre();
    }

    $maxViews = 0;

    foreach ($topMenus as $stat) {
        $maxViews = max($maxViews, (int) $stat['count']);
    }

    foreach ($topMenus as &$stat) {
        $menuId = (int) $stat['_id'];

        $stat['menuTitle'] = $menuTitles[$menuId] ?? 'Menu supprimé';

        $stat['percentage'] = $maxViews > 0
            ? (($stat['count'] / $maxViews) * 100)
            : 0;
    }

    unset($stat);

    // Consultations des menus sur les 7 derniers jours
    $dailyViews = $this->activityService->getDailyMenuViews(7);

    return $this->render('admin/dashboard.html.twig', [
        'nbCommandes' => $nbCommandes,
        'nbAvisEnAttente' => $nbAvisEnAttente,
        'nbMenus' => $nbMenus,
        'nbPlats' => $nbPlats,
        'nbUsers' => $nbUsers,

        'totalViews' => $totalViews,
        'totalActivities' => $totalActivities,
        'uniqueMenus' => count($menuIds),
        'topMenus' => $topMenus,
        'dailyViews' => $dailyViews,
    ]);
}    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->generateRelativeUrls()
            ->setTranslationDomain('admin')
            ->disableDarkMode()
            ->setTitle(
                '<img src="/image/logovitegourmande.png"
                alt="Logo"
                style="height: 30px;
                margin-right: 10px;
                border-radius: 50%;">
                ViteGourmande Admin'
            )
            ->setFaviconPath('favicon.ico')
            ->setTextDirection('ltr')
            ->renderSidebarMinimized()
            ->setDefaultColorScheme('light')
            ->setLocales([
                'fr' => '🇫🇷 Français'
            ]);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard(
            'Dashboard',
            'fa fa-home'
        );

        yield MenuItem::linkTo(
            MenuCrudController::class,
            'Menus',
            'fa-solid fa-utensils'
        );

        yield MenuItem::linkTo(
            PlatCrudController::class,
            'Plats',
            'fa-solid fa-bowl-food'
        );

        yield MenuItem::linkTo(
            CommandeCrudController::class,
            'Commandes',
            'fa-solid fa-cart-shopping'
        );

        yield MenuItem::linkTo(
            AvisCrudController::class,
            'Avis à valider',
            'fa-solid fa-comment'
        );

        yield MenuItem::linkTo(
            ThemeCrudController::class,
            'Themes',
            'fa-solid fa-palette'
        );

        yield MenuItem::linkTo(
            HoraireCrudController::class,
            'Horaires',
            'fa-solid fa-clock'
        );

        yield MenuItem::linkTo(
            UserCrudController::class,
            'Users',
            'fa-solid fa-users'
        );

        yield MenuItem::linkToExitImpersonation(
            'Stop impersonation',
            'fa fa-exit'
        );

        yield MenuItem::linkToRoute(
            'Back to the website',
            'fa fa-undo',
            'app_accueil'
        );

        yield MenuItem::linkToLogout(
            'Logout',
            'fa-solid fa-right-from-bracket'
        );
    }

    public function configureAssets(): Assets
    {
        return Assets::new()
            ->addCssFile('/admin.css');
    }

    public function configureUserMenu(
        UserInterface $user
    ): UserMenu {
        return parent::configureUserMenu($user)
            ->setAvatarUrl('/image/logovitegourmande.png')
            ->displayUserName(true)
            ->addMenuItems([
                MenuItem::linkToRoute(
                    'Mon Profil',
                    'fa-solid fa-user',
                    'app_moncompte_profil'
                ),
                MenuItem::linkToRoute(
                    'Paramètres',
                    'fa-solid fa-cog',
                    'app_moncompte_profil_modifier'
                ),
                MenuItem::linkToLogout(
                    'Se Déconnecter',
                    'fa-solid fa-right-from-bracket'
                ),
            ]);
    }

    public function configureCrud(): Crud
    {
        return Crud::new()
            ->setDefaultSort(['id' => 'DESC']);
    }
}

