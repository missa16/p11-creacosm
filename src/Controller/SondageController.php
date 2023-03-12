<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Sondage;
use App\Entity\User;

use App\Entity\UserSondageReponse;
use App\Entity\UserSondageResult;
use App\Form\RepondreSondageType;

use App\Form\SondageType;

use App\Repository\QuestionRepository;
use App\Repository\ReponseRepository;
use App\Repository\SondageRepository;

use App\Repository\UserSondageReponseRepository;
use App\Repository\UserSondageResultRepository;
use AppBundle\Form\QuestionEmbededForm;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sondage')]
class SondageController extends AbstractController
{
    #[Route('/', name: 'app_sondage_index', methods: ['GET'])]
    public function index(SondageRepository $sondageRepository): Response
    {
        return $this->render('sondage/index.html.twig', [
            'sondages' => $sondageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_sondage_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SondageRepository $sondageRepository): Response
    {
        $sondage = new Sondage();
        $form = $this->createForm(SondageType::class, $sondage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sondage->setDateCreation(new DateTimeImmutable());
            $sondage->setDateUpdate(new DateTimeImmutable());
            $sondage->setEtatSondage('EN_COURS');
            $sondageRepository->save($sondage, true);

            $this->addFlash(
                'success', 'Vous avez bien créer un  nouveau sondage: %s!');

            return $this->redirectToRoute('app_sondage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sondage/new.html.twig', [
            //'sondage' => $sondage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sondage_show', methods: ['GET'])]
    public function show(Sondage $sondage): Response
    {
        return $this->render('sondage/show.html.twig', [
            'sondage' => $sondage,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sondage_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sondage $sondage, SondageRepository $sondageRepository): Response
    {
        $form = $this->createForm(SondageType::class, $sondage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sondage->setDateUpdate(new DateTimeImmutable());
            $sondageRepository->save($sondage, true);
            $this->addFlash('success', 'Sondage mis à jour !');
            return $this->redirectToRoute('app_sondage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sondage/edit.html.twig', [
            'sondage' => $sondage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sondage_delete', methods: ['POST'])]
    public function delete(Request $request, Sondage $sondage, SondageRepository $sondageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $sondage->getId(), $request->request->get('_token'))) {
            $sondageRepository->remove($sondage, true);
        }

        return $this->redirectToRoute('app_sondage_index', [], Response::HTTP_SEE_OTHER);
    }


    // Répondre à sondage
    #[Route('/{id}/answer', name: 'app_sondage_answer', methods: ['GET', 'POST'])]
    public function answer(Request $request, Sondage $sondage, SondageRepository $sondageRepository): Response
    {
        $nbQuestion = $sondage->getQuestions()->count();
        return $this->render('user/answer_survey.html.twig', [
            'sondage' => $sondage,
            'questions' => $nbQuestion,
        ]);
    }

    #[Route('/{id}/answer-question', name: 'app_sondage_answer_questions', methods: ['GET', 'POST'])]
    public function answerQuestions(EntityManagerInterface $entityManager, Request $request, Sondage $sondage, QuestionRepository $questionRepository, ReponseRepository $reponseRepository, UserSondageResultRepository $userSondageResultRepository, UserSondageReponseRepository $userSondageReponseRepository): Response
    {
        // A enlever lors de la creation du form d'authentification
        $user = new User();
        $user->setEmail('lol@gmail.com');
        $user->setPassword('123');

        //Enregistrement des réponses

        $form = $this->createForm(RepondreSondageType::class, null, [
            'sondage' => $sondage,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userSondageResult = new UserSondageResult();
            $userSondageResult->setSondage($sondage);

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
}



