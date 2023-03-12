<?php

namespace App\DataFixtures;

use App\Entity\Question;
use App\Entity\Reponse;
use App\Entity\Sondage;
use App\Entity\TypeQuestion;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypeQuestionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $allTypes=[];

//        $type1= new TypeQuestion();
//        $type1
//            ->setIntituleType("Liste déroulante");
//
//        $allTypes[]=$type1;

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


        //CREATION D'UN SONDAGE
        $sondage = new Sondage();
        $sondage->setIntitule("Un trés beau sondage")
            ->setDescription("oui oui")
            ->setDateLancement(new DateTimeImmutable())
            ->setDateFin(new DateTimeImmutable())
            ->setEtatSondage("EN_COURS")
            ->setDateUpdate(new DateTimeImmutable())
            ->setDateCreation(new DateTimeImmutable());

        for ($i=0; $i<5;$i++){
            $question=new Question();
            $question
                ->setIntitule("Question numero ".$i)
                ->setTypeQuestion($allTypes[($i%2)]);

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
        // $product = new Product();
        // $manager->persist($product);
        $manager->flush();
    }
}
