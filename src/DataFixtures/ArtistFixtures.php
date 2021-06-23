<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Artist;
use App\DataFixtures\CategoryFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ArtistFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for($nbArtists = 1; $nbArtists <= 30; $nbArtists++ ) {
            $category = $this->getReference('category_' . $faker->numberBetween(1, 5));
            $artist = new Artist;
            $artist->setCategory($category);
            $artist->setConcert($faker->numberBetween(1, 9));
            $artist->setName($faker->lastName);
            $artist->setDescription($faker->realText(5000));
            $artist->setIsLive($faker->numberBetween(0, 1));
            $manager->persist($artist);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // retourne la liste des dépendances du notre objet, qui doivent s'exécuter avant
        return [
            CategoryFixtures::class            
        ];
    }
}
