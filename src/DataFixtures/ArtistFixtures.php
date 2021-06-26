<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Artist;
use App\DataFixtures\CategoryFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ArtistFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($nbArtists = 1; $nbArtists <= 15; $nbArtists++) {
            $category = $this->getReference(
                'category_' . $faker->numberBetween(1, 5)
            );
            $artist = new Artist();
            // 2 fois sur 10 il y aura des aristes sans categorie
            if (rand(1, 100) >= 20) {
                $artist->setCategory($category);
            } else {
                $artist->setCategory(null);
            }

            // 1 fois sur 10 il y aura des aristes sans concert programé
            // if(rand(1, 100) >= 10){
            if ($nbArtists <= 9) {
                $artist->setConcert($nbArtists);
            } else {
                $artist->setConcert(null);
            }
            $artist->setName($faker->lastName);
            $artist->setDescription($faker->realText(5000));
            $manager->persist($artist);
        }
        $manager->flush(); // on contacte la bdd une seule fois pour la populer
    }

    public function getDependencies()
    {
        // retourne la liste des dépendances du notre objet, qui doivent s'exécuter avant
        return [CategoryFixtures::class];
    }
}
