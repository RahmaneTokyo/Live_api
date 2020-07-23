<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Profil;
use App\Entity\Commune;
use App\Entity\Departement;
use App\Repository\RegionRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    // Creating a construct function

    private  $repo;
    private  $encoder;

    public function __construct(RegionRepository $repo, UserPasswordEncoderInterface  $encoder ){     
        $this->repo=$repo;   
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager) // Load function can only have 1 parameter (ObjectManager)  
    {
        
        // Getting Region list from dataBase
        $regions=$this->repo->findAll();
        $faker = Factory::create('fr_FR');

        // Inserting Regions
        foreach($regions as $region){
            $departement=new Departement(); // Create Department for each region
            $departement->setCode($faker->postcode)
                        ->setNom($faker->city)
                        ->setRegion($region);
   
            $manager->persist( $departement);

            // For each Départment, insert 10 Communes
            for ($i=0; $i <10; $i++) {
                $commune=new Commune();
                $commune->setCode($faker->postcode)->setNom($faker->city)->setDepartement($departement);
                $manager->persist($commune);
            }
        }
        
        $manager->flush();

        $profils=["ADMIN","FORMATEUR","APPRENANT","CM"];

        foreach ($profils as $key => $libelle) { // Creating 3 users for each Profile    
            $profil= new Profil();         
            $profil->setLibelle($libelle);         
            $manager->persist($profil);         
            $manager->flush();for ($i=1; $i <=3; $i++) {           
                $user = new User();           
                $user->setProfil($profil)
                     ->setLogin(strtolower($libelle).$i)
                     ->setNomComplet($faker->name);
                //Generating Users           
                $password = $this->encoder->encodePassword($user, 'pass_1234');           
                $user->setPassword($password);           
                $manager->persist($user);         
            }
            
            $manager->flush();
        }


    }
}
