<?php

namespace App\Controller\Admin;

use App\Repository\RecetteRepository;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    private RecetteRepository $recetteRepository;

    public function __construct(RecetteRepository $recetteRepository)
    {
        $this->recetteRepository = $recetteRepository;
    }

    public function index(): Response
    {
        // Nombre total de recettes
        $totalRecettes = $this->recetteRepository->count([]);

        // 5 dernières recettes créées
        $derniereRecettes = $this->recetteRepository->findBy([], ['cree_le' => 'DESC'], 5);

        return $this->render('admin/index.html.twig', [
            'user'              => $this->getUser(),
            'totalRecettes'     => $totalRecettes,
            'derniereRecettes'  => $derniereRecettes,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Kairun');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Site');
        yield MenuItem::linkToRoute('Retour au site', 'fa fa-arrow-left', 'home');
    }
}
