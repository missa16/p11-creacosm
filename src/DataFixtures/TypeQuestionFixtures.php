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

        $manager->flush();
    }
}
