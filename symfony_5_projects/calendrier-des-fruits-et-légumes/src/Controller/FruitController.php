<?php

namespace App\Controller;

use App\Entity\Fruit;
use App\Form\FruitType;
use App\Repository\FruitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/fruit')]
class FruitController extends AbstractController
{
    #[Route('/', name: 'fruit_index', methods: ['GET'])]
    public function index(FruitRepository $fruitRepository): Response
    {
        return $this->render('fruit/index.html.twig', [
            'fruits' => $fruitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'fruit_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $fruit = new Fruit();
        $form = $this->createForm(FruitType::class, $fruit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($fruit);
            $entityManager->flush();

            return $this->redirectToRoute('fruit_index');
        }

        return $this->render('fruit/new.html.twig', [
            'fruit' => $fruit,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'fruit_show', methods: ['GET'])]
    public function show(Fruit $fruit): Response
    {
        return $this->render('fruit/show.html.twig', [
            'fruit' => $fruit,
        ]);
    }

    #[Route('/{id}/edit', name: 'fruit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Fruit $fruit): Response
    {
        $form = $this->createForm(FruitType::class, $fruit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('fruit_index');
        }

        return $this->render('fruit/edit.html.twig', [
            'fruit' => $fruit,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'fruit_delete', methods: ['POST'])]
    public function delete(Request $request, Fruit $fruit): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fruit->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($fruit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('fruit_index');
    }
}
