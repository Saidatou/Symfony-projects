<?php

namespace App\Controller;


use App\Repository\ProductRepository;
use App\Services\Cart\CartServices;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartControllerbis extends AbstractController
{
    #[Route('/panier', name: 'cart_index')]
    public function index(CartServices $cartServices): Response
    {



        return $this->render('cart/index.html.twig', [
            'items' => $cartServices->getFullCart(),
            //je passe cette clé donc à mon twig
            'total' =>  $cartServices->getTotal()
        ]);
    }

    #[Route('/panier/add/{id}', name: 'cart_add')]
    public function add($id, CartServices $cartServices): Response
    {

        $cartServices->add($id);

        return $this->redirectToRoute("cart_index");



        // return $this->render('cart/index.html.twig', [
        //     'controller_name' => 'CartController',

        // ]);
    }

    #[Route('/panier/remove/{id}', name: 'cart_remove')]
    public function remove($id, CartServices $cartServices): Response
    {

        $cartServices->remove($id);

        return $this->redirectToRoute("cart_index");



        // return $this->render('cart/index.html.twig', [
        //     'controller_name' => 'CartController',

        // ]);
    }
}
