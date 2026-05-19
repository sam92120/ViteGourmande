<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Override;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;



#[IsGranted('ROLE_EMPLOYEE')]
#[AdminDashboard(routePath: '/employee', routeName: 'employee')]
class EmployeeDashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->render('employee/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('<img src="/image/logovitegourmande.png" alt="Logo" style="height: 30px; margin-right: 10px; border-radius: 50%;"> ViteGourmande Admin')
            ->setFaviconPath('favicon.ico')
            ->setTranslationDomain('admin')
            ->setTextDirection('ltr')
            ->renderSidebarMinimized()
            ->setDefaultColorScheme('light')
            ->setLocales([
                'fr' => '🇫🇷 Français',
            ]);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkTo(MenuCrudController::class, 'Menus', 'fa-solid fa-utensils');
        yield MenuItem::linkTo(PlatCrudController::class, 'Plats', 'fa-solid fa-bowl-food');
        yield MenuItem::linkTo(CommandeCrudController::class, 'Commandes', 'fa-solid fa-cart-shopping');
        yield MenuItem::linkTo(HoraireCrudController::class, 'Horaires', 'fa-solid fa-clock');
        //yield MenuItem::linkTo(AvisCrudController::class, 'Avis', 'fa-solid fa-comment');
        //yield MenuItem::linkTo(ThemeCrudController::class, 'Thèmes', 'fa-solid fa-palette');
        yield MenuItem::section('pour les employés');
        yield MenuItem::linkToRoute('Retour au site', 'fa fa-undo', 'app_accueil');
        yield MenuItem::linkToLogout('Déconnexion', 'fa-solid fa-right-from-bracket');
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->setName((string) $user)
            ->displayUserAvatar(false)
            ->addMenuItems([
                MenuItem::linkToLogout('Déconnexion', 'fa fa-sign-out'),
            ]);
    }

    #[Override]
    public function configureFilters(): Filters
    {
        return parent::configureFilters();
    }

    #[Override]
    public function configureActions(): Actions
    {
        return parent::configureActions();
    }
    
}