<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder=$encoder;
    }

    public function load(ObjectManager $manager)
    {
        $users=[
            ["Laura", "USER1", "user1@user1.fr", "mdp", "ROLE_USER"],
            ["Mathieu", "USER2", "user2@user2.fr", "mdp", "ROLE_USER"],
            ["Toto", "EDITOR1",  "editor1@editor1.fr", "mdp", "ROLE_EDITOR"],
            ["Simone","EDITOR2", "editor2@editor2.fr", "mdp", "ROLE_EDITOR"],
            ["Cachou","ADMIN1",  "admin1@admin1.fr", "mdp", "ROLE_ADMIN"],
            ["Cocotte", "ADMIN2",  "admin2@admin2.fr", "mdp", "ROLE_ADMIN"]   
        ];

        foreach( $users as $u ){
            $user = new Users();
            $password = $this->encoder->encodePassword($user, $u[3]);
            $user->setPrenom( $u[0])
            ->setNom($u[1])
            ->setEmail( $u[2])
            ->setPassword( $password )
            ->setRoles([ $u[4] ]);

            $manager->persist($user);
        }

        $manager->flush();
    }
}


// class AppFixtures extends Fixture
// {
//     private $encoder;
//     public function __construct(UserPasswordEncoderInterface $encoder)
//     {
//        $this->encoder=$encoder; 
//     }

//     public function load(ObjectManager $manager)
//     {
//        $user= new Users();
//        //PREMIER UTILISATEUR
//        $password= $this->encoder->encodePassword( $user, "mdp"); 
//        $user->setEmail("user@user.fr")
//             ->setNom("Poisson")
//             ->setPrenom("Nicolas")
//             ->setRoles(["ROLE_USER"])
//             ->setPassword($password)
//             ;
//         $manager->persist($user);
       
//         $editor= new Users();
//          // SECOND UTILISATEUR
//         $password= $this->encoder->encodePassword( $editor, "mdp"); 
//         $editor->setEmail("editor@editor.fr")
//             ->setNom("Poivron")
//             ->setPrenom("Nilo")
//             ->setRoles(["ROLE_USER","ROLE_EDITOR"])
//             ->setPassword($password)
//             ;
//         $manager->persist($editor);

//         $admin= new Users();
//         // TROISIEME UTILISATEUR
//        $password= $this->encoder->encodePassword( $admin, "mdp"); 
//        $admin->setEmail("admin@admin.fr")
//             ->setNom("Poichiche")
//             ->setPrenom("Nir")
//            ->setRoles(["ROLE_USER","ROLE_EDITOR", "ROLE_ADMIN"])
//            ->setPassword($password)
//            ;
//        $manager->persist($admin);


//        $manager->flush();
//     }
// }
