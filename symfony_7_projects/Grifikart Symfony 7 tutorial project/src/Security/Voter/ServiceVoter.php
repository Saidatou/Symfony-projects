<?php

namespace App\Security\Voter;

use App\Entity\Service;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class ServiceVoter extends Voter
{
    public const EDIT = 'SERVICE_EDIT';
    public const VIEW = 'SERVICE_VIEW';
    public const CREATE= 'SERVICE_CREATE';
    public const LIST= 'SERVICE_LIST';
    public const LIST_ALL= 'SERVICE_ALL';

    // public function __construct(private readonly Security $security){}

    protected function supports(string $attribute, mixed $subject): bool
    {
        
        return in_array($attribute, [self::EDIT, self::LIST, self::LIST_ALL]) || 
        (
            in_array($attribute, [self::CREATE, self::VIEW])
            && $subject instanceof \App\Entity\Service
        );
    }


    /** 
     * @param Service $subject
    */

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        if(!$subject instanceof Service){
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
               
                return $subject->getUser()->getId() == $user->getId();
                break;

            // case self::LIST_ALL:
                // return $this->security->isGranted('ROLE_ADMIN');
                
            case self::VIEW:
            case self::LIST:
            case self::CREATE:
                // logic to determine if the user can VIEW
                return true;
                break;
        }

        return false;
    }
}
