<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\TagRepository;
use Symfony\UX\Turbo\TurboBundle;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use Symfony\Component\Mercure\Update;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\EventListener\AbstractSessionListener;

class PostsController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em )
    {
    }
    #[Route('/', name: 'app_home', methods:['GET'])]
    #[Route(
        '/tags/{tagSlug}',
        name: 'app_posts_by_tag',
        requirements: [
            'tagSlug' => Requirement::ASCII_SLUG,
        ],
        methods: ['GET']
    )]
    public function index(Request $request, PaginatorInterface $paginator, ?string $tagSlug, TagRepository $tagRepository, PostRepository $postRepository): Response
    {
        $tag = null;
        if ($tagSlug) {
            $tag = $tagRepository->findOneBySlug($tagSlug);
        }

       
        $query = $postRepository->createAllPublishedOrderedByNewestQuery($tag);

        $page = $request->query->getInt('page', 1);

        $pagination = $paginator->paginate(
            $query,
            $page,
            Post::NUM_ITEMS_PER_PAGE
        );

        $response = $this->render('posts/index.html.twig', [
            'pagination' => $pagination,
            'tagName' => $tag?->getName(),
        ])->setSharedMaxAge(30);

        // We do this otherwise Symfony will override the Cache-Control header
        // if the session exists
        $response->headers->set(
            AbstractSessionListener::NO_AUTO_CACHE_CONTROL_HEADER, 'true'
        );

        return $response;
    }

   

    #[Route(
        '/posts/{slug}',
        name: 'app_posts_show',
        requirements: [
            'slug' => Requirement::ASCII_SLUG,
        ],
        methods: ['GET', 'POST'],
        priority:5
    )]
    public function show(Request $request, Post $post, HubInterface $hub, $slug, PostRepository $postRepository, CommentRepository $commentRepo): Response
    {
       
        $similarPosts = $postRepository->findSimilar($post);
        $comments = $post->getActiveComments();
        // $comments = $post->getComments()->filter(function($comment){
        //     return $comment->isIsActive();
        // });
     
        // $comments=$this->em->getRepository(Comment::class)->findBy(['isActive'=>true]);
       
        // $comments= $post->getComments();
        // $post = $this->em->getRepository(Post::class)->findOneBySlug($slug);
        
        $commentForm = $this->createForm(CommentFormType::class);
        $emptyCommentForm = clone $commentForm;

        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment = $commentForm->getData();
            $comment->setPost($post);

            $commentRepo->save($comment, flush: true);

            if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()){
                $hub->publish(new Update(
                    "post_{$post->getId()}_comments",
                    $this->renderView('comments/success.stream.html.twig', [
                        'comment' => $comment,
                        'commentsCount' => $comments->count() + 1,
                        'commentForm' =>  $emptyCommentForm,
            ])      
                    ));
            }
            // if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()){
            //     $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            //     return $this->render('comments/success.stream.html.twig', [
            //         'comment' => $comment,
            //         'commentsCount' => $comments->count() + 1,
            //         'commentForm' =>  $emptyCommentForm,
            //     ] );
            // }

            $this->addFlash('success', '🚀 Comment successfully added!');

            return $this->redirectToRoute('app_posts_show', ['slug' => $post->getSlug()]);
        }

        return $this->render('posts/show.html.twig', compact('post', 'comments',  'commentForm', 'similarPosts'));
    }


    #[Route('/posts/featured-content', name: 'app_posts_featured_content', methods: ['GET'], priority: 10)]
    public function featuredContent(PostRepository $postRepository, int $maxResults = 5): Response
    {
        $totalPosts = $postRepository->count([]);
        $latestPosts = $postRepository->findBy([], ['publishedAt' => 'DESC'], $maxResults);
        $mostCommentedPosts = $postRepository->findMostCommented($maxResults);

        return $this->render(
            'posts/_featured_content.html.twig',
            compact('totalPosts', 'latestPosts', 'mostCommentedPosts')
        )->setSharedMaxAge(50);
    }
}
