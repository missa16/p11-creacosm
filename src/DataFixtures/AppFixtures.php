<?php

namespace App\DataFixtures;

use App\Entity\CategorieSondage;
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
            ->setVille('Orélans');
        $manager->persist($sondeur);

        // Mise en place des types de question
        $allTypes=[];
        $type1= new TypeQuestion();
        $type1
            ->setIntituleType("Liste déroulante");
        $allTypes[]=$type1;

        $type2= new TypeQuestion();
        $type2
            ->setIntituleType("Choix multiple")
            ->setIsMultiple(true);
        $type2->setIsExpanded(true);
        $allTypes[]=$type2;

        $type3= new TypeQuestion();
        $type3
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
            ->setNomCategorie("Maquillage");
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
            ->setDateLancement(new DateTimeImmutable())
            ->setDateFin(new DateTimeImmutable())
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
        $manager->flush();

    }
}
