<?php

namespace App\Controller\Admin;

use App\Entity\Ingrediant;
use App\Entity\Menu;
use App\Entity\Recipe;
use App\Entity\Resto;
use App\Entity\TwigVars;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardAdminController extends AbstractDashboardController
{
    public function __construct(private AdminUrlGenerator $adminUrlGenerator)
    {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/home.html.twig', [

        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Restoflash');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Home', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);

        yield MenuItem::section('Users');
        yield MenuItem::linkToCrud('Users', 'fa fa-user', User::class);
        yield MenuItem::section('Restaurant');
        yield MenuItem::linkToRoute('Restaurant', 'fa-solid fa-utensils', 'admin_resto');
        yield MenuItem::section('Ingredients');
        yield MenuItem::linkToCrud('Ingredients', 'fa-solid fa-carrot', Ingrediant::class);
        yield MenuItem::section('Recipe');
        yield MenuItem::linkToCrud('Recipe', 'fa-solid fa-burger', Recipe::class);
        yield MenuItem::section('Menu');
        yield MenuItem::linkToCrud('Menu', 'fa-solid fa-bowl-food', Menu::class);
        yield MenuItem::section('Public rendering');
        yield MenuItem::linkToCrud('Modify twig variables', 'fa-brands fa-symfony', TwigVars::class);
    }

    #[Route('/admin/resto', name: 'admin_resto')]
    public function resto(): Response
    {
        return $this->render('admin/resto.html.twig', [

        ]);
    }
}
