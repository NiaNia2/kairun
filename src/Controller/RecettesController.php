<?php

namespace App\Controller;

use App\Repository\ObjectifRepository;
use App\Repository\RecetteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RecettesController extends AbstractController
{
    #[Route('/recettes', name: 'app_recettes')]
    public function index(): Response
    {
        return $this->render('recettes/index.html.twig', [
            'controller_name' => 'RecettesController',
        ]);
    }

    #[Route('/perte-de-poids', name: 'perte_de_poids')]
    public function perteDePoids(RecetteRepository $recetteRepository, ObjectifRepository $objectifRepository): Response
    {
        $objectif = $objectifRepository->findOneBy(['nom' => 'Perte de poid']);
        $recettes = $recetteRepository->findBy(['objectif' => $objectif]);

        return $this->render('recettes/perte_de_poids.html.twig', [
            'objectif' => $objectif,
            'recettes' => $recettes,
        ]);
    }

    #[Route('/prise-de-masse', name: 'prise_de_masse')]
    public function priseDeMasse(RecetteRepository $recetteRepository, ObjectifRepository $objectifRepository): Response
    {
        $objectif = $objectifRepository->findOneBy(['nom' => 'Prise de masse']);
        $recettes = $recetteRepository->findBy(['objectif' => $objectif]);

        return $this->render('recettes/prise_de_masse.html.twig', [
            'objectif' => $objectif,
            'recettes' => $recettes,
        ]);
    }
}
