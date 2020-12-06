<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Beneficiary;
use App\Entity\User;
use App\Entity\Transaction;
use App\Entity\Account;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{


    private $encoder;

    //constructor with one argument: the service UserPasswordEncoderInterface 
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    //method for loading test data
    public function load(ObjectManager $manager)
    {
        //initialization object Faker
        //configure the location, to have "French" data
        $faker = Faker\Factory::create('fr_FR');

        //creation users
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setUsername("kevin$i");
            $user->setBirthat(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
            $user->setEmail("test$i@test.fr");
            $role = $i <= 5 ? ["ROLE_ADMIN"] : ["ROLE_USER"];
            $user->setRoles($role);
            $password = $this->encoder->encodePassword($user, 'kevin');
            $user->setPassword($password);
            $user->updatedTimestamps();
            $manager->persist($user); //persistence an user object

            for ($j = 0; $j < 5; $j++) {
                $beneficiary = new Beneficiary();
                $beneficiary->setFirstname($faker->firstname);
                $beneficiary->setLastname($faker->lastname);
                $beneficiary->setIban($faker->iban);
                $beneficiary->updatedTimestamps();
                $beneficiary->setUser($user);
                $manager->persist($beneficiary);
            }

            $tabaccounts = ["Compte Courant", "Livret A", "Plan Épargne Logement", "Livret Développement Durable (LDD)"];
            for ($j = 0; $j < count($tabaccounts); $j++) {
                $account = new Account();
                $account->setName($tabaccounts[$j]);

                if ($tabaccounts[$j] == "Compte Courant")
                    $account->setIban($faker->iban);

                $account->setBalance(5000);
                $account->updatedTimestamps();
                $account->setUser($user);
                $manager->persist($account);

                for ($j = 0; $j < 5; $j++) {
                    $transaction = new Transaction();
                    $transaction->setUser($user);
                    $transaction->setAccount($account);
                    $transaction->setLabel($faker->word);
                    $transaction->setCredit(5000);
                    $transaction->setAchievedat(new \DateTime());
                    $manager->persist($transaction);
                }
            }
            $manager->flush();
        }
    }
}
