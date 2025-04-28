<?php

namespace App\Doctrine\Filters;

use App\Entity\Post;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class PublishedFilter extends SQLFilter

{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
         // Check if the entity implements the LocalAware interface
         if ($targetEntity->getReflectionClass()->getName() !== Post::class) {
            return "";
        }

        return sprintf(
            '%s.published_at IS NOT NULL AND %s.published_at <= %s',
            $targetTableAlias,
            $targetTableAlias,
            $this->getParameter('current_datetime')
         );
        
        // return $targetTableAlias.'.published_at IS NOT NULL' ; 

    }
}

   