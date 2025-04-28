<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/annonce')]
class AnnonceController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/', name: 'annonce_index', methods: ['GET'])]
    public function index(AnnonceRepository $annonceRepository): Response
    {

        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonceRepository->findAll(),
        ]);
    }

    #[Route('/single', name: 'single_annonce', methods: ['GET'])]
    public function single_annonce(AnnonceRepository $repoAnnonce): Response
    {
        $annonce = $repoAnnonce->findBy(['active' => true]);
        return $this->render('annonce/single_annonce.html.twig',  [
            'annonce' => $annonce,

        ]);
    }




    // #[Route('/liste', name: 'liste', methods: ['GET'])]
    // public function liste(AnnonceRepository $annonceRepository, Request $request): Response
    // {
    //     //on définit le nombre d'élément par page
    //     $limit = 1;
    //     //on récupère le numéro de page
    //     $page = (int)$request->query->get("page", 1);
    //     //on récupère les annonces de la page
    //     $annonces = $annonceRepository->getPaginatedAnnonces($page, $limit);
    //     //on recupère le nombre total d'annonce
    //     $total = $annonceRepository->getTotalAnnonces();
    //     dd($total);

    //     return $this->render('annonce/index.html.twig', compact('annonces', 'total', 'limit', 'page'));
    // }



    #[Route('/new', name: 'annonce_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {

        $annonce = new Annonce();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $annonce->setUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($annonce);
            $entityManager->flush();


            //création d'un nouveau contact
            $annonce = new Annonce();
            //lorsque tous les informations saisies sont corrects l'envoie s'effectue sans problème
            $form = $this->createForm(AnnonceType::class, $annonce);
            $this->addFlash('annonce_success', "votre candidature a bien été envoyée. Nous essaierons de repondre dans les plus brefs délais");

            // return $this->redirectToRoute('annonce_index', [], Response::HTTP_SEE_OTHER);
        }
        // Mais lorsqu'il y a des erreurs, on a ce message flash qui s'affiche
        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('annonce_error', 'Ce formulaire contient des erreurs. Merci de revérifier puis de réessayer');
        }

        return $this->renderForm('annonce/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'annonce_show', methods: ['GET'])]
    public function show(Annonce $annonce): Response
    {

        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce,
        ]);
    }

    /**
     * @IsGranted("ROLE_COACH")
     */
    #[Route('/{id}/edit', name: 'annonce_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Annonce $annonce): Response
    {
        // $this->denyAccessUnlessGranted('annonce_edit', $annonce);
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annonce/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'annonce_delete', methods: ['POST'])]
    public function delete(Request $request, Annonce $annonce): Response
    {
        if ($this->isCsrfTokenValid('delete' . $annonce->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($annonce);
            $entityManager->flush();
        }

        return $this->redirectToRoute('annonce_index', [], Response::HTTP_SEE_OTHER);
    }



    #[Route('detail/{slug}', name: 'annonce_detail', methods: ['GET'])]
    public function annonceDetails($slug, Annonce $annonce): Response
    {


        return $this->render('annonce/annonceDetail.html.twig', [
            'annonce' => $annonce,
        ]);
    }
}
