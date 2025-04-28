<?php

namespace App\Controller\Admin;

use App\Entity\Annonce;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/admin')]
class AdminController extends AbstractController
{

    #[Route('/', name: 'admin')]

    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    //cette methode nous permet d'activer ou de désactiver une annonce

    #[Route('/annonce/activer/{id}', name: 'activer')]

    public function activer(?Annonce $annonce)
    {

        $annonce->setActive(($annonce->getActive()) ? false : true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($annonce);
        $em->flush();

        return new Response("true");
    }

    //cette methode nous permet de supprimer une annonce

    #[Route('/annonce/supprimer/{id}', name: 'supprimer')]

    public function supprimer(?Annonce $annonce)
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($annonce);
        $em->flush();

        $this->addFlash('message', 'Annonce supprimée avec succes');

        return $this->redirectToRoute("annonce_index");
    }
}
