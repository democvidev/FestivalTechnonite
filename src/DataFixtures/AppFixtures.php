<?php

namespace App\DataFixtures;

use App\Entity\Artist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        // create 20 products! Bam!
        for ($i = 0; $i < 20; $i++) {
            $artist = new Artist();
            $artist->setName('Nom Artiste '.$i);
            $manager->persist($artist);
        }


        $manager->flush();
    }
}
