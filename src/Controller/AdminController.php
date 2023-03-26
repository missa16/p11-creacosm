<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Sondage;
use App\Entity\StatsQuestion;
use App\Entity\User;
use App\Form\connexion\RegistrationFormType;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use App\Repository\QuestionRepository;
use App\Repository\SondageRepository;
use App\Repository\UserRepository;
use App\Security\AppUserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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

    #[Route('/sondages/{id}/stats', name: 'app_admin_stats_survey', methods: ['GET', 'POST'])]
    public function statSondage(SondageRepository $sondageRepository,FormationRepository $formationRepository,QuestionRepository $questionRepository, Sondage $sondage): Response
    {
        $ageChart= $sondageRepository->findAgeSondes($sondage);
        $formationChart = $sondageRepository->findFormationSondes($sondage,$formationRepository);
        $genreChart = $sondageRepository->findGenreSondes($sondage);

        $questions = $sondage->getQuestions();
        foreach ( $questions as $i=> $question){
            $statsGles= json_encode($questionRepository->findStatsGlobales($question));
            $statsGlobalesQuestion= new StatsQuestion();
            $statsGlobalesQuestion
                ->setNomStat("Stats globale")
                ->setDataJson($statsGles);
            $question->addStatsQuestion($statsGlobalesQuestion);
        }

        return $this->render('admin/stats_sondage.html.twig', [
            'sondage' => $sondage,
            'ageChart' => json_encode($ageChart),
            'formationChart' => json_encode($formationChart),
            'genreChart' => json_encode($genreChart),
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


}
