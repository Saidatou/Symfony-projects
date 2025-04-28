<?php

namespace App\Controller;

use App\Form\CheckoutType;
use App\Services\CartServices;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CheckoutController extends AbstractController
{
    //j'aurai besoin de la session et du CartService dans méthode se ce controller
    // on sauvergarder les données issus du formulaire depaiement dans une session afin de ne les
    //losrqu'on quittera pour une autre action déclencher depuis la page comme le fait de modifier 
    //son adresse 
    private $cartServices;
    private $session;

    public function __construct(CartServices $cartServices, SessionInterface $session)
    {
        // initialistion du cartServices et de la session
        $this->cartServices = $cartServices;
        $this->session = $session;
    }
    #[Route('/checkout', name: 'checkout')]
    //injection du CartServices avec son use nous permet d'accéder au détail du panier
    public function index(Request $request): Response
    {
        //recupération de l'utilisateur connecté
        $user = $this->getUser();
        // On recupère le panier
        $cart = $this->cartServices->getFullCart();

        // regarder si le panier est défini et est ce la clé produt est défini si oui oncontinue
        //le processus de paiement. Dans cas contraire le rediriger sur la page home_product
        if (!isset($cart['products'])) {
            return $this->redirectToRoute("home_product");
        }
        //est ce que l'utilisateur a déjà défini des addresse

        if (!$user->getAddresses()->getValues()) {
            //ajout de ce message afin de faire comprendre le processus à l'utilisateur
            $this->addFlash('checkout_message', 'Merci de rajouter une addresse avant de poursuivre !');
            return $this->redirectToRoute("address_new");
        }
        // si l'utilisateur revient une prochaine fois sur le site, il ne faut plus qu'il retrouve
        //la page confirme comme il était lorsqu'il se connectera
        if ($this->session->get('checkout_data')) {
            //si les données sont déjà enregistrées on l'nvoie sur la page de confirmation
            return $this->redirectToRoute('checkout_confirm');
        }
        //nous allons initialiser le formulaire et définition des option la clé de l'utilisateur connecté
        $form = $this->createForm(CheckoutType::class, null, ['user' => $user]);



        return $this->render('checkout/index.html.twig', [
            'cart' => $cart,
            'checkout' => $form->createView()
        ]);
    }



    #[Route('/checkout/confirm', name: 'checkout_confirm')]
    public function confirm(Request $request,): Response
    {
        // 
        //recuperer l'utilisateur connecté 
        $user = $this->getUser();
        //recuper le panier
        $cart = $this->cartServices->getFullCart();

        // regarder si le panier est défini et est ce la clé produt est défini si oui oncontinue
        //le processus de paiement. Dans cas contraire le rediriger sur la page home
        if (!isset($cart['products'])) {
            return $this->redirectToRoute("home");
        }
        //est ce que l'utilisateur a déjà défini des addresse


        if (!$user->getAddresses()->getValues()) {
            $this->addFlash('checkout_message', 'Merci de rajouter une addresse dans votre espace personnel avant de poursuivre !');
            return $this->redirectToRoute("address_new");
        }

        $form = $this->createForm(CheckoutType::class, null, ['user' => $user]);
        //traitement du formulaire
        $form->handleRequest($request);
        // est ce que le formlaire est soumis et est ce que les données sont valides
        // ou est ce qu'il y a quelque chose dans la session qui a pour clé ('checkout_data')

        if ($form->isSubmitted() && $form->isValid() || $this->session->get('checkout_data')) {
            // Lorsqu'on vient du formualire confirm checkout, d'abord
            // on sauvegarde des données du formulaire dans une session avec la clé checkout_data
            if ($this->session->get('checkout_data')) {
                //on sauvegarde des données du formulaire dans une session avec la clé checkout_data
                $data = $this->session->get('checkout_data');
            } else {
                //création de la variable $data afin de récupérer les données qui sont envoyés
                //dans le formulaire
                $data = $form->getData();
                //on sauvegarde des données du formulaire dans une session avec la clé checkout_data
                //avec en parametre les donées issues du formulaire
                $this->session->set('checkout_data', $data);
            }
            //nous allons récupérer l'adresse du client, du pseudo du coach et des informations complémentaires

            $address = $data['address'];
            $name = $data['name'];
            $city = $data['city'];
            $id = $data['id'];
            $information = $data["informations"];


            // Save Cart
            $cart['checkout'] = $data;
            // $reference = $orderServices->saveCart($cart, $user);
            //OrderServices $orderServices

            return $this->render('checkout/confirm.html.twig', [
                'cart' => $cart,
                'address' => $address,
                'name' => $name,
                'city' => $city,
                'id' => $id,
                'informations' => $information,
                //'reference' => $reference,
                'checkout' => $form->createView()
            ]);
        }


        return $this->redirectToRoute('checkout');
    }

    /**
     * @Route("/checkout/edit", name="checkout_edit")
     */
    public function checkoutEdit(): Response
    {
        //on supprimer la session et l'envoyer directement sur la page checkout avec le tableau vide
        $this->session->set('checkout_data', []);
        return $this->redirectToRoute("checkout");
    }
}
