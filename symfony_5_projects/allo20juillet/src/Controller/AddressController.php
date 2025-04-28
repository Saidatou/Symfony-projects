<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Services\CartServices;
use App\Repository\AddressRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/address')]
class AddressController extends AbstractController
{

    private $session;

    public function __construct(SessionInterface $session)
    {
        // initialistion de la session afin de pouvoir l'utiliser

        $this->session = $session;
    }
    #[Route('/', name: 'address_index', methods: ['GET'])]
    public function index(AddressRepository $addressRepository): Response
    {
        return $this->render('address/index.html.twig', [
            'addresses' => $addressRepository->findAll(),
        ]);
    }

    #[Route('/client', name: 'address_client', methods: ['GET'])]
    public function addressClient(AddressRepository $addressRepository): Response
    {
        return $this->render('address/addressClient.html.twig');
    }

    #[Route('/new', name: 'address_new', methods: ['GET', 'POST'])]

    public function new(Request $request, CartServices $cartServices): Response
    //nous souaitons que si l'utilsateur est sur le panier avant de rajouter son address de se 
    //rediriger sur la panier une fois l'ajout de l'adresse effectué
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $address->setUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($address);
            $entityManager->flush();
            // parès le flush on fait la condition suivante
            if ($cartServices->getFullCart()) {
                //est ce qu'il a des produits dans son panier, dans ce cas on le dirige sur la checkout
                return $this->redirectToRoute('checkout');
            }
            // sinon il sera redirigé comme au paravant sur la page des adresse 
            $this->addFlash('address_message', "Votre nouvelle adresse a bien été ajoutée à votre liste d'adresses");

            return $this->redirectToRoute('address_client', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('address/new.html.twig', [
            'address' => $address,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'address_show', methods: ['GET'])]
    public function show(Address $address): Response
    {
        return $this->render('address/show.html.twig', [
            'address' => $address,
        ]);
    }

    #[Route('/{id}/edit', name: 'address_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Address $address): Response
    {
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            //On regarde si la session est défini. s'il l'est ça veut dire qu'on vient de la page checkout confirm
            if ($this->session->get('checkout_data')) {
                //donc il qu'on lui retourne sur la page d'où il vient.  
                //Mais pour mettre à jour, les données modifiées
                $data = $this->session->get('ckechout_data');
                //on modifie l'adresse qu'on a à l'intérieur
                $data['address'] = $address;
                //on met à jour la session avec la clé checkout_data et la valeur $data définit
                //dans le checkout controller
                $this->session->set('checkout_data', $data);
                return $this->redirectToRoute("checkout_confirm");
            }


            $this->addFlash('address_message', "Votre adresse a bien été mise à jour");

            return $this->redirectToRoute('address_client', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('address/edit.html.twig', [
            'address' => $address,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'address_delete', methods: ['POST'])]
    public function delete(Request $request, Address $address): Response
    {
        if ($this->isCsrfTokenValid('delete' . $address->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($address);
            $entityManager->flush();
            $this->addFlash('address_message', "Votre adresse a bien été supprimée");
        }

        return $this->redirectToRoute('address_client', [], Response::HTTP_SEE_OTHER);
    }
}
