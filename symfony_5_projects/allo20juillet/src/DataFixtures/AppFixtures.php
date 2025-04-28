<?php

namespace App\DataFixtures;


use App\Entity\User;
use App\Entity\Categories;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)

    {
        $users = [
            ["Lauravi", "Lauragan", "USER1", "user1@user1.fr", "mdpmdp", "ROLE_USER"],
            ["Mathieuvi", "Mathieugan", "USER2",   "user2@user2.fr", "mdpmdp", "ROLE_USER"],
            ["Totovi", "Totogan", "EDITOR1",  "editor1@editor1.fr", "mdpmdp", "ROLE_EDITOR"],
            ["Simonevi", "Simonegan", "EDITOR2", "editor2@editor2.fr", "mdpmdp", "ROLE_EDITOR"],
            ["Cachouvi", "Cachougan", "ADMIN1",  "admin1@admin1.fr", "mdpmdp", "ROLE_ADMIN"],
            ["Cocottevi", "Cocottegan", "ADMIN2",  "admin2@admin2.fr", "mdpmdp", "ROLE_ADMIN"]
        ];


        // $categories = [

        //     [
        //         "name" => "Homme",
        //         "description" => "Pour vous les hommes",
        //         "image" => " ",
        //         "products" => " "

        //     ],
        //     [
        //         "name" => "Femme",
        //         "description" => "Pour vous les Femmes",
        //         "image" => " ",
        //         "products" => " "

        //     ],

        // ];



        foreach ($users as $u) {
            $user = new User();
            $password = $this->encoder->encodePassword($user, $u[4]);
            $user->setFirstname($u[0])
                ->setLastname($u[1])
                ->setEmail($u[3])
                ->setPassword($password)
                ->setRoles([$u[5]]);


            $manager->persist($user);
        }



        // foreach ($categories as $c) {
        //     $category = new Categories();
        //     $category->setName($c["name"])
        //         ->setDescription($c["description"])
        //         ->setImage($c["image"])
        //         ->setProducts($c["products"]);

        //     $manager->persist($category);
        // }




        $manager->flush();
    }
}
