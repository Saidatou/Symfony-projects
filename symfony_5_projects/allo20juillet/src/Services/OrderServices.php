<?php

namespace App\Services;

use App\Entity\Cart;
use App\Entity\Order;
use App\Entity\CartDetails;
use App\Entity\OrderDetails;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;


// définition de la class
class OrderServices
{

    private $manager;
    private $repoProduct;

    public function __construct(EntityManagerInterface $manager, ProductRepository $repoProduct)
    {
        $this->manager = $manager;
        $this->repoProduct = $repoProduct;
    }
    //création de méthode qui servira à créer une commande qui prendra en paramètre un panier
    public function createOrder($cart)
    {

        $order = new Order();
        $order->setReference($cart->getReference())
            ->setFullName($cart->getFullName())
            ->setDeliveryAddresss($cart->getDeliveryAddresss())
            ->setCoachName($cart->getCoachName())
            ->setCaochCity($cart->getCaochCity())
            ->setCoachId($cart->getCoachId())
            ->setMoreInformations($cart->getMoreInformations())
            ->setQuantity($cart->getQuantity())
            ->setSubtotalHT($cart->getSubTotalHT() / 100)
            ->setTaxe($cart->getTaxe() / 100)
            ->setSubtotalTTC($cart->getSubTotalTTC() / 100)
            ->setUser($cart->getUser())
            ->setCreatedAt($cart->getCreatedAt());
        $this->manager->persist($order);
// on peut récupérer les détail du panier
        $products = $cart->getCartDetails()->getValues();

        foreach ($products as $cart_product) {
            $orderDetails = new OrderDetails();
            $orderDetails->setOrders($order)
                ->setProductName($cart_product->getProductName())
                ->setProductPrice($cart_product->getProductPrice())
                ->setQuantity($cart_product->getQuantity())
                ->setSubTotalHT($cart_product->getSubTotalHT())
                ->setSubTotalTTC($cart_product->getSubTotalTTC())
                ->setTaxe($cart_product->getTaxe());
            $this->manager->persist($orderDetails);
        }

        $this->manager->flush();

        return $order;
    }

    public function getLineItems($cart)
    {
        $cartDetails = $cart->getCartDetails();

        $line_items = [];
        foreach ($cartDetails as $details) {
            $product = $this->repoProduct->findOneByName($details->getProductName());

            $line_items[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => $product->getPrice(),
                    'product_data' => [
                        'name' => $product->getName(),
                        'images' => [$_ENV['YOUR_DOMAIN'] . '/uploads/products/' . $product->getImage()],
                    ],
                ],
                'quantity' =>  $details->getQuantity(),
            ];
        }

        // Carrier
        $line_items[] = [
            'price_data' => [
                'currency' => 'usd',
                'unit_amount' => $cart->getCarrierPrice(),
                'product_data' => [
                    'name' => 'Carrier ( ' . $cart->getCarrierName() . ' )',
                    'images' => [$_ENV['YOUR_DOMAIN'] . '/uploads/products/'],
                ],
            ],
            'quantity' =>  1,
        ];

        // Taxe
        $line_items[] = [
            'price_data' => [
                'currency' => 'usd',
                'unit_amount' => $cart->getTaxe(),
                'product_data' => [
                    'name' => 'TVA (20%)',
                    'images' => [$_ENV['YOUR_DOMAIN'] . '/uploads/products/'],
                ],
            ],
            'quantity' =>  1,
        ];


        return $line_items;
    }
    //création d'une méthode qui va nous permettre de sauvegarder un panier en base de données lorsque 
    //l'utilisateur arrive sur la page checkout confirm ,très utiles pour l'équipe de marketing 
    //lorsqu'il souhaite contacter l'utilisateur pour proposer une offre par exemple. il faut lui donner 
    //en paramètre les données de la page $data et l'utilisateur connecté $user
    public function saveCart($data, $user)
    {
        // création d'un tableau qui va contenir qui va contenir un autre tableau qui
        // contientdra le detail des produis qu'on a dans le panier et 
        /*$data = [
            'products' => [],
            //les informtion liées au panier
            'data' => [],
            //les informations sur les données choisies ou saisies par l'utilisateur sur la page checkout
            'checkout' => [
                'address' => Objet Address,
                'informations' => String
            ]
        ]*/
        //création de l'objet métier Cart
        $cart = new Cart();
        $reference = $this->generateUuid();
        $address = $data['checkout']['address'];
        $annonce = $data['checkout']['name'];
        $annonce = $data['checkout']['city'];
        $annonce = $data['checkout']['id'];
        $informations = $data['checkout']['informations'];

        $cart->setReference($reference)
            ->setFullName($address->getFullName())
            ->setDeliveryAddresss($address)
            ->setCoachName($name)
            ->setCaochCity($city)
            ->setCoachId($id)
            ->setMoreInformations($informations)
            ->setQuantity($data['data']['quantity_cart'])
            ->setsubTotalHT(($data['data']['subTotalHT']))
            ->setTaxe($data['data']['Taxe'])
            ->setSubtotalTTC(round(($data['data']['subTotalTTC']), 2))
            ->setUser($user)
            ->setCreatedAt(new \DateTime());
        $this->manager->persist($cart);
// nous servira à stocker les élément du panier
        $cart_details_array = [];
// faire une boucle pour parcourir produit par produit du panier
        foreach ($data['products'] as $products) {
            $cartDetails = new CartDetails();
// ce tableau a 2 clés, la quanté et le pruduct qui garde l'objet métier Product

            $subtotal = $products['quantity'] * $products['product']->getPrice() / 100;

            $cartDetails->setCarts($cart)
                ->setProductName($products['product']->getName())
                ->setProductPrice($products['product']->getPrice() / 100)
                ->setQuantity($products['quantity'])
                ->setSubTotalHT($subtotal)
                ->setSubTotalTTC($subtotal * 1.2)
                ->setTaxe($subtotal * 0.2);
            $this->manager->persist($cartDetails);
            $cart_details_array[] = $cartDetails;
        }

        $this->manager->flush();

        return $reference;
    }
    //Création d’une méthode qui va nous permettre d’avoir une clé unique qui
    // va nous servir pour sauvegarder les références. L'objectif est d'avoir une clé unique
    public function generateUuid()
    {

        // Initialise le générateur de nombres aléatoires Mersenne Twister
        mt_srand((float)microtime() * 100000);

        //strtoupper : Renvoie une chaîne en majuscules
        //uniqid : Génère un identifiant unique
        $charid = strtoupper(md5(uniqid(rand(), true)));

        //Générer une chaîne d'un octet à partir d'un nombre
        $hyphen = chr(45);

        //substr : Retourne un segment de chaîne
        $uuid = ""
            . substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12);

        return $uuid;
    }
}
