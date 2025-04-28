<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/cgu')]
class CGUController extends AbstractController
{
    #[Route('/', name: 'cgu')]
    public function index(): Response
    {
        return $this->render('cgu/index.html.twig', [
            'controller_name' => 'CGUController',
        ]);
    }




    #[Route('/buy', name: 'cgu_buy')]
    public function buy(): Response
    {
        return $this->render('cgu/buy.html.twig', [
            'controller_name' => 'CGUController',
        ]);
    }



    #[Route('/calc', name: 'cgu_calc')]
    public function Calc(): Response
    {


        $a = 0;
        $b = 0;
        $c = 0;
        $mess = "";
        return $this->render('cgu/calcul.html.twig');
    }

    #[Route('/calcul', name: 'cgu_calcul')]
    public function Calcul(Request $request): Response
    {

        $calc = new Calc();
        $mess = "";
        $a = 0;
        $b = 0;
        $c = 0;
        $mess = "";
        return $this->render('cgu/calcul.html.twig');
    }
}
