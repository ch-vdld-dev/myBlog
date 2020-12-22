<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create("en_EN");

        for($i=0;$i<10;$i++){
            $post = new Post();

            $post->setTitle($faker->sentence($nbWords = 3, $variableNbWords = true))
                 ->setContent($faker->sentence($nbWords = 20, $variableNbWords = true))
                 ->setAuthor($faker->name)
                 ->setCreatedAt($faker->dateTimeBetween("- 6 months"))
                 ->setSlug($faker->slug);
            
            $manager->persist($post);
        }

        $manager->flush();
    }
}
