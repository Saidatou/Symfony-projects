<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Product;
use App\Entity\SearchCoach;
use App\Entity\PropertySearch;
use App\Form\SearchAnnonceType;
use App\Form\PropertySearchType;
use App\Form\SearchAnnonceBigType;
use App\Repository\AnnonceRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Repository\HomeSliderRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(AnnonceRepository $repoAnnonce, Request $request): Response
    {
        $annonce = $repoAnnonce->findBy(['active' => true]);
        //affichage du formulaire créer et manipulation ds le SearchAnnonceType
        $form = $this->createForm(SearchAnnonceType::class);
        $search = $form->handleRequest($request);
        //traitement de la recherche du fomulaire

        if ($form->isSubmitted() && $form->isValid()) {
            //on recherche les annonces correspondants aux mots clé saisi avec le repository
            $annonce = $repoAnnonce->search(
                //pour les mots clés
                $search->get('mots')->getData(),
                //pour les département
                $search->get('departement')->getData()
            );
        }
        // // Mais lorsqu'il y a des erreurs, on a ce message flash qui s'affiche
        // if ($form->isSubmitted() && $form->isValid() && $annonce = null) {
        //     $this->addFlash('search_error', 'Désolé ');
        // }

        // $annonces = $repoAnnonce->findAll();
        return $this->render('home/index.html.twig',   [
            'annonces' => $annonce,
            'form' => $form->createView()
        ]);
    }


    // routes affichage des produits
    #[Route('/home/products', name: 'home_product')]
    public function home_product(ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        $products = $productRepository->findAll();
        $categories = $categoryRepository->findAll();



        return $this->render('product/indexbis.html.twig', [
            'products' => $products,
            'categories' => $categories,

        ]);
    }

    // route pour l'affichage des commentaires des étoiles

    #[Route('/home/stars', name: 'home_stars')]
    public function home_stars(): Response
    {

        return $this->render('home/stars.html.twig');
    }

    /**
     * @Route("/annonce/{slug}", name="annonce_details")
     */
    #[Route('/home/stars', name: 'home_stars')]
    public function show(?Annonce $annonce): Response
    {

        // if (!$annonce) {
        //     return $this->redirectToRoute("home");
        // }

        return $this->render("home/stars.html.twig", [
            'product' => $annonce
        ]);
    }

    /**
     * @Route("/coach", name="coach")
     */
    public function coach(AnnonceRepository $repoAnnonce, Request $request): Response
    {
        $annonces = $repoAnnonce->findBy(['active' => true]);
        //création de la variable $search avec les propriétés de l'entité créée
        $search = new SearchCoach();
        $form = $this->createForm(SearchAnnonceBigType::class, $search);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $annonces = $repoAnnonce->findWithSearch($search);
        }

        return $this->render('home/coach.html.twig', [
            'annonces' => $annonces,
            'search' => $form->createView()
        ]);
    }

    /**
     * @Route("/a_details/{id}", name="a_details")
     */
    public function details($id, AnnonceRepository $annoncesRepo,)
    {
        $annonce = $annoncesRepo->findOneBy(['id' => $id]);





        return $this->render('home/a_details.html.twig', [
            'annonce' => $annonce,


        ]);
    }
}
