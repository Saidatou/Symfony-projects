<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Ingredient;
use App\Entity\Quantity;
use App\Entity\Service;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use FakerRestaurant\Provider\fr_FR\Restaurant;
use Symfony\Component\String\Slugger\SluggerInterface;

class ServiceFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private readonly SluggerInterface $slugger)
    {
        
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new Restaurant($faker));

        $ingredients = array_map(fn(string $name) => (new Ingredient())
            ->setName($name)
            ->setSlug(strtolower($this->slugger->slug($name))), [
            'Farine',
            'Sucre',
            'Oeufs',
            'Beurre',
            'Lait',
            'Leuvure chimique',
            'Sel',
            'Chocolat noir',
            'Pépites de chocolat',
            'Fruits secs (amandes, noix, etc.)',
            'Vanille',
            'Cannelle',
            'Fraise',
            'Banane',
            'Pomme',
            'Carotte',
            'Oignon',
            'Ail',
            'Citron',
            'Gingembre',
            'Échalote',
            'Herbes fraîches (ciboulette,persil,etc.)'

        ]);

        $units = [
            'g',
            'kg',
            'L',
            'mL',
            'cL',
            'dL',
            'c. à soupe',
            'c. à café',
            'pincée',
            'verre'
        ];

        foreach($ingredients as $ingredient){
            $manager->persist($ingredient);
        }
        // foreach($units as $unit){
        //     $manager->persist($unit);
        // }
        
        
        
        $categories = ['Plat chaud', 'Dessert', 'Entrée', 'Goûter'];
        
        foreach($categories as $c){

            $category = (new Category())

                    ->setName($c)
                    ->setSlug($this->slugger->slug($c))
                    ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                    ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()));
                    $manager->persist($category);
                    $this->addReference($c, $category);
        }
        
        for($i=1; $i<= 10; $i++){
            $title = $faker->foodName();
            $service = (new Service())
                    ->setTitle($faker->foodName())
                    ->setSlug($this->slugger->slug($title))
                    ->setIcon($faker->beverageName())
                    ->setShortDesc($faker->vegetableName())
                    ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                    ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                    ->setContent($faker->paragraph(10, true))
                    // ->setCategory($this->getReference($faker->randomElement($categories)))
                    // ->setUser($this->getReference('USER'. $faker->randomElement(1, 10)))
                    ->setDuration($faker->numberBetween(2, 60));
                    // ->setCategory($faker->randomElement($categories));
                    foreach($faker->randomElements($ingredients, $faker->numberBetween(2, 5)) as $ingredient){
                        $service->addQuantity((new Quantity())
                            ->setQuantity($faker->numberBetween(1, 250))
                            ->setUnit($faker->randomElement($units))
                            ->setIngredient($ingredient)
                    );
                    }
             $manager->persist($service)      ; 
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
