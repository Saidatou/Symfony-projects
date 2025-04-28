<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Tag;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $passwordHasher, private SluggerInterface $slugger){}
    public function load(ObjectManager $manager): void
    {
       $faker = Factory::create();
            
        $user = new User;
        $user
        ->setName('Mercury Series')
        ->setEmail('mercuryseries@gmail.com')
        ->setPassword($this->passwordHasher->hashPassword($user, 'secret123'));
        $manager->persist($user);

        $admin = new User();
        $admin->setName('Mister Admin');
        $admin->setEmail('admin@admin.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'secret123'));
        $manager->persist($admin);

        // $manager->flush();
        //create tags

        $tags = [];
        for ($j = 1; $j <= 10; ++$j) {
            $tag = new Tag();
            $tag->setName($faker->unique()->word());
            $manager->persist($tag);
            $tags[] = $tag;
        }
       
        // create 10 posts! Bam!
        for ($i = 1; $i <= 80; $i++) {
            $post = (new Post())
            ->setTitle($faker->unique()->sentence())
            // ->setTitle($title=$faker->sentence())
            // ->setSlug($this->slugger->slug(mb_strtolower($title)))
            ->setBody($faker->paragraph(10))
            ->setPublishedAt(
                $faker->boolean(75)
                ? \DateTimeImmutable::createFromMutable(
                    $faker->dateTimeBetween('-50 days', '+10 days')
                )
                : null
            );
            $post->setAuthor($faker->boolean(50) ? $user: $admin);
            

            foreach ($faker->randomElements($tags,3) as $tag){
                $post->addTag($tag);
            }
            $manager->persist($post);

           

            for ($k = 1; $k <= $faker->numberBetween(1, 5); ++$k) {
                $comment = new Comment();
                $comment->setName($faker->name());
                $comment->setEmail($faker->email());
                $comment->setBody($faker->paragraph());
                $comment->setIsActive($faker->boolean(90));
                $comment->setPost($post);
                $manager->persist($comment);
            }
        }

        $manager->flush();
    }
}
