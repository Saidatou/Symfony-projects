<?php

namespace App\DataFixtures;

use App\Entity\Film;
use App\Entity\User;
use App\Entity\Artiste;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername("admin")
            ->setEmail("admin@admin.fr")
            ->setRoles(["ROLE_ADMIN"])
            ->setPassword( $this->encoder->encodePassword( $user, "mdp"));
        $manager->persist($user);
            
        $artistes=[
            ["Brad", "Pitt"],
            ["George", "Clonney"],
            ["Matt", "Damon"],
            ["Julia", "Roberts"],
            ["Ben", "Affleck"],
            ["Albert", "Finney"],
            ["Steven", "Soderbergh"],
            ["Casey", "Affleck"],
            ["Bradley", "Cooper"],
            ["Steven", "Spielberg"],
            ["Albert", "Dupontel"]
        ];
        
        foreach( $artistes as $a ){
            $artiste= new Artiste();
            $artiste->setPrenom($a[0])->setNom($a[1])->setPhoto($a[0].$a[1].".jpg");
            $this->addReference($a[0].$a[1], $artiste);
            $manager->persist($artiste);
        }

        $films=[
            ["titre"=>"Will Hunting", 
             "date"=>"1998",
             "description"=>"Un jeune homme de ménage se découvre génie des maths", 
             "affiche"=>"willHunting.jpg", 
             "realisateur"=>"BenAffleck",
             "casting"=>[ "MattDamon", "BenAffleck"],
            ],
            
            ["titre"=>"Ocean's Eleven",
            "date"=> "2001",
            "description"=>"Braquage d'un casino",
            "affiche"=> "oceans11.jpg",   
            "realisateur"=>"StevenSoderbergh", 
            "casting"=>[
                "BradPitt", "GeorgeClonney", "MattDamon", "JuliaRoberts"
            ]],
            ["titre"=>"Ocean's Twelve", 
            "date"=> "2004",
            "description"=>"Braquage d'un casino 2", 
            "affiche"=>"oceans12.jpg", 
            "realisateur"=>"StevenSoderbergh",
            "casting"=>["BradPitt", "GeorgeClonney", "MattDamon", "JuliaRoberts" ]
            ],
            ["titre"=>"Ocean's Thirteen", 
            "date"=> "2007",
            "description"=>"Braquage d'un casino 3", 
            "affiche"=>"oceans13.jpg", 
            "realisateur"=>"StevenSoderbergh",
            "casting"=>["BradPitt", "GeorgeClonney", "MattDamon" ]
             ],
            ["titre"=>"Erin Brokovitch", 
            "date"=> "1994",
            "description"=>"Une femme entre en lutte pour sauver des gens de la délocalisation", 
            "affiche"=>"erin.jpg", 
            "realisateur"=>"StevenSoderbergh" ,
            "casting"=>["AlbertFinney", "JuliaRoberts" ]
        ]
        ];

        foreach($films as $f){
            $film=(new Film())
                ->setTitre($f["titre"])
                ->setDateDeSortie(new \DateTime($f["date"]."-01-01 00:00"))
                ->setDescription($f["description"])
                ->setAffiche($f["affiche"])
                ->setRealisateur($this->getReference($f["realisateur"]));
                //var_dump($film->getDateDeSortie());

            foreach($f["casting"] as $acteur){
                $film->addActeur($this->getReference($acteur));
            }
            $manager->persist($film);
            
        }
 

     
     
        $manager->flush();
    }
}
