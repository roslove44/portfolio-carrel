<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;;

class CategoriesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = [".entreprises", ".evenements", ".organisme", ".ui"];
        for ($i = 0; $i < 4; $i++) {
            $category = new Categories();
            $category->setName($categories[$i]);

            $manager->persist($category);
        }

        $manager->flush();
    }
}
