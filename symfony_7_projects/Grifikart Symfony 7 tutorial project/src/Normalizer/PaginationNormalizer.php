<?php 
namespace App\Normalizer;

use App\Entity\Service;
use ArrayObject;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PaginationNormalizer implements NormalizerInterface
{

    public function __construct(
        #[Autowire(service:'serializer.normalizer.object')]
        private readonly NormalizerInterface $normalizer){}
    
    public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|ArrayObject|null
    {
        if(!($object instanceof PaginationInterface)){
           throw new \RuntimeException(); 
        }

        return [
            'items'=> array_map(fn (Service $service) => $this->normalizer->normalize($service, $format, $context), $object->getItems()),
            'total' => $object->getTotalItemCount(),
            'page'=> $object->getCurrentPageNumber(),
            'lastPage'=> ceil($object->getTotalItemCount() / $object->getItemNumberPerPage($format, $context))
        ];
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
       return $data instanceof PaginationInterface && $format === 'json';
    }
   
    public function getSupportedTypes(?string $format): array
    {
        return [
            PaginationInterface::class => true
        ];
    }
    

}