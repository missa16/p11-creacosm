<?php

namespace App\DataFixtures;

use App\Entity\Question;
use App\Entity\Reponse;
use App\Entity\Sondage;
use App\Repository\ReponseRepository;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

    }
}
