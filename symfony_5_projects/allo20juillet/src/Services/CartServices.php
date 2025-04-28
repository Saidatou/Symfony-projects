<?php

namespace App\Services;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartServices
{
    //mettre tout ce qui est lié à notre panier : ajout, suppression, consultation etc
    //creation d'un constructeur à l'intérieur duquel la session va être initialiasée
    private $session;
    private $repoProduct;
    private $tva = 0.2; // la TVA est 20% du PHT 
    private $coefmulti = 1.20; //PTTC qui sera affiché sur le site,
    //  1.2 est donc le coefficient multiplicateur
    //Prix T.T.C. = Prix H.T. × Coefficient Multiplicateur associé à la TVA
    //Prix H.T. = Prix T.T.C. ÷ Coefficient Multiplicateur associé à la TVA
    //Prix T.T.C = Prix H.T. + Montant des Taxes

    public function __construct(SessionInterface $session, ProductRepository $repoProduct)
    {
        $this->session = $session;
        $this->repoProduct = $repoProduct;
    }
    // definition des actions de notre panier par le biais de différents méthodes
    //1- ajouts de produit par le biais de ID. si le produit n'est pas encore dans le panier, on l'incrémente. 

    public function addToCart($id)
    {
        //on recupère le panier, 
        $cart = $this->getCart();
        //on regarde si l'élément est définit. S'il est déjà dans le panier
        if (isset($cart[$id])) {
            // produit déja dans le panier
            $cart[$id]++;
        } else {
            // le produit n'est pas encore dans le panier on va le créer et l'intialisé à un 1
            $cart[$id] = 1;
        } // On met ensuite à jour le panier
        $this->updateCart($cart);
    }


    //2- suppression de produit par le biais de ID
    public function deleteFromCart($id)
    {
        // On recupère le panier
        $cart = $this->getCart();
        // est ce que le produit existe dans le panier? 2 cas: le cas l'élément existe plus d'une fois dans le panier
        // est que le produit est supérieur à 1 on lui enlève un élément

        if (isset($cart[$id])) {
            //produit déjà dans le panier, exist 'il plus d'une fois 
            if ($cart[$id] > 1) {
                //produit existe plus d'une fois on lui enlève 
                $cart[$id]--;
            } else { // il n'y a qu'un seul produit ddans le panier
                unset($cart[$id]);
            } // On met à jour le panier
            $this->updateCart($cart);
        }
    }

    // vider le contenue d'un produit du panier
    public function deleteAllToCart($id)
    {
        //est ce que l'élémnent dasns le panier
        $cart = $this->getCart();

        if (isset($cart[$id])) {
            //produit déjà dans le panier
            unset($cart[$id]);
            $this->updateCart($cart);
        }
    }

    //Vider tout le panier
    public function deleteCart()
    {
        // on remet le panier à 0. retourner un tableau vide
        $this->updateCart([]);
    }

    //creer une deuxème variable en lien avec le mouvement du panier pour les calcules fianaux
    public function updateCart($cart)
    {
        $this->session->set('cart', $cart);
        //metre le contenue réelle du panier. qui est la méthode get fullcart
        $this->session->set('cartData', $this->getFullCart());
    }
    //recupérer le panier
    public function getCart()
    {
        return $this->session->get('cart', []);
    }

    // création d'un methode de récupération des produits dans le panier 
    public function getFullCart()
    {
        $cart = $this->getCart();

        $fullCart = [];
        //qté sera incrémenté dans l boucle foreach ainsi que le sous total

        $quantity_cart = 0;
        $subTotal = 0;
        // nous allons parcourir le contenu du panier par son identifiant et la qté
        foreach ($cart as $id => $quantity) {
            // il faut recupérer les produit à partir des repositorys. On va donc créer private proprié
            //Aller donc l'injecter au début de la class et l'injecter dans le Construct 
            $product = $this->repoProduct->find($id);
            //Si on arrive à recupérer corrcement le produit,
            if ($product) {
                // Produit récupéré avec succès, Dans ce cas il faut l'ajouter à un tableau
                $fullCart['products'][] =
                    //mise de tout les produits dans une première clé ['products']
                    //[à l'intériieur nous mettrions les prduits que nous avons]
                    [
                        "quantity" => $quantity,
                        "product" => $product,

                    ];
                //incrémentation de la quantité   
                $quantity_cart += $quantity;
                //on incrémente le sous total avec(Qté*prix)
                $subTotal += $quantity * $product->getPrice(); ///100;
            } else {
                //Dans le cas contraire id incorrecte, on a enlever ce produit du panier
                $this->deleteFromCart($id);
            }
        }
        //deuxième clé ['data'] contient les informations sur le produit
        $fullCart['data'] = [
            "quantity_cart" => $quantity_cart,
            // "subTotalHT" => $subTotal,
            "subTotalTTC" => $subTotal,
            //le round ...n, 2 indiquequ'on souhaite avoir 2 chiffre après la virguel lors du calcul
            "Taxe" => round((($subTotal / $this->coefmulti) * $this->tva), 2),
            // "Taxe" => round($subTotal / $this->tva, 2),
            "subTotalHT" => round(($subTotal / $this->coefmulti), 2)

            // "subTotalTTC" => round(($subTotal + ($subTotal * $this->tva)), 2)
        ];

        return $fullCart;
    }
}
