<?php

namespace App\Controller;


use App\Repository\VentesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EditorsController extends AbstractController
{
    #[Route('/editors', name: 'editors')]
    public function index(VentesRepository $repoVentes, ): Response

    {
        $ventes=$repoVentes->findAll();
        return $this->render('editors/show-editor-produits.html.twig', [
            'ventes' => $ventes,

        ]);
    }

    #[Route('/editors/conditions', name: 'editors_conditions')]
    public function indexConditions(): Response
    {
        return $this->render('editors/conditions.html.twig', [
            'controller_name' => 'EditorsController',
        ]);
    }

    #[Route('/editors/commandes', name: 'editors_commandes')]
    public function indexCommandes(): Response
    {
        return $this->render('editors/index.html.twig', [
            'controller_name' => 'EditorsController',
        ]);
    }

  
   

   


}
