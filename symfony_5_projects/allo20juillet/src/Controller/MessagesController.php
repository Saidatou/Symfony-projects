<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Form\MessagesType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessagesController extends AbstractController
{
    #[Route('/messages', name: 'messages')]
    public function index(): Response
    {
        return $this->render('messages/index.html.twig', [
            'controller_name' => 'MessagesController',
        ]);
    }
    #[Route('/send', name: 'send')]
    public function send(Request $request): Response
    {
        $message = new Messages;
        //initilisation du message (le formulaire est créée)
        $form = $this->createForm(MessagesType::class, $message);
        // traitement du formulaire
        $form->handleRequest($request); //permet de récupérer le formulaire et de le traiter 
        //et voir si les données sont cohérents et si le formulaire est soumis et valid (l'ordre à son importance)
        if ($form->isSubmitted() && $form->isValid()) {

            $message->setSender($this->getUser()); // de l'utilisateur connecté
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();
            // message affiché après l'envoie de message paramètrage dans base avec la boucle for
            $this->addFlash("message", "Message envoyé avec succès.");
            //retour à la page d'accueil après l'envoi de votre message
            return $this->redirectToRoute("messages"); // 

        }

        return $this->render('messages/send.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    //cette fonction renvoie uniquement à la page twig de la boite de réception
    #[Route('/received', name: 'received')]
    public function received(): Response
    {
        return $this->render('messages/received.html.twig');
    }

    //cette fonction renvoie uniquement à la page twig de la boite de réception
    #[Route('/sent', name: 'sent')]
    public function sent(): Response
    {
        return $this->render('messages/sent.html.twig');
    }

    // cette comportera l'id du message que l'on souhaite lire
    #[Route('/read{id}', name: 'read')]
    // je recupère le message en question
    public function read(Messages $message): Response
    {
        //étant donnée que je lis le message
        $message->setIsRead(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();
        //je fais un compact de mon message que j'envoie à mon twig
        return $this->render('messages/read.html.twig', compact("message"));
    }


    // cette comportera l'id du message que l'on souhaite suprimer
    #[Route('/delete{id}', name: 'delete')]
    // je recupère le message en question
    public function delete(Messages $message): Response
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($message);
        $em->flush();

        return $this->redirectToRoute("received");
    }
}
