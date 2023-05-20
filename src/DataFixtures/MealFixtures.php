<?php

namespace App\DataFixtures;

use App\Entity\Meal;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class MealFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr');

        for($i = 0; $i < 50; $i ++) {
            $m = (new Meal())
                ->setName(ucfirst(trim($faker->unique()->sentence(2), '.')))
                ->setDescription($faker->text())
                ->setPrice($faker->randomFloat(2, 1, 15));
            $manager->persist($m);
        }
        $manager->flush();
    }
}
