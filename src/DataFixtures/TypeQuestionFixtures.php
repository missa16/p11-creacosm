<?php

namespace App\DataFixtures;

use App\Entity\TypeQuestion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypeQuestionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $allTypes=[];

        $type1= new TypeQuestion();
        $type1->setIntituleType("Choix multiple");
        $allTypes[]=$type1;

        $type2= new TypeQuestion();
        $type2->setIntituleType("Choix unique");
        $allTypes[]=$type2;


        $size= count($allTypes);
        for ($i=0; $i<$size; $i++){
            $manager->persist($allTypes[$i]);
        }
        $manager->flush();
    }
}
