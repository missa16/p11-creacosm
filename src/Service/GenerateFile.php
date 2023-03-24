<?php

namespace App\Service;

use App\Entity\Sondage;
use DateTime;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class GenerateFile
{
    public function export(Sondage $sondage): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $questions = $sondage->getQuestions();
        $premieresLigne = ['Email','Genre','Activité Pro','Age'];
        foreach ($questions as $question) {
            $premieresLigne[] = $question->getIntitule();
        }

        // Add some data to the spreadsheet
        foreach ($premieresLigne as $i => $data) {
            $i++;
            $sheet->setCellValue([$i, 1], $data);
        }

        $userSondageResult = $sondage->getLesSondes();
        foreach ($userSondageResult as $k => $dataSonde) {
            $k = $k + 2;
            $j = 1;
            $email = $dataSonde->getSonde()->getEmail();
            $genre= $dataSonde->getSonde()->getGenre();
            $formation= $dataSonde->getSonde()->getFormation()->getNomFormation();
            $birthDate= $dataSonde->getSonde()->getDateNaissance();
            $age =  $birthDate->diff(new DateTime())->y;
            $sheet->setCellValue([$j++, $k], $email);
            $sheet->setCellValue([$j++, $k], $genre);
            $sheet->setCellValue([$j++, $k], $formation);
            $sheet->setCellValue([$j, $k], $age);
            $repsSonde = $dataSonde->getAllReponses();
            foreach ($repsSonde as $repSonde) {
                $j++;
                $reponses = $repSonde->getReponses();
                $reponsesString = "";
                foreach ($reponses as $rep) {
                    $nbrReponses = sizeof($reponses);
                    if ($nbrReponses == 1) {
                        $reponsesString = $rep->getLaReponse();
                    } else {
                        if ($reponses->last() == $rep) {
                            $reponsesString = $reponsesString . $rep->getLaReponse();
                        } else {
                            $reponsesString = $reponsesString . $rep->getLaReponse() . ", ";
                        }
                    }
                }
                $sheet->setCellValue([$j, $k], $reponsesString);
            }
        }
        return $spreadsheet;
    }
}