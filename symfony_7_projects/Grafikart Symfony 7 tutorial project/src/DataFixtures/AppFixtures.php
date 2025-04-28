<?php

namespace App\DataFixtures;

// use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
// use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    // public function __construct(
    //     private readonly UserPasswordHasherInterface $hasher
    // ){}

    public function load(ObjectManager $manager): void
    {
    //     $user = (new User());
    //     $user->setRoles(['ROLE_ADMIN'])
    //         ->setEmail('admin@doe.com')
    //         ->setUsername('admin')
    //         ->setIsVerified(true)
    //         ->setPassword($this->hasher->hashPassword($user, 'admin'))
    //         ->setApiToken('admin_token');
    //     $manager->persist($user);

    //     for($i = 1; $i<= 10; $i++){
    //         $user->setRoles([])
    //             ->setEmail("user{$i}@doe.com")
    //             ->setUsername("user{$i}")
    //             ->setIsVerified(true)
    //             ->setPassword($this->hasher->hashPassword($user, '0000'))
    //             ->setApiToken("user{$i}");
    //         $this->addReference('USER'.$i, $user);
    //         $manager->persist($user);
    //     }
        
    //     // $product = new Product();
    //     // $manager->persist($product);

        $manager->flush();
    }
}
