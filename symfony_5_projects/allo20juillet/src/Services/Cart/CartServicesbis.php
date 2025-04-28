<?php

namespace App\Services\Cart;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartServicesbis

{


    //mettre tout ce qui est lié à notre panier : ajout, suppression, consultation etc
    //creation d'un constructeur à l'intérieur duquel la session va être initialiasée

    protected $session;
    // private $repoProduct;
    // private $tva = 0.2;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    public function add(int $id)
    {
        //On récupère un tableau vide, on regarde s'il y a un panier par défaut si pas de panier je prend un vide
        $panier = $this->session->get('panier', []);
        //on recupère chaque produit par son identiant si le panier contient déjà un article on rajoute la qté voulu
        if (!empty($panier[$id])) {
            $panier[$id]++; // je veux rajouter quelque chose
        } else {
            $panier[$id] = 1;
        }
        // ce qui a été dans mon panier je le recupère itialiser à 1 pour faire la visite dans le magasin
        // mais qui été altéré par les articles que je mets à l'intérieur
        $this->session->set('panier', $panier);
    }

    public function remove(int $id)
    {
        //On récupère un tableau vide, on regarde s'il y a un panier par défaut si pas de panier je prend un vide
        $panier = $this->session->get('panier', []);
        //on recupère chaque produit par son identiant si le panier contient déjà un article je vais le supprimer
        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }

        // 
        // mais qui été altéré par les articles que je retiré à l'intérieur
        $this->session->set('panier', $panier);
        // je vais retourner donc à mon panier et dans mon bouton du twig, je vais mettre la route du cart_index
        // afin que l'utilisateur puisse déclencher l'action

    }
    public function getFullCart(): array
    {
        //On récupère un tableau vide, on regarde s'il y a un panier par défaut si pas de panier je prend un vide
        $panier = $this->session->get('panier', []);
        //stock le contenu du panier dans un tableau
        $panierWithData = [];
        //On boucle sur ce contenu stocké en fonction de l'identifiant et de la quantité.
        foreach ($panier as $id => $quantity) {
            //ajout dans le tableau $panierWithData on autre tableau contenant le couple clés de chaque produit et la quantité
            $panierWithData[] = [ // Je me fais livré  la fonction find du product repository 
                'product' => $this->productRepository->find($id),
                'quantity' => $quantity
            ];
        }
        return $panierWithData;
    }
    public function getTotal(): float
    {


        //le total se fait dans le controleur avec la boucle foreach sur notre panierWithData
        //dans laquelle on va preendre à chaque fois un item
        $total = 0; //

        foreach ($this->getFullCart() as $item) {
            //le toltalItem est égal item dans lequel il y a une case product qui reprente un produit et qui a une
            //une méthode get price qui nous permet de trouver son prix avec lequel je peut multiplier avec 
            //l'item de la quantité
            // le total sera donc celui de mon twig
            $total = $item['product']->getPrice() * $item['quantity'];
        }
        return $total;
    }
}
