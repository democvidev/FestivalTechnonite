<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    
    private $encoder; // on stocke l'instance de l'objet

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR'); //population de jeu de fausses données en français

        for($nbUsers = 1; $nbUsers <=20; $nbUsers++){
            $user = new User;
            // le premier de la liste est admin
            if($nbUsers === 1){
                $user->setRoles(['ROLE_ADMIN']);
                $user->setEmail('admin@admin.com');
            } else if ($nbUsers === 2) {
                $user->setRoles(['ROLE_USER']);
                $user->setEmail('user@user.com');
            } else {
                $user->setEmail($faker->email);
            }
            // tous les users ont le même mot de passe
            $user->setPassword($this->encoder->encodePassword($user, '123456'));
            $user->setIsVerified($faker->numberBetween(0,1));
            $manager->persist($user);
        }
        $manager->flush();        
    }
}
