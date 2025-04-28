<?php

namespace App\Controller;

use App\Entity\Ventes;
use App\Form\VentesType;
use App\Repository\VentesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ventes')]
class VentesController extends AbstractController
{
    #[Route('/', name: 'ventes_index', methods: ['GET'])]
    public function index(VentesRepository $ventesRepository): Response
    {
        return $this->render('ventes/index.html.twig', [
            'ventes' => $ventesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'ventes_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $vente = new Ventes();
        $form = $this->createForm(VentesType::class, $vente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vente);
            $entityManager->flush();

            return $this->redirectToRoute('ventes_index');
        }

        return $this->render('ventes/new.html.twig', [
            'vente' => $vente,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'ventes_show', methods: ['GET'])]
    public function show(Ventes $vente): Response
    {
        return $this->render('ventes/show.html.twig', [
            'vente' => $vente,
        ]);
    }

    #[Route('/{id}/edit', name: 'ventes_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ventes $vente): Response
    {
        $form = $this->createForm(VentesType::class, $vente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ventes_index');
        }

        return $this->render('ventes/edit.html.twig', [
            'vente' => $vente,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'ventes_delete', methods: ['POST'])]
    public function delete(Request $request, Ventes $vente): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vente->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($vente);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ventes_index');
    }
}
