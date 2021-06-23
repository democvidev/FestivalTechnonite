<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR'); //population de jeu de fausses données en français

        for($nbUsers = 1; $nbUsers <=20; $nbUsers++){
            $user = new User;
            $user->setEmail($faker->email);
            // le premier de la liste est admin
            if($nbUsers === 1){
                $user->setRoles(['ROLE_ADMIN']);
            } else {
                $user->setRoles(['ROLE_USER']);
            }
            // tous les users ont le même mot de passe
            $user->setPassword($this->encoder->encodePassword($user, '123456'));
            $user->setIsVerified($faker->numberBetween(0,1));
            $manager->persist($user);
        }
        $manager->flush();        
    }
}
