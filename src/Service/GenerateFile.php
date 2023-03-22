<?php

namespace App\Service;

use App\Entity\Sondage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class GenerateFile
{
    public function export(Sondage $sondage): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $questions = $sondage->getQuestions();
        $premiereLigne = ['Email'];
        foreach ($questions as $question) {
            $premiereLigne[] = $question->getIntitule();
        }

        // Add some data to the spreadsheet
        foreach ($premiereLigne as $i => $data) {
            $i++;
            $sheet->setCellValue([$i, 1], $data);
        }

        $userSondageResult = $sondage->getLesSondes();
        foreach ($userSondageResult as $k => $dataSonde) {
            $k = $k + 2;
            $j = 1;
            $email = $dataSonde->getSonde()->getEmail();
            $sheet->setCellValue([$j, $k], $email);
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