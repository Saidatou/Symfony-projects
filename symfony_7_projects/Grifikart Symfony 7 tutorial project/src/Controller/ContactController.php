<?php

namespace App\Controller;

use App\DTO\ContactDTO;
use App\Event\ContactRequestEvent;
use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer, EventDispatcherInterface $dispatcher): Response
    {
        $data = new ContactDTO();

        // TODO : Supprimer
        // $data->name = 'John Doe';
        // $data->email= 'sabt@gmail.com';
        // $data->message = 'hello this is a first message';

        $form = $this->createForm(ContactType::class, $data);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            try{
                $dispatcher->dispatch(new ContactRequestEvent($data));
                $this->addFlash('success','Votre email a bien été envoyé');
                
            }catch (\Exception $e) {
                $this->addFlash('danger', "Impossible d'envoyer votre email");
            }
            //envoie de l'email
            /*try {
                //code...
            $mail = (new TemplatedEmail())
                // ->to('hokokoo')
                ->to($data->service)
                ->from($data->email)
                ->subject('Demande de contact')
                ->htmlTemplate('emails/contact.html.twig')
                ->context(['data' => $data]);
                $mailer->send($mail);
                $this->addFlash('success','Votre email a bien été envoyé');
                return $this->redirectToRoute('contact');
            } catch (\Exception $e) {
                $this->addFlash('danger', "Impossible d'envoyer votre email");
            }
            */
        }

        return $this->render('contact/contact.html.twig', [
            'form' =>$form
        ]);
    }
}
