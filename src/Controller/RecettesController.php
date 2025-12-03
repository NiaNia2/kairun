<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Form\RecetteType;
use App\Repository\RecetteRepository;
use App\Repository\ObjectifRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TypeDeRepasRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class RecettesController extends AbstractController
{
    #[Route('/recettes', name: 'app_recettes')]
    public function index(): Response
    {
        return $this->render('recettes/index.html.twig', [
            'controller_name' => 'RecettesController',
        ]);
    }
    #[Route('/recette/nouvelle', name: 'recette_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $recette = new Recette();
        $form = $this->createForm(RecetteType::class, $recette);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recette->setCreeLe(new \DateTimeImmutable());

            $em->persist($recette);
            $em->flush();

            return $this->redirectToRoute('app_recettes');
        }

        return $this->render('recettes/new.html.twig', [
            'form' => $form->createView(),
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
    #[Route('/perte-de-poids/petit-dejeuner', name: 'perte_poids_petit_dejeuner')]
    public function perteDePoidsPetitDejeuner(RecetteRepository $recetteRepository, ObjectifRepository $objectifRepository, TypeDeRepasRepository $typeDeRepasRepository): Response
    {
        $objectif = $objectifRepository->findOneBy(['nom' => 'Perte de poid']);
        $typeDeRepas = $typeDeRepasRepository->findOneBy(['nom' => 'Petit déjeuner']);
        $recettes = $recetteRepository->findBy(['objectif' => $objectif, 'typeDeRepas' => $typeDeRepas]);

        return $this->render('recettes/perte_poids_petit_dejeuner.html.twig', [
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
