<?php

namespace App\Controller;

use App\Entity\Sondage;
use App\Entity\User;
use App\Entity\UserSondageReponse;
use App\Entity\UserSondageResult;
use App\Form\creationSondage\SondageType;
use App\Form\repondreSondage\RepondreSondageType;
use App\Repository\QuestionRepository;
use App\Repository\ReponseRepository;
use App\Repository\SondageRepository;
use App\Repository\UserRepository;
use App\Repository\UserSondageReponseRepository;
use App\Repository\UserSondageResultRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sondage')]
class SondageController extends AbstractController
{

    #[Route('/', name: 'app_sondage_index', methods: ['GET'])]
    public function index(): Response
    {
        $user = $this->getUser();
        return $this->render('user/index.html.twig',
        [
            'user'=>$user,
        ]);
    }

    #[Route('/sondages', name: 'app_sondage_now', methods: ['GET'])]
    public function sondagesNow(SondageRepository $sondageRepository): Response
    {
        $user = $this->getUser();
        return $this->render('user/sondages_en_cours.html.twig', [
            'sondages' => $sondageRepository->findAllSondageEnCours($user),
        ]);
    }

    #[Route('/sondages-repondus', name: 'app_sondage_historique', methods: ['GET'])]
    public function sondagesHistoriques(UserSondageResultRepository $userSondageResultRepository,UserRepository $userRepository, SondageRepository $sondageRepository): Response
    {
        $user = $this->getUser();;
        $sondagesRepondus = $userSondageResultRepository->findSondageRepondus($user);
        return $this->render('user/historique.html.twig', [
            'sondages' => $sondagesRepondus,
        ]);
    }




    // Répondre à sondage
    #[Route('/{id}/answer', name: 'app_sondage_answer', methods: ['GET', 'POST'])]
    public function answer(Sondage $sondage): Response
    {
        $nbQuestion = $sondage->getQuestions()->count();
        $temps = $nbQuestion." - ".$nbQuestion+2;
        return $this->render('user/answer_survey.html.twig', [
            'sondage' => $sondage,
            'temps'=>$temps,
        ]);
    }

    // Enregistrer les réponses d'un sondé
    #[Route('/{id}/answer-question', name: 'app_sondage_answer_questions', methods: ['GET', 'POST'])]
    public function answerQuestions(EntityManagerInterface $entityManager, Request $request, Sondage $sondage, QuestionRepository $questionRepository, ReponseRepository $reponseRepository, UserSondageResultRepository $userSondageResultRepository, UserSondageReponseRepository $userSondageReponseRepository): Response
    {
        $user = $this->getUser();

        //Enregistrement des réponses
        $form = $this->createForm(RepondreSondageType::class, null, [
            'sondage' => $sondage,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userSondageResult = new UserSondageResult();
            $userSondageResult->setSondage($sondage);
            $userSondageResult->setSonde($user);
            $userSondageResult->setDateReponse(new DateTimeImmutable());
            foreach ($form->getData() as $questionId => $reponsesIds) {
                $userSondageReponse = new UserSondageReponse();
                // get the question and selected reponses
                preg_match('/\d+/', $questionId, $matches);
                $idQ = (int)$matches[0];
                $question = $questionRepository->find($idQ);
                $userSondageReponse->setQuestion($question);

                //enregistrer les différentes réponses
                if(is_array($reponsesIds)){
                    foreach ( $reponsesIds as $reponseId){
                        $reponse=$reponseRepository->find($reponseId);
                        $userSondageReponse->addReponse($reponse);
                    }
                }
                else {
                    $reponse=$reponseRepository->find($reponsesIds);
                    $userSondageReponse->addReponse($reponse);
                }

                $userSondageReponse->setUserSondageResult($userSondageResult);
                $entityManager->persist($userSondageResult);
                $userSondageReponseRepository->save($userSondageReponse, true);
            }

            // save the userSondageResult entity
            $userSondageResultRepository->save($userSondageResult, true);

            return $this->redirectToRoute('app_sondage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/answer_survey_question.html.twig', [
            'sondage' => $sondage,
            'form' => $form
        ]);

    }


    #[Route('/{id}/save-avances', name: 'app_sondage_save_avances', methods: ['GET'])]
    public function comeBackLater(Request $request, Sondage $sondage): Response
    {
       // $user = $this->getUser();

        $form = $this->createForm(RepondreSondageType::class, null, [
            'sondage' => $sondage,
        ]);

        $form->handleRequest($request);

        return $this->render('user/back_later.html.twig', [
            'form' => $form
        ]);
    }
}



