<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\SharePostFormType;
use App\Repository\PostRepository;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SharedPostsController extends AbstractController
{

    public function __construct(private EntityManagerInterface $em, private PostRepository $postRepository)
    {
    }
    
    #[Route(
        '/posts/{slug}/share',
         name:'app_posts_share',
         requirements: [
          
            'slug'=> Requirement::ASCII_SLUG,
         ],
         methods:['GET','POST'])]
    public function create( Request $request,Post $post, $slug, MailerInterface $mailer): Response
    {
          
            // $this->checkPostSlug($post, $slug);
    
            // $post= $this->postRepository->findOneByPublishDateAndSlug($date, $slug);
            // if (!$post) {
            //     throw $this->createNotFoundException('Post not found');
            // }
    
            $post = $this->em->getRepository(Post::class)->findOneBySlug($slug);
     
            $form = $this->createForm(SharePostFormType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                $data = $form->getData();
    
                // $postURL= $this->generateUrl('app_posts_show',
                //  $post->getPathParams(),
                // UrlGeneratorInterface::ABSOLUTE_URL);
    
                $subject = sprintf('%s recommends you to read "%s"', $data['sender_name'], $post->getTitle());
    
                
    
            $email = (new TemplatedEmail)
            ->from(new Address(
                $this->getParameter('app.contact_email'),
                $this->getParameter('app.name')))
            ->to($data['receiver_email'])
            ->subject($subject)
            ->htmlTemplate('emails/shared_posts/create.html.twig')
             // pass variables (name => value) to the template
            ->context([
            'post' => $post,
            'sender_name'=>$data['sender_name'],
            'sender_comments' => $data['receiver_email'],
        ])
            ;
          
    
            $mailer->send($email);
    
            $this->addFlash('success', '🚀 Post successfully shared with your friend!');
            
    
            return $this->redirectToRoute('app_home');
            }
    
           
            return $this->render('shared_posts/create.html.twig', compact('form', 'post'));
        
    
    }
}
