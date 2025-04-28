<?php

namespace App\Controller;

use App\Entity\HomeSlider;
use App\Form\HomeSliderType;
use App\Repository\HomeSliderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/home/slider')]
class HomeSliderController extends AbstractController
{
    #[Route('/', name: 'home_slider_index', methods: ['GET'])]
    public function index(HomeSliderRepository $homeSliderRepository): Response
    {
        return $this->render('home_slider/index.html.twig', [
            'home_sliders' => $homeSliderRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'home_slider_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $homeSlider = new HomeSlider();
        $form = $this->createForm(HomeSliderType::class, $homeSlider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($homeSlider);
            $entityManager->flush();

            return $this->redirectToRoute('home_slider_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('home_slider/new.html.twig', [
            'home_slider' => $homeSlider,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'home_slider_show', methods: ['GET'])]
    public function show(HomeSlider $homeSlider): Response
    {
        return $this->render('home_slider/show.html.twig', [
            'home_slider' => $homeSlider,
        ]);
    }

    #[Route('/{id}/edit', name: 'home_slider_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, HomeSlider $homeSlider): Response
    {
        $form = $this->createForm(HomeSliderType::class, $homeSlider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('home_slider_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('home_slider/edit.html.twig', [
            'home_slider' => $homeSlider,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'home_slider_delete', methods: ['POST'])]
    public function delete(Request $request, HomeSlider $homeSlider): Response
    {
        if ($this->isCsrfTokenValid('delete'.$homeSlider->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($homeSlider);
            $entityManager->flush();
        }

        return $this->redirectToRoute('home_slider_index', [], Response::HTTP_SEE_OTHER);
    }
}
