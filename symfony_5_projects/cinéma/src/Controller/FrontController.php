<?php

namespace App\Controller;

use App\Entity\Artiste;
use App\Entity\Film;
use App\Repository\FilmRepository;
use App\Repository\ArtisteRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="front")
     */
    public function index(FilmRepository $filmRepository, ArtisteRepository $artisteRepository): Response
    {

        $films= $filmRepository->findAll();
        $artistes = $artisteRepository->findAll();

        return $this->render('front/index.html.twig', [
            'films' => $films,
            "artistes"=>$artistes
        ]
    );
    }

    /**
     * @Route("/fiche-film/{id}", name="film_front", methods={"GET"})
     */
    public function showFilm(Film $film): Response
    {
        return $this->render('front/show-film.html.twig', [
            'film' => $film,
        ]);
    }

    /**
     * @Route("/fiche-artiste/{id}", name="artiste_front", methods={"GET"})
     */
    public function showArtiste(Artiste $artiste): Response
    {
        return $this->render('front/show-artiste.html.twig', [
            'artiste' => $artiste,
        ]);
    }
    /**
     * @Route("/test", name="test")
     */
    public function test(FilmRepository $repo, ArtisteRepository $repoArtiste){
       // Faire un dump de tous les films
       $tousLesfilms=$repo->findAll();
       //dump("Tous les films", $tousLesfilms);
     
       // Récupérer un film grace à son id
       $film=$repo->find(15);
       //dump("find id 15",$film);

       // Récupérer tous les films ayant le titre Oceans's Twelve et l'affiche test.jpg
       $film2 = $repo->findBy(["titre"=>"Ocean's Twelve"]);
      // dump("find by titre", $film2);

       $tousLesFilmsOrdonnes=$repo->findBy( [], ["titre"=>"ASC"]);
       //dump("order by titre", $tousLesFilmsOrdonnes);

       $tousLesFilmsOrdonnesParDate=$repo->findBy( [], ["dateDeSortie"=>"DESC"]);
       //dump("order by date", $tousLesFilmsOrdonnesParDate);
  

       // Récupérer une seul objet grace à ses propriétés
       $film3=$repo->findOneBy(["titre"=>"Ocean's Eleven"]);
       //dump("find one by", $film3);

        // récupérer tous les films de Steven Sodderbergh
        $realisateur=$repoArtiste->findOneBy(["nom"=>"Soderberhg"]);
        //dump($realisateur);
        $filmsDeSoderberhg=$repo->findBy(["realisateur"=>$realisateur]);
        //dump("films de soderberhg", $filmsDeSoderberhg);

       
        //$filmsDate=$repo->
        //dump($filmsDate);

        $requetePersonnalisee=$repo->findByDateInterval("Soderberhg");
       dump($requetePersonnalisee);

       $search=$repo->searchByTitle("ocean");
       dump($search);

       $test=$repo->findByActeur($repoArtiste->findOneBy(["nom"=>"Roberts",
       "prenom"=>"Julia"]));
       dump($test);


        return $this->render("front/test.html.twig");

       

    }
     /**
         * @Route("/test-artistes", name="test2")
         */
        public function test2(ArtisteRepository $repoArtiste){
            //recupérer les artistes le nom est Affleck,
            $affleck=$repoArtiste->findBy(["nom"=>"Affleck"]);
            dump($affleck);

             //recupérer les artistes le preom est Alberts,
             $alberts=$repoArtiste->findBy(["prenom"=>"Albert"]);
             dump($alberts);

             // récuperer tous les artistes d'OceansEleven
             $ocean=$repoFilm->findOneBy(["titre"=>"ocean's Eleven"]);
             dump($ocean);
             $artistesOcean=$repoArtiste->FindByFilm($ocean);
             dump($artistesOcean)

 
            return $this->render("front/test.html.twig");
        }
}
