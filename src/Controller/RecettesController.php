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
            if ($this->getUser()) {
                $recette->setUser($this->getUser());
            }
            $em->persist($recette);
            $em->flush();

            return $this->redirectToRoute('app_recettes');
        }

        return $this->render('recettes/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/perte-de-poids', name: 'perte_de_poids')]
    public function perteDePoids(Request $request, RecetteRepository $recetteRepository, ObjectifRepository $objectifRepository, TypeDeRepasRepository $typeDeRepasRepository): Response
    {
        $objectif = $objectifRepository->findOneBy(['nom' => 'Perte de poids']);

        $types = $typeDeRepasRepository->findAll();


        $selectedTypeId = $request->query->getInt('type', 0);
        $selectedType = null;
        $critere = ['objectif' => $objectif];

        if ($selectedTypeId > 0) {
            $selectedType = $typeDeRepasRepository->find($selectedTypeId);

            if ($selectedType) {

                $critere['typeDeRepas'] = $selectedType;
            }
        }

        $recettesFiltres = $recetteRepository->findBy(
            $critere,
            ['cree_le' => 'DESC']
        );
        $dernieresRecettes = $recetteRepository->findBy(
            ['objectif' => $objectif],
            ['cree_le' => 'DESC'],
            3

        );
        return $this->render('recettes/perte_de_poids.html.twig', [
            'objectif' => $objectif,
            'types' => $types,
            'selectedType' => $selectedType,
            'selectedTypeId' => $selectedTypeId,
            'recettesFiltres' => $recettesFiltres,
            'dernieresRecettes' => $dernieresRecettes,

        ]);
    }


    #[Route('/prise-de-masse', name: 'prise_de_masse')]
    public function priseDeMasse(Request $request, RecetteRepository $recetteRepository, ObjectifRepository $objectifRepository, TypeDeRepasRepository $typeDeRepasRepository): Response
    {
        $objectif = $objectifRepository->findOneBy(['nom' => 'Prise de masse']);

        $types = $typeDeRepasRepository->findAll();


        $selectedTypeId = $request->query->getInt('type', 0);
        $selectedType = null;
        $critere = ['objectif' => $objectif];

        if ($selectedTypeId > 0) {
            $selectedType = $typeDeRepasRepository->find($selectedTypeId);

            if ($selectedType) {

                $critere['typeDeRepas'] = $selectedType;
            }
        }

        $recettesFiltres = $recetteRepository->findBy(
            $critere,
            ['cree_le' => 'DESC']
        );
        $dernieresRecettes = $recetteRepository->findBy(
            ['objectif' => $objectif],
            ['cree_le' => 'DESC'],
            3

        );
        return $this->render('recettes/prise_de_masse.html.twig', [
            'objectif' => $objectif,
            'types' => $types,
            'selectedType' => $selectedType,
            'selectedTypeId' => $selectedTypeId,
            'recettesFiltres' => $recettesFiltres,
            'dernieresRecettes' => $dernieresRecettes,

        ]);
    }
}
