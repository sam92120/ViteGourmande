<?php

namespace App\Controller\Admin;


use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\Admin\MenuCrudController;
use App\Controller\Admin\PlatCrudController;
use App\Controller\Admin\CommandeCrudController;
use App\Controller\Admin\AvisCrudController;
use App\Controller\Admin\ThemeCrudController;
use App\Controller\Admin\UserCrudController;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use App\Repository\CommandeRepository;
use App\Repository\AvisRepository;
use App\Repository\MenuRepository;
use App\Repository\PlatRepository;
use App\Repository\UserRepository;






#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{

//
    public function __construct( private ChartBuilderInterface $chartBuilderInterface,
    private CommandeRepository $commandeRepository,
    private AvisRepository $avisRepository,
    private MenuRepository $menuRepository,
    private PlatRepository $platRepository,
    private UserRepository $userRepository,

    )
    {
        // ...

    }
    public function index(): Response
    {
           
     return $this->render('admin/dashboard.html.twig', [
        'nbCommandes' => $this->commandeRepository->count([]),
        'nbAvisEnAttente' => $this->avisRepository->count(['isApproved' => false]),
        'nbMenus' => $this->menuRepository->count([]),
        'nbPlats' => $this->platRepository->count([]),
        //nombre de plat lies à chaque menu
        'nbUsers' => $this->userRepository->count([]),
        
    ]);
    
    
    
    
    
    
    
    
    //return parent::index();
             //return $this->redirectToRoute('admin_user_index');



        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // return $this->redirectToRoute('admin_user_index');

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
             //return $this->redirectToRoute('...');
        //}

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
       // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        //couleur du thème
        return Dashboard::new()
             
            ->generateRelativeUrls()
            ->setTranslationDomain('admin dashboard')
            ->disableDarkMode()
            ->setTitle('<img src="/image/logovitegourmande.png" alt="Logo" 
            style="height: 30px; margin-right: 10px; border-radius: 50%;
             ">ViteGourmande Admin')
            ->setFaviconPath('favicon.ico ')
            ->setTranslationDomain('admin')
            ->setTextDirection('ltr')
            ->renderSidebarMinimized()
            ->setDefaultColorScheme('light')
            ->setLocales(['fr', 'fr'])
            ->setLocales([
                'fr' => '🇫🇷 Français'
            ]);

        


    }



    public function configureMenuItems(): iterable
    {
   
  
    yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
    yield MenuItem::linkTo(MenuCrudController::class, 'Menus', 'fa-solid fa-utensils');
    yield MenuItem::linkTo(PlatCrudController::class, 'Plats', 'fa-solid fa-bowl-food');
    yield MenuItem::linkTo(CommandeCrudController::class, 'Commandes', 'fa-solid fa-cart-shopping');
    yield MenuItem::linkTo(AvisCrudController::class, 'Avis à valider', 'fa-solid fa-comment');
    yield MenuItem::linkTo(ThemeCrudController::class, 'Themes', 'fa-solid fa-palette');
    yield MenuItem::LinkTo(HoraireCrudController::class, 'Horaires', 'fa-solid fa-clock');
    yield MenuItem::linkTo(UserCrudController::class, 'Users', 'fa-solid fa-users');
    
    yield MenuItem::linkToExitImpersonation('Stop impersonation', 'fa fa-exit');
    yield MenuItem::linkToRoute('Back to the website', 'fa fa-undo', 'app_accueil');
    yield MenuItem::linkToLogout('Logout', 'fa-solid fa-right-from-bracket');
    
    }

    public function configureAssets(): Assets
    {
        return Assets::new()
            ->addCssFile('/admin.css');
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->setAvatarUrl('/image/logovitegourmande.png')
            ->displayUserName(true)
            ->addMenuItems([
                MenuItem::linkToRoute('Mon Profil', 'fa-solid fa-user', 'app_moncompte_profil'),
                MenuItem::linkToRoute('Paramètres', 'fa-solid fa-cog', 'app_moncompte_profil_modifier'),
                MenuItem::linkToLogout('Se Déconnecter', 'fa-solid fa-right-from-bracket'),
            ]);         
    }
 
   

    public function configureCrud(): Crud
    {
        return Crud::new()
            ->setDefaultSort(['id' => 'DESC']);
    }

    

}

