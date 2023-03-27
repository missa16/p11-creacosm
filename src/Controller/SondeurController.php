<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Sondage;
use App\Entity\StatsQuestion;
use App\Form\creationSondage\SondageType;
use App\Repository\FormationRepository;
use App\Repository\QuestionRepository;
use App\Repository\SondageRepository;
use App\Repository\UserRepository;
use App\Service\GenerateFile;
use App\Service\PictureService;
use DateTimeImmutable;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Ods;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Validator\Constraints\Json;

#[Route('/sondeur')]
class SondeurController extends AbstractController
{
    #[Route('/', name: 'app_sondeur_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('sondeur/index.html.twig');
    }


    #[Route('/mes-sondages', name: 'app_sondeur_my_surveys', methods: ['GET'])]
    public function getMySurveys(UserRepository $userRepository): Response
    {
        $user= $this->getUser();
        $mesSondages = $userRepository->findMesSondages($user);
        return $this->render('sondeur/mes_sondages.html.twig', [
            'sondages' => $mesSondages,
        ]);
    }

    #[Route('/mes-sondages/{id}', name: 'app_sondeur_show', methods: ['GET'])]
    public function show(SondageRepository $sondageRepository, Sondage $sondage): Response
    {
        $user = $this->getUser();

        // interdire l'accés si ce n'est pas le créateur du sondage
        if($user !== $sondage->getSondeur()){
                return $this->render('atelier/index.html.twig', [
                    'ateliers' => $sondageRepository->findAll(),
                ]);
        }
        return $this->render('sondeur/show.html.twig', [
            'sondage' => $sondage,
        ]);
    }


    /**
     * @throws \Doctrine\DBAL\Exception
     */
    #[Route('/new', name: 'app_sondeur_new', methods: ['GET', 'POST'])]

    public function new(PictureService $pictureService, Request $request, SondageRepository $sondageRepository): Response
    {

        $sondage = new Sondage();
        $user= $this->getUser();
        $form = $this->createForm(SondageType::class, $sondage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //dd();
            $questions=$sondage->getQuestions()->toArray();
            foreach ($form->get('Questions') as $i=>$question) {
                $imageQuestion = $question->get('imageQuestion')->getData();
                $question = $questions[$i];
                //dd($imageQuestion.$question);
                if ($imageQuestion != null) {
                    // On définit le dossier de destination
                    $folder = 'image-question';
                    //on appelle le service d'ajout
                    $fichier = $pictureService->add($imageQuestion,$folder,300,300);
                    $question->setImageQuestion($fichier);
                }
            }

            $image =  $form->get('imageCouverture')->getData();
            if(!$image==null){
                // On définit le dossier de destination
                $folder = 'couverture-sondage';
                //on appelle le service d'ajout
                $fichier = $pictureService->add($image,$folder,300,300);
                $sondage->setImageCouverture($fichier);
            }

            $sondage->setDateCreation(new DateTimeImmutable());
            $sondage->setDateUpdate(new DateTimeImmutable());
            if($form->get('Brouillon')->isClicked()){
                $sondage->setEtatSondage('BROUILLON');
            }
            else{
                $sondage->setEtatSondage('EN_COURS');
            }
            $sondage->setSondeur($user);
            $sondageRepository->save($sondage, true);

            $this->addFlash(
                'success', 'Vous avez bien créer un  nouveau sondage: %s!');

            return $this->redirectToRoute('app_sondeur_my_surveys', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sondeur/new.html.twig', [
            //'sondage' => $sondage,
            'form' => $form,
        ]);
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    #[Route('/mes-sondages/{id}/edit', name: 'app_sondeur_edit', methods: ['GET', 'POST'])]
    public function edit(PictureService $pictureService,Request $request, Sondage $sondage, SondageRepository $sondageRepository): Response
    {
        $form = $this->createForm(SondageType::class, $sondage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $questions=$sondage->getQuestions()->toArray();
            foreach ($form->get('Questions') as $i=>$question) {
                $imageQuestion = $question->get('imageQuestion')->getData();
                $question = $questions[$i];
                //dd($imageQuestion.$question);
                if ($imageQuestion != null) {
                    // On définit le dossier de destination
                    $folderQ = 'image-question';
                    //on appelle le service d'ajout
                    $fichier = $pictureService->add($imageQuestion,$folderQ,300,300);
                    $question->setImageQuestion($fichier);
                }
            }

            // on récupère l'image
            $image =  $form->get('imageCouverture')->getData();
            if(!$image==null){
                // On définit le dossier de destination
                $folder = 'couverture-sondage';
                //on appelle le service d'ajout
                $fichier = $pictureService->add($image,$folder,300,300);
                $sondage->setImageCouverture($fichier);
            }
            if($form->get('Brouillon')->isClicked()){
                $sondage->setEtatSondage('BROUILLON');
            }
            else{
                $sondage->setEtatSondage('EN_COURS');
            }
            $sondage->setDateUpdate(new DateTimeImmutable());
            $sondageRepository->save($sondage, true);
            $this->addFlash('success', 'Sondage mis à jour !');
            return $this->redirectToRoute('app_sondeur_my_surveys', [], Response::HTTP_SEE_OTHER);
        }


        return $this->renderForm('sondeur/edit.html.twig', [
            'sondage' => $sondage,
            'form' => $form,
        ]);
    }

    #[Route('/mes-sondages/delete/{id}', name: 'app_sondeur_delete', methods: ['GET','POST'])]
    public function delete(Request $request, SondageRepository $sondageRepository): Response
    {
        $idSondage = (int)($request->get('id'));
        $sondage= $sondageRepository->find($idSondage);
        $sondageRepository->remove($sondage, true);
        return $this->redirectToRoute('app_sondeur_my_surveys', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/mes-sondages/{id}/stats', name: 'app_sondeur_stats_survey', methods: ['GET', 'POST'])]
    public function statSondage(SondageRepository $sondageRepository,FormationRepository $formationRepository,QuestionRepository $questionRepository, Sondage $sondage): Response
    {
        $ageChart= $sondageRepository->findAgeSondes($sondage);
        $formationChart = $sondageRepository->findFormationSondes($sondage,$formationRepository);
        $genreChart = $sondageRepository->findGenreSondes($sondage);

        $questions = $sondage->getQuestions();
        foreach ( $questions as $question){
            // stats globales
            $statsGles= json_encode($questionRepository->findStatsGlobales($question));
            $statsGlobalesQuestion= new StatsQuestion();
            $statsGlobalesQuestion
                ->setNomStat("Stat globale")
                ->setDataJson($statsGles);
            $question->addStatsQuestion($statsGlobalesQuestion);

            //stats par genre

            $statsGlesGenre= json_encode($questionRepository->findStatsParGenre($question));
            $statsGenreQuestion= new StatsQuestion();
            $statsGenreQuestion
                ->setNomStat("Stat par genre")
                ->setDataJson($statsGlesGenre);
            $question->addStatsQuestion($statsGenreQuestion);


            //stats par formation
            $statsGlesFormation= json_encode($questionRepository->findStatsParFormation($question,$formationRepository));
            $statsFormationQuestion= new StatsQuestion();
            $statsFormationQuestion
                ->setNomStat("Stat par formation")
                ->setDataJson($statsGlesFormation);
            $question->addStatsQuestion($statsFormationQuestion);


            //stats par age
            $statsGlesAge= json_encode($questionRepository->findStatsParTrancheAge($question));
            $statsAgeQuestion= new StatsQuestion();
            $statsAgeQuestion
                ->setNomStat("Stat par tranche d'age")
                ->setDataJson($statsGlesAge);
            $question->addStatsQuestion($statsAgeQuestion);

        }


        return $this->render('sondeur/stats_sondage.html.twig', [
            'sondage' => $sondage,
            'ageChart' => json_encode($ageChart),
            'formationChart' => json_encode($formationChart),
            'genreChart' => json_encode($genreChart),

        ]);

    }

    #[Route('/mes-sondages/{id}/stats-questions', name: 'app_sondeur_stats_question', methods: ['GET', 'POST'])]
    public function statsQuestion(FormationRepository $formationRepository,QuestionRepository $questionRepository, Sondage $sondage): Response
    {
        $questions = $sondage->getQuestions();
        foreach ( $questions as $question){
            // stats globales
            $statsGles= json_encode($questionRepository->findStatsGlobales($question));
            $statsGlobalesQuestion= new StatsQuestion();
            $statsGlobalesQuestion
                ->setNomStat("Stat globale")
                ->setDataJson($statsGles);
            $question->addStatsQuestion($statsGlobalesQuestion);

            //stats par genre

            $statsGlesGenre= json_encode($questionRepository->findStatsParGenre($question));
            $statsGenreQuestion= new StatsQuestion();
            $statsGenreQuestion
                ->setNomStat("Stat par genre")
                ->setDataJson($statsGlesGenre);
            $question->addStatsQuestion($statsGenreQuestion);

            //stats par formation
            $statsGlesFormation= json_encode($questionRepository->findStatsParFormation($question,$formationRepository));
            $statsFormationQuestion= new StatsQuestion();
            $statsFormationQuestion
                ->setNomStat("Stat par formation")
                ->setDataJson($statsGlesFormation);
            $question->addStatsQuestion($statsFormationQuestion);

            //stats par age
            $statsGlesAge= json_encode($questionRepository->findStatsParTrancheAge($question));
            $statsAgeQuestion= new StatsQuestion();
            $statsAgeQuestion
                ->setNomStat("Stat par tranche d'age")
                ->setDataJson($statsGlesAge);
            $question->addStatsQuestion($statsAgeQuestion);

        }

        return $this->render('sondeur/stat_sondage_question.html.twig', [
            'sondage' => $sondage,
        ]);

    }

    #[Route('/export/{id}', name: 'app_sondeur_export', methods: ['GET'])]
    public function export(Request $request,GenerateFile $generateFile,Sondage $sondage): BinaryFileResponse
    {
        $spreadsheet= $generateFile->export($sondage);
        $nomSondage = $sondage->getIntitule();

        //Avoir un nom de fichier clair
        $nomFichier = strtr(utf8_decode($nomSondage), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
        $nomFichier= strtolower(str_replace(' ', '_', $nomFichier));
        $nomFichier = "donnees_sondage_creacosm_".$nomFichier;

        $format= $request->query->get('format');
        switch ($format){
            case 'ods':
                $writer = new Ods($spreadsheet);
                $filename = $nomFichier.'.ods';
                break;
            case 'xlsx' :
                $writer = new Xlsx($spreadsheet);
                $filename = $nomFichier.'.xlsx';
                break;
            case 'csv' :
                $writer = new Csv($spreadsheet);
                $filename = $nomFichier.'.csv';
                break;
        }
        $path = sys_get_temp_dir().'/'.$filename;
        try {
            $writer->save($path);
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }
        // Send the file to the client for download
        $response = new BinaryFileResponse($path);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename);
        return $response;
    }


    #[Route('/mes-sondages/image/{id}', name: 'app_sondeur_delete_image', methods: ['POST'])]
    public function deleteImage(PictureService $pictureService,Request $request, SondageRepository $sondageRepository): Response
    {
        $idSondage = (int)($request->get('id'));
        $sondage= $sondageRepository->find($idSondage);
        $nom = $sondage->getImageCouverture();
        $pictureService->delete($nom,'couverture-sondage',300,300);
        $sondage->deleteImageCouverture();
        $sondageRepository->save($sondage,true);

        return $this->redirectToRoute('app_sondeur_edit',
            [
                'id'=> $sondage->getId(),
            ],
            Response::HTTP_SEE_OTHER);
    }


    #[Route('/mes-sondages/image-question/{id}', name: 'app_sondeur_delete_image_question', methods: ['GET','POST'])]
    public function deleteImageQuestion(QuestionRepository $questionRepository,PictureService $pictureService,Request $request): Response
    {

        $idQuestion = (int)($request->get('id'));
        $question= $questionRepository->find($idQuestion);
        $nom = $question->getImageQuestion();
        $pictureService->delete($nom,'couverture-sondage',300,300);
        $question->deleteImageQuestion();
        $sondage=$question->getSondage();
        $questionRepository->save($question,true);

        return $this->redirectToRoute('app_sondeur_edit',
            [
                'id'=> $sondage->getId(),
            ],
            Response::HTTP_SEE_OTHER);
    }

}
