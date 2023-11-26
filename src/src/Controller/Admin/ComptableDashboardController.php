<?php

namespace App\Controller\Admin;

use DateTime;
use App\Entity\Stock;
use App\Entity\Facture;
use App\Entity\Transaction;
use Symfony\UX\Chartjs\Model\Chart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class ComptableDashboardController extends AbstractDashboardController
{
    public function __construct(
        private ChartBuilderInterface $chartBuilder,
        private EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/comptable', name: 'comptable')]
    public function index(): Response
    {
        $transactions = $this->entityManager->getRepository(Transaction::class)->findAll();
        $factures = $this->entityManager->getRepository(Facture::class)->findAll();
        $stocks = $this->entityManager->getRepository(Stock::class)->findAll();

        // Obtenir la date actuelle
        $dateActuelle = new DateTime();

        foreach ($factures as $facture) {
            // Calculer la différence en jours entre la date actuelle et la date d'échéance
            $diff = $dateActuelle->diff($facture->getEchanceDate())->days;
            if ($diff < 7) {
                $this->addFlash(
                   'warning',
                   "!!! YOU MUST TO PAY THE FACTURE $facture->getId()"
                );
            }
        }

        foreach ($stocks as $stock) {
            // Calculer la différence en jours entre la date actuelle et la date d'échéance
            if ($stock->getAmount() < 10) {
                $this->addFlash(
                   'warning',
                   "!!! YOU MUST TO BUY {$stock->getName()}"
                );
            }
        }
        // Initialiser les tableaux pour les données du graphique
        $earnedData = [];
        $payedData = [];

        // Parcourir les transactions et organiser les données dans les tableaux appropriés
        foreach ($transactions as $transaction) {
            if ($transaction->getType() === "earned") {
                $earnedData[] = $transaction->getAmount();
            } elseif ($transaction->getType() === "payed") {
                $payedData[] = $transaction->getAmount();
            }
        }

        // Configurer les options du graphique
        $options = [
            'type' => 'bar',
            'data' => [
                'labels' => ['Revenus', 'Dépenses'],
                'datasets' => [
                    [
                        'label' => 'Revenus',
                        'backgroundColor' => 'rgba(75, 192, 192, 0.5)',
                        'data' => $earnedData,
                    ],
                    [
                        'label' => 'Dépenses',
                        'backgroundColor' => 'rgba(255, 99, 132, 0.5)',
                        'data' => $payedData,
                    ],
                ],
            ],
            'options' => [
                'scales' => [
                    'yAxes' => [
                        [
                            'ticks' => [
                                'beginAtZero' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ];

        // Convertir les options en une chaîne JSON pour l'utiliser dans le template <link>EasyAdmin</link>
        $jsonOptions = json_encode($options);

        return $this->render('comptable/home.html.twig', [
            'jsonOptions' => $jsonOptions,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Comptable');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);

        yield MenuItem::section('Invoices');
        yield MenuItem::linkToCrud('Invoices', 'fa-solid fa-money-bill', Facture::class);

        yield MenuItem::section('Stock');
        yield MenuItem::linkToCrud('Stock', 'fa-solid fa-cubes', Stock::class);

        yield MenuItem::section('Transactions');
        yield MenuItem::linkToCrud('Transactions', 'fa-solid fa-cart-shopping', Transaction::class);
    }
}
