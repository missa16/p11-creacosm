<?php

namespace App\Controller;

use App\Entity\CategorieSondage;
use App\Entity\Formation;
use App\Entity\Sondage;
use App\Entity\StatsQuestion;
use App\Entity\User;
use App\Form\CategorieSondageType;
use App\Form\connexion\RegistrationFormType;
use App\Form\FormationType;
use App\Repository\CategorieSondageRepository;
use App\Repository\FormationRepository;
use App\Repository\QuestionRepository;
use App\Repository\SondageRepository;
use App\Repository\UserRepository;
use App\Security\AppUserAuthenticator;
use App\Service\ColorGenerator;
use App\Service\GenerateFile;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Ods;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    #[Route('/all-sondages', name: 'app_admin_all_surveys', methods: ['GET'])]
    public function getAllSurveys(SondageRepository $sondageRepository): Response
    {
        $allSondages = $sondageRepository->findAll();
        return $this->render('admin/all_sondages.html.twig', [
            'sondages' => $allSondages,
        ]);
    }

    #[Route('/all-sondeurs', name: 'app_admin_show_sondeurs', methods: ['GET'])]
    public function getSondeurs(UserRepository $userRepository): Response
    {
        $sondeurs = $userRepository->findSondeurs();
        return $this->render('admin/all_sondeurs.html.twig', [
            'sondeurs' => $sondeurs,
        ]);
    }

    #[Route('/ajouter-sondeur', name: 'app_admin_new_sondeur')]
    public function registerInstructeur(AppUserAuthenticator $appUserAuthenticator,Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $role = ['ROLE_SONDEUR'];
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            )
                // Enregistrer en tant que sondeur
                ->setRoles($role);
            $entityManager->persist($user);

            $this->addFlash('success','Vous avez bien ajouté un nouveau sondeur');
            $entityManager->flush();
            return $appUserAuthenticator->onAddSondeur();
        }
        return $this->render('admin/register-sondeur.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/sondages/{id}', name: 'app_admin_show', methods: ['GET'])]
    public function show(Sondage $sondage): Response
    {
        return $this->render('admin/show_sondage.html.twig', [
            'sondage' => $sondage,
        ]);
    }

    // CRUD des formations
    #[Route('/formation', name: 'app_formation_index', methods: ['GET'])]
    public function indexFormation(FormationRepository $formationRepository): Response
    {
        return $this->render('admin/formation/index.html.twig', [
            'formations' => $formationRepository->findAll(),
        ]);
    }

    #[Route('/formation/new', name: 'app_formation_new', methods: ['GET', 'POST'])]
    public function newFormation(Request $request, FormationRepository $formationRepository): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formationRepository->save($formation, true);

            return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/formation/new.html.twig', [
            'formation' => $formation,
            'form' => $form,
        ]);
    }

    #[Route('/formation/{id}', name: 'app_formation_show', methods: ['GET'])]
    public function showFormation(Formation $formation): Response
    {
        return $this->render('admin/formation/show.html.twig', [
            'formation' => $formation,
        ]);
    }

    #[Route('/formation/{id}/edit', name: 'app_formation_edit', methods: ['GET', 'POST'])]
    public function editFormation(Request $request, Formation $formation, FormationRepository $formationRepository): Response
    {
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formationRepository->save($formation, true);

            return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/formation/edit.html.twig', [
            'formation' => $formation,
            'form' => $form,
        ]);
    }

    #[Route('/formation/{id}', name: 'app_formation_delete', methods: ['POST'])]
    public function deleteFormation(Request $request, Formation $formation, FormationRepository $formationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$formation->getId(), $request->request->get('_token'))) {
            $formationRepository->remove($formation, true);
        }

        return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
    }



    // CRUD des categorie de sondage
    #[Route('/categorie-sondage', name: 'app_categorie_sondage_index', methods: ['GET'])]
    public function indexCategorieSondage(CategorieSondageRepository $categorieSondageRepository): Response
    {
        return $this->render('admin/categorie_sondage/index.html.twig', [
            'categorie_sondages' => $categorieSondageRepository->findAll(),
        ]);
    }

    #[Route('/categorie-sondage/new', name: 'app_categorie_sondage_new', methods: ['GET', 'POST'])]
    public function newCategorieSondage(Request $request, CategorieSondageRepository $categorieSondageRepository): Response
    {
        $categorieSondage = new CategorieSondage();
        $form = $this->createForm(CategorieSondageType::class, $categorieSondage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieSondageRepository->save($categorieSondage, true);

            return $this->redirectToRoute('app_categorie_sondage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/categorie_sondage/new.html.twig', [
            'categorie_sondage' => $categorieSondage,
            'form' => $form,
        ]);
    }

    #[Route('/categorie-sondage/{id}', name: 'app_categorie_sondage_show', methods: ['GET'])]
    public function showCategorieSondage(CategorieSondage $categorieSondage): Response
    {
        return $this->render('admin/categorie_sondage/show.html.twig', [
            'categorie_sondage' => $categorieSondage,
        ]);
    }

    #[Route('/categorie-sondage/{id}/edit', name: 'app_categorie_sondage_edit', methods: ['GET', 'POST'])]
    public function editCategorieSondage(Request $request, CategorieSondage $categorieSondage, CategorieSondageRepository $categorieSondageRepository): Response
    {
        $form = $this->createForm(CategorieSondageType::class, $categorieSondage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieSondageRepository->save($categorieSondage, true);

            return $this->redirectToRoute('app_categorie_sondage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/categorie_sondage/edit.html.twig', [
            'categorie_sondage' => $categorieSondage,
            'form' => $form,
        ]);
    }

    #[Route('/categorie-sondage/{id}', name: 'app_categorie_sondage_delete', methods: ['POST'])]
    public function deleteCategorieSondage(Request $request, CategorieSondage $categorieSondage, CategorieSondageRepository $categorieSondageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorieSondage->getId(), $request->request->get('_token'))) {
            $categorieSondageRepository->remove($categorieSondage, true);
        }

        return $this->redirectToRoute('app_categorie_sondage_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/mes-sondages/{id}/stats', name: 'app_admin_stats_survey', methods: ['GET', 'POST'])]
    public function statSondage(SondageRepository $sondageRepository,FormationRepository $formationRepository,QuestionRepository $questionRepository, Sondage $sondage): Response
    {
        $colorGenerator = new ColorGenerator();
        $colors = $colorGenerator->generatePastelColors();
        $ageChart= $sondageRepository->findAgeSondes($sondage,$colors);
        $formationChart = $sondageRepository->findFormationSondes($sondage,$formationRepository,$colors);
        $genreChart = $sondageRepository->findGenreSondes($sondage,$colors);

        return $this->render('admin/stats_sondage.html.twig', [
            'sondage' => $sondage,
            'ageChart' => json_encode($ageChart),
            'formationChart' => json_encode($formationChart),
            'genreChart' => json_encode($genreChart)

        ]);

    }

    #[Route('/mes-sondages/{id}/stats-questions', name: 'app_admin_stats_question', methods: ['GET', 'POST'])]
    public function statsQuestion(FormationRepository $formationRepository,QuestionRepository $questionRepository, Sondage $sondage): Response
    {
        $colorGenerator = new ColorGenerator();
        $colors = $colorGenerator->generatePastelColors();

        $questions = $sondage->getQuestions();
        foreach ( $questions as $question){
            // stats globales
            $statsGles= json_encode($questionRepository->findStatsGlobales($question,$colors));
            $statsGlobalesQuestion= new StatsQuestion();
            $statsGlobalesQuestion
                ->setNomStat("Stat globale")
                ->setDataJson($statsGles);
            $question->addStatsQuestion($statsGlobalesQuestion);

            //stats par genre

            $statsGlesGenre= json_encode($questionRepository->findStatsParGenre($question,$colors));
            $statsGenreQuestion= new StatsQuestion();
            $statsGenreQuestion
                ->setNomStat("Stat par genre")
                ->setDataJson($statsGlesGenre);
            $question->addStatsQuestion($statsGenreQuestion);

            //stats par formation
            $statsGlesFormation= json_encode($questionRepository->findStatsParFormation($question,$formationRepository,$colors));
            $statsFormationQuestion= new StatsQuestion();
            $statsFormationQuestion
                ->setNomStat("Stat par formation")
                ->setDataJson($statsGlesFormation);
            $question->addStatsQuestion($statsFormationQuestion);

            //stats par age
            $statsGlesAge= json_encode($questionRepository->findStatsParTrancheAge($question,$colors));
            $statsAgeQuestion= new StatsQuestion();
            $statsAgeQuestion
                ->setNomStat("Stat par tranche d'age")
                ->setDataJson($statsGlesAge);
            $question->addStatsQuestion($statsAgeQuestion);

        }

        return $this->render('admin/stat_sondage_question.html.twig', [
            'sondage' => $sondage,
        ]);

    }

    #[Route('/export/{id}', name: 'app_admin_export', methods: ['GET'])]
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


}
