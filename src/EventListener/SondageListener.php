<?php

namespace App\EventListener;

use App\Entity\Sondage;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class SondageListener
{
    public function prePersist(Sondage $survey, LifecycleEventArgs $event): void
    {
        $this->updateSurveyState($survey);
    }

    public function preUpdate(Sondage $survey, LifecycleEventArgs $event): void
    {
        $this->updateSurveyState($survey);
    }

    private function updateSurveyState(Sondage $survey): void
    {
        if ($survey->getDateFin() < new \DateTime('now')) {
            $survey->setEtatSondage('TERMINE');
        }
    }
}
