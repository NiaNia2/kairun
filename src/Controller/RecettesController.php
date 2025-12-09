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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;


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
    #[IsGranted('ROLE_USER')]
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

        return $this->render('recettes/create_recette.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/perte-de-poids', name: 'perte_de_poids')]
    public function perteDePoids(
        Request $request,
        RecetteRepository $recetteRepository,
        ObjectifRepository $objectifRepository,
        TypeDeRepasRepository $typeDeRepasRepository
    ): Response {
        //  On récupère l'objectif "Perte de poids"
        $objectif = $objectifRepository->findOneBy(['nom' => 'Perte de poids']);

        $types = $typeDeRepasRepository->findAll();

        $selectedTypeId = $request->query->getInt('type', 0);
        $selectedType = null;


        $critere = ['objectif' => $objectif];

        // Si un type est sélectionné on l'ajoute aux critères
        if ($selectedTypeId > 0) {
            $selectedType = $typeDeRepasRepository->find($selectedTypeId);

            if ($selectedType) {
                $critere['typeDeRepas'] = $selectedType;
            }
        }

        // Recettes filtrées 
        $recettesFiltres = $recetteRepository->findBy(
            $critere,
            ['cree_le' => 'DESC']
        );

        //  3 dernières recettes pour "Perte de poids"
        $dernieresRecettes = $recetteRepository->findBy(
            ['objectif' => $objectif],
            ['cree_le' => 'DESC'],
            3
        );

        return $this->render('recettes/perte_de_poids.html.twig', [
            'objectif'          => $objectif,
            'types'             => $types,
            'selectedType'      => $selectedType,
            'selectedTypeId'    => $selectedTypeId,
            'recettesFiltres'   => $recettesFiltres,
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
    #[Route('/recettes/{objectif}/{type}', name: 'recettes_par_type')]
    public function recettesParType(
        string $objectif,
        string $type,
        RecetteRepository $recetteRepository,
        ObjectifRepository $objectifRepository,
        TypeDeRepasRepository $typeDeRepasRepository
    ): Response {

        // Slugs de l’URL -> nom EXACT en base
        $mapObjectif = [
            'perte-de-poids' => 'Perte de poids',
            'prise-de-masse' => 'Prise de masse',
        ];

        $mapType = [
            //  DOIT correspondre exactement à la colonne "nom" dans type_de_repas
            'petit-dejeuner' => 'Petit dejeuner',
            'dejeuner'       => 'Déjeuner',
            'diner'          => 'Dîner',
            'collation'      => 'Collations',
        ];

        // Si le slug ne correspond pas à une clé, on 404 direct
        if (!isset($mapObjectif[$objectif]) || !isset($mapType[$type])) {
            throw $this->createNotFoundException(' type inconnu.');
        }

        // On va chercher les entités exactes en base
        $objectifEntity = $objectifRepository->findOneBy(['nom' => $mapObjectif[$objectif]]);
        $typeEntity     = $typeDeRepasRepository->findOneBy(['nom' => $mapType[$type]]);

        if (!$objectifEntity || !$typeEntity) {
            throw $this->createNotFoundException('Objectif ou type introuvable en base.');
        }

        $recettes = $recetteRepository->findBy(
            [
                'objectif'    => $objectifEntity,
                'typeDeRepas' => $typeEntity,
            ],
            ['cree_le' => 'DESC']
        );

        return $this->render('recettes/par_type.html.twig', [
            'objectif' => $objectifEntity,
            'type'     => $typeEntity,
            'recettes' => $recettes,
        ]);
    }
    #[Route('/recette/{id}', name: 'recette_show', requirements: ['id' => '\d+'])]
    public function show(Recette $recette): Response
    {
        return $this->render('recettes/recette_show.html.twig', [
            'recette' => $recette,
        ]);
    }

    #[Route('/recette/{id}/modifier', name: 'recette_edit')]
    public function edit(Recette $recette, Request $request, EntityManagerInterface $em): Response
    {

        if ($recette->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('recette_show', [
                'id' => $recette->getId()
            ]);
        }

        return $this->render('recettes/create_recette.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/recette/{id}/supprimer', name: 'recette_delete', methods: ['POST'])]
    public function delete(Recette $recette, Request $request, EntityManagerInterface $em): Response
    {

        if ($recette->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        if ($this->isCsrfTokenValid('delete_recette_' . $recette->getId(), $request->request->get('_token'))) {
            $em->remove($recette);
            $em->flush();
        }

        return $this->redirectToRoute('perte_de_poids');
    }
}
