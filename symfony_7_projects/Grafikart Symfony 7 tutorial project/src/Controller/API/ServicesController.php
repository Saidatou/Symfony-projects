<?php
namespace App\Controller\API;

use App\DTO\PaginationDTO;
use App\Entity\Service;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ServicesController extends AbstractController
{
    // #[Route('/api/services')]
    #[Route('/api/services', methods: ['GET'])]
    public function index(
        ServiceRepository $repository, 
        // Request $request,
        #[MapQueryString]
        ?PaginationDTO $paginationDTO = null
        ){
        // $services =  $repository->paginateServices($request->query->getInt('page',1));
        $services =  $repository->paginateServices($paginationDTO?->page);
        return $this->json($services, 200, [], [
            'groups' => ['services.index']
        ]);
    }

    #[Route('/api/services/{id}', requirements:['id' => Requirement::DIGITS])]
    public function show(Service $service ){
        
        return $this->json($service, 200, [], [
            'groups' => ['services.index','services.show']
        ]);
    }

    // pour la déséréalisation (cette partie n'as pas fonctionné)
    #[Route('/api/services', methods:['POST'])]
    public function create(
        Request $request,
        #[MapRequestPayload(
            serializationContext: [
                'groups' => ['services.create']
            ]
        )] 
        Service $service,
        EntityManagerInterface $em
        )
        { 
            $service->setCreatedAt(new \DateTimeImmutable());
            $service->setUpdatedAt(new \DateTimeImmutable());
            $em->persist($service);
            $em->flush();  
            return $this->json($service, 200, [], [
                'groups' => ['services.index','services.show']
            ]);
        }
}