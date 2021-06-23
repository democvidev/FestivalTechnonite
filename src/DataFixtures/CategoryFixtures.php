<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $categories = [
            1 => [
                'name' => 'Mélodique',
                'color' => 'primary',
            ],
            2 => [
                'name' => 'Industrielle',
                'color' => 'secondary',
            ],
            3 => [
                'name' => 'Groovy',
                'color' => 'success',
            ],
            4 => [
                'name' => 'Deep',
                'color' => 'info',
            ],
            5 => [
                'name' => 'Détroit',
                'color' => 'warning',
            ],
        ];

        foreach ($categories as $key => $value) {
            $category = new Category;
            $category->setName($value['name']);
            $category->setColor($value['color']);
            $manager->persist($category);

            // Enregistre la catégorie dans une référence
            $this->addReference('category_' . $key, $category);
        }
        $manager->flush();
    }
}
