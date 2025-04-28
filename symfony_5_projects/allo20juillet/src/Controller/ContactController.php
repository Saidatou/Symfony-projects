<?php

namespace App\Controller;

use App\Entity\User;
use Twig\Node\SetNode;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Entity\EmailModel;
use App\Repository\ContactRepository;
use App\Services\EmailSender;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/contact')]
class ContactController extends AbstractController

{
    /**
     * @IsGranted("ROLE_ADMIN")
     */

    #[Route('/', name: 'contact_index', methods: ['GET'])]
    public function index(ContactRepository $contactRepository): Response
    {
        return $this->render('contact/index.html.twig', [
            'contacts' => $contactRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'contact_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EmailSender $emailsender): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();

            //Envoie d'email: lorsqu'une personne remplit le formulaire de contact, il faut envoyer un message
            //à l'administrateur afin qu'il soit informer. Pour ce faire nous allons créer ici un objet metier $user
            //puis de l'objet metier email
            $user = (new User())
                ->setEmail('babasaidatou@yahoo.fr')
                ->setFirstname('MC')
                ->setLastname('SPORTIFS');
            //en faisant un setTitle et d'obtenir le nom et le prénon de l'utisateur
            // le suject ou objet de l'envoi du formaulaire, après le contenu du message
            //donc l'email, son nom et prenom, l'objet et le contenu de l'email
            //après cela, nous allons injecter le service EmailSender dans la variable $email
            $email = (new EmailModel())
                ->setTitle("Hello " . $user->getFullName())
                ->setSubject("New contact from your website")
                ->setContent("<br>From : " . $contact->getEmail()
                    . "<br> Name : " . $contact->getName()
                    . "<br> Subject : " . $contact->getSubject()
                    . "<br><br>" . $contact->getContent());
            // le $suer ci-dssous est l'utilisateur à qui on envoie le mail
            $emailsender->sendEmailNotificationByMailJet($user, $email);

            //création d'un nouveau contact
            $contact = new Contact();
            //lorsque tous les informations saisies sont corrects l'envoie s'effectue sans problème
            $form = $this->createForm(ContactType::class, $contact);
            $this->addFlash('contact_success', 'votre message a bien été envoyé. Nous essaierons de repondre dans les plus brefs délais');

            // return $this->redirectToRoute('contact_index', [], Response::HTTP_SEE_OTHER);
        }
        // Mais lorsqu'il y a des erreurs, on a ce message flash qui s'affiche
        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('contact_error', 'Ce formulaire contient des erreurs. Merci de revérifier puis de réessayer');
        }

        return $this->renderForm('contact/new.html.twig', [
            'contact' => $contact,
            'form' => $form,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/{id}', name: 'contact_show', methods: ['GET'])]
    public function show(Contact $contact): Response
    {
        return $this->render('contact/show.html.twig', [
            'contact' => $contact,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */

    #[Route('/{id}/edit', name: 'contact_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Contact $contact): Response
    {
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('contact_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contact/edit.html.twig', [
            'contact' => $contact,
            'form' => $form,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/{id}', name: 'contact_delete', methods: ['POST'])]
    public function delete(Request $request, Contact $contact): Response
    {
        if ($this->isCsrfTokenValid('delete' . $contact->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($contact);
            $entityManager->flush();
        }

        return $this->redirectToRoute('contact_index', [], Response::HTTP_SEE_OTHER);
    }
}
