<?php

namespace App\Controller;

use App\Entity\Places;
use App\Form\PlacesType;
use App\Repository\PlacesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/places')]
class PlacesController extends AbstractController
{
    #[Route('/', name: 'places_index', methods: ['GET'])]
    public function index(PlacesRepository $placesRepository): Response
    {
        return $this->render('places/index.html.twig', [
            'places' => $placesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'places_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $place = new Places();
        $form = $this->createForm(PlacesType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($place);
            $entityManager->flush();

            return $this->redirectToRoute('places_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('places/new.html.twig', [
            'place' => $place,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'places_show', methods: ['GET'])]
    public function show(Places $place): Response
    {
        return $this->render('places/show.html.twig', [
            'place' => $place,
        ]);
    }

    #[Route('/{id}/edit', name: 'places_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Places $place): Response
    {
        $form = $this->createForm(PlacesType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('places_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('places/edit.html.twig', [
            'place' => $place,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'places_delete', methods: ['POST'])]
    public function delete(Request $request, Places $place): Response
    {
        if ($this->isCsrfTokenValid('delete'.$place->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($place);
            $entityManager->flush();
        }

        return $this->redirectToRoute('places_index', [], Response::HTTP_SEE_OTHER);
    }
}
