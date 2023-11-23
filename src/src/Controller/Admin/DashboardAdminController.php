<?php

namespace App\Controller\Admin;

use App\Entity\Menu;
use App\Entity\User;
use App\Entity\Resto;
use App\Entity\Recipe;
use GuzzleHttp\Client;
use App\Entity\TwigVars;
use App\Entity\Ingrediant;
use Doctrine\ORM\EntityManager;
use Geocoder\Provider\Provider;
use Geocoder\Query\GeocodeQuery;
use Geocoder\Exception\Exception;
use App\Form\AdminRestoSettingsType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\NotSupported;
use Geocoder\Provider\Nominatim\Nominatim;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;


class DashboardAdminController extends AbstractDashboardController
{
    private $client;

    public function __construct(HttpClientInterface $client, private AdminUrlGenerator $adminUrlGenerator)
    {
        $this->client = $client;
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
        yield MenuItem::linkToCrud('Restaurant', 'fa-solid fa-utensils', Resto::class);
        yield MenuItem::section('Ingredients');
        yield MenuItem::linkToCrud('Ingredients', 'fa-solid fa-carrot', Ingrediant::class);
        yield MenuItem::section('Recipe');
        yield MenuItem::linkToCrud('Recipe', 'fa-solid fa-burger', Recipe::class);
        yield MenuItem::section('Menu');
        yield MenuItem::linkToCrud('Menu', 'fa-solid fa-bowl-food', Menu::class);
        yield MenuItem::section('Public rendering');
        yield MenuItem::linkToCrud('Modify twig variables', 'fa-brands fa-symfony', TwigVars::class);
    }
}
