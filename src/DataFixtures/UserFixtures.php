<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    

    private $encoder;

    //constructor with one argument: the service UserPasswordEncoderInterface 
    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    //method for loading test data
    public function load(ObjectManager $manager) {
        //initialization object Faker
        //configure the location, to have "French" data
        $faker = Faker\Factory::create('fr_FR');

        //creation users
        for ($i = 1; $i <= 5; $i++) {
            $user = new User();
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setUsername("kevin$i");
            $user->setBirthat(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
            $user->setEmail("test$i@test.fr");
            $user->setRoles(["ROLE_ADMIN"]);
            $password = $this->encoder->encodePassword($user, 'kevin');
            $user->setPassword($password);
            $user->setCreatedat(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
            $manager->persist($user); //persistence an user object
            $manager->flush(); //save the users in the database 
        }
    }

}
