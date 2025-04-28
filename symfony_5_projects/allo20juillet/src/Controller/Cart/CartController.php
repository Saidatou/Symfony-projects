<?php

namespace App\Controller\Cart;

use App\Services\CartServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    //comme on besoin de cart service un peut partout, il est donc préférable 
    //de le mettre dans un constructeur
    private $cartServices;

    public function __construct(CartServices $cartServices)
    {
        $this->cartServices = $cartServices;
    }
    /**
     * @Route("/cart", name="cart")
     */
    public function index(): Response
    {
        $cart = $this->cartServices->getFullCart();
        if (!isset($cart['products'])) {
            return $this->redirectToRoute("cart");
        }
        return $this->render('cart/index.html.twig', [
            'cart' => $cart
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="cart_add")
     */
    public function addToCart($id): Response
    {
        $this->cartServices->addToCart($id);
        // a chaque ajout on redirige l'ulisateur sur le panier
        return $this->redirectToRoute("cart");
    }

    /**
     * @Route("/cart/delete/{id}", name="cart_delete")
     */
    public function deleteFromCart($id): Response
    {
        $this->cartServices->deleteFromCart($id);
        //a chaque retrait on redirige aussi sur le panier
        return $this->redirectToRoute("cart");
    }

    /**
     * @Route("/cart/delete-all/{id}", name="cart_delete_all")
     */
    public function deleteAllToCart($id): Response
    {
        $this->cartServices->deleteAllToCart($id);
        return $this->redirectToRoute("cart");
    }
}
