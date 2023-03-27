<?php

namespace App\DataFixtures;

use App\Entity\CategorieSondage;
use App\Entity\Formation;
use App\Entity\Question;
use App\Entity\Reponse;
use App\Entity\Sondage;
use App\Entity\TypeQuestion;
use App\Entity\User;
use App\Repository\ReponseRepository;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        $formations=[];
        // Mise en place des différentes formations
        $formation1 = new Formation();
        $formation1->setNomFormation('Employé');
        $formations[]=$formation1;

        $formation2 = new Formation();
        $formation2->setNomFormation('Étudiant');
        $formations[]=$formation2;

        $formation3 = new Formation();
        $formation3->setNomFormation('Retraités');
        $formations[]=$formation3;

        $size= count($formations);
        for ($i=0; $i<$size; $i++){
            $manager->persist($formations[$i]);
        }


        // Mise en place d'un sondeur
        $sondeur = new User();
        $role =  ['ROLE_SONDEUR'];
        $sondeur->setEmail('james.bond@skyfall.com')
            ->setPassword($this->passwordHasher->hashPassword(
                $sondeur,'password'))
            ->setRoles($role)
            ->setNom('Bond')
            ->setPrenom('James')
            ->setDateNaissance(new DateTimeImmutable())
            ->setVille('Orléans');
        $manager->persist($sondeur);

        // Mise en place d'un sondeur
        $admin = new User();
        $role =  ['ROLE_ADMIN'];
        $admin->setEmail('admin@creacosm.com')
            ->setPassword($this->passwordHasher->hashPassword(
                $admin,'password'))
            ->setRoles($role)
            ->setNom('ADMIN')
            ->setPrenom('ADMIN')
            ->setDateNaissance(new DateTimeImmutable())
            ->setVille('Orléans');
        $manager->persist($admin);

        // Mise en place d'un sondé
        $sonde = new User();
        $role =  ['ROLE_SONDE'];
        $sonde->setEmail('bob.leponge@carre.com')
            ->setPassword($this->passwordHasher->hashPassword(
                $sonde,'password'))
            ->setRoles($role)
            ->setNom('Leponge')
            ->setGenre('Homme')
            ->setPrenom('Bob')
            ->setDateNaissance(new DateTimeImmutable())
            ->setVille('Bikini Bottom')
            ->setFormation($formation2);
        $manager->persist($sonde);




        // Mise en place des types de question
        $allTypes=[];
        $type1= new TypeQuestion();
        $type1
            ->setIntituleType("Liste déroulante")
            ->setLabel('select');
        $allTypes[]=$type1;

        $type2= new TypeQuestion();
        $type2
            ->setIntituleType("Choix multiple")
            ->setIsMultiple(true);

        $type2->setLabel('choices')
            ->setIsExpanded(true);
        $allTypes[]=$type2;

        $type3= new TypeQuestion();
        $type3->setLabel('unique')
            ->setIntituleType("Choix unique")
            ->setIsExpanded(true);
        $allTypes[]=$type3;

        $size= count($allTypes);
        for ($i=0; $i<$size; $i++){
            $manager->persist($allTypes[$i]);
        }


        // Mise en place des catégories de Sondage
        $allCats=[];
        $cat1= new CategorieSondage();
        $cat1
            ->setNomCategorie("Soin de la peau");
        $allCats[]=$cat1;

        $cat2= new CategorieSondage();
        $cat2
            ->setNomCategorie("Maquillage")
            ->setEmoji('💄');
        $allCats[]=$cat2;

        $size= count($allCats);
        for ($i=0; $i<$size; $i++){
            $manager->persist($allCats[$i]);
        }


        //CREATION D'UN SONDAGE
        $sondage = new Sondage();
        $sondage->setIntitule("Un trés beau sondage")
            ->setDescription("oui oui")
            ->setSondeur($sondeur)
            ->setCategorieSondage($cat2)
            ->setDateFin(new DateTimeImmutable())  //'yesterday'
            ->setEtatSondage("EN_COURS")
            ->setDateUpdate(new DateTimeImmutable())
            ->setDateCreation(new DateTimeImmutable());

        for ($i=0; $i<5;$i++){
            $question=new Question();
            $question
                ->setIntitule("Question numero ".$i)
                ->setTypeQuestion($allTypes[($i%3)]);

            for ($j=0; $j<3;$j++){
                $reponse = new Reponse();
                $reponse->setLaReponse("la reponse ".$j);
                $reponse->setQuestion($question);
                $question->addReponse($reponse);
                $manager->persist($reponse);
            }
            $question->setSondage($sondage);
            $sondage->addQuestion($question);
            $manager->persist($question);
        }
        $manager->persist($sondage);


        $sondage2 = new Sondage();
        $sondage2->setIntitule("Un trés beau sondage")
            ->setDescription("oui oui")
            ->setSondeur($sondeur)
            ->setCategorieSondage($cat2)
            ->setDateFin(new DateTimeImmutable())  //'yesterday'
            ->setEtatSondage("EN_COURS")
            ->setDateUpdate(new DateTimeImmutable())
            ->setDateCreation(new DateTimeImmutable());

        for ($i=0; $i<5;$i++){
            $question=new Question();
            $question
                ->setIntitule("Question numero ".$i)
                ->setTypeQuestion($allTypes[($i%3)]);

            for ($j=0; $j<3;$j++){
                $reponse = new Reponse();
                $reponse->setLaReponse("la reponse ".$j);
                $reponse->setQuestion($question);
                $question->addReponse($reponse);
                $manager->persist($reponse);
            }
            $question->setSondage($sondage);
            $sondage->addQuestion($question);
            $manager->persist($question);
        }
        $manager->persist($sondage2);
        $manager->flush();

    }
}
