<?php

namespace App\Controller\Admin;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\CategoryRepository;
use App\Repository\ServiceRepository;
use App\Security\Voter\ServiceVoter;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\UX\Turbo\TurboBundle;

#[Route('/admin/services', name:'admin.service.')]
// #[IsGranted('ROLE_ADMIN')]
final class ServiceController extends AbstractController
{
    public function __construct(private ServiceRepository $repository){}

    #[Route('/', name: 'index')]
    #[IsGranted(ServiceVoter::LIST)]
    public function index(ServiceRepository $repository, Request $request, Security $security): Response
    {
        // $this->denyAccessUnlessGranted('ROLE_USER');
        // $services = $repository->findAll();
        $page = $request->query->getInt('page',1);
        $userId = $security->getUser()->getId();
        $canListAll  = $security->isGranted(ServiceVoter::LIST_ALL);
        // $limit=2;
        $services = $this->repository->paginateServices($page, $canListAll ? null : $userId);
        // $maxPage = ceil($services->getTotalItemCount()/ $limit);

        
        return $this->render('admin/service/index.html.twig', [
            'services' => $services,
            // 'maxPage'=> $maxPage,
            // 'page'=>$page
        ]);
        //   return new response('Services'); 
        }

    // #[Route('/{slug}-{id}', name: 'show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    // public function show(Request $request, string $slug, int $id, ServiceRepository $repository): Response
    // {
    //     // return $this->json([
    //     //     'slug' => $slug
    //     // ]);

    //     // return new JsonResponse([
    //     //     'slug' => $slug
    //     // ]);
    //     //   return new Response('Service: '. $slug);

    //         $service = $repository->find($id);

    //     if($service->getSlug() != $slug){
    //         return $this->redirectToRoute('service.show', ['slug' =>$service->getSlug, 'id'=>$service->getId]);
    //     }
    //         // dd($service);

    //         return $this->render('service/show.html.twig',[
    //             'service' =>$service
    //         ]);
    // }

        #[Route('/create', name:'create')]
        #[IsGranted(ServiceVoter::CREATE)]
        public function create(Request $request, EntityManagerInterface $em){
            $service = new Service();    
            $form = $this->createForm(ServiceType::class, $service);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                // $service->setCreatedAt(new \DateTimeImmutable());
                // $service->setUpdatedAt(new \DateTimeImmutable());
                $em->persist($service);
                $em->flush();
                $this->addFlash('success', 'Le bien été créé');
                return $this->redirectToRoute('admin.service.index');
            }
            return $this->render('admin/service/create.html.twig',[
                'form' =>$form
            ]);
        }

        #[Route('/{id}', name:'edit', methods:['GET', 'POST'], requirements:['id' => Requirement::DIGITS])]
        #[IsGranted(ServiceVoter::EDIT, subject:'service')]
        public function edit(Service $service, Request $request, EntityManagerInterface $em){
            $form = $this->createForm(ServiceType::class, $service);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                // $service->setUpdatedAt(new \DateTimeImmutable());
                $em->flush();
                $this->addFlash('success', 'le service a bien été modifiée');
                
                return $this->redirectToRoute('admin.service.index');
            }

            return $this->render('admin/service/edit.html.twig',[
                'service'=> $service,
                'form' =>$form
            ]);
        }

        #[Route('/{id}', name:'delete', methods:['DELETE'], requirements:['id' => Requirement::DIGITS])]
        #[IsGranted(ServiceVoter::EDIT, subject:'service')]
        public function remove(Request $request,Service $service, EntityManagerInterface $em){
                
                $serviceId = $service->getId();
                $message = 'le service a bien été supprimer';
                $em->remove($service);       
                $em->flush();
                if($request->getPreferredFormat() == TurboBundle::STREAM_FORMAT){
                    $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
                    return $this->render('admin/service/delete.html.twig', ['serviceId' => $serviceId, 'message' =>$message]);
                }
                $this->addFlash('success', $message);
                
                return $this->redirectToRoute('admin.service.index');
        }
}
