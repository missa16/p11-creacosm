<?php

namespace App\Controller;

use App\Entity\Sondage;
use App\Entity\User;
use App\Form\creationSondage\SondageType;
use App\Repository\SondageRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sondeur')]
class SondeurController extends AbstractController
{
    #[Route('/', name: 'app_sondeur_index', methods: ['GET'])]
    public function index(SondageRepository $sondageRepository): Response
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
    public function show(Sondage $sondage): Response
    {
        return $this->render('sondeur/show.html.twig', [
            'sondage' => $sondage,
        ]);
    }



    #[Route('/new', name: 'app_sondeur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SondageRepository $sondageRepository): Response
    {
        $sondage = new Sondage();
        $user= $this->getUser();
        $form = $this->createForm(SondageType::class, $sondage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sondage->setDateCreation(new DateTimeImmutable());
            $sondage->setDateUpdate(new DateTimeImmutable());
            $sondage->setEtatSondage('EN_COURS');
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

    #[Route('/mes-sondages/{id}/edit', name: 'app_sondeur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sondage $sondage, SondageRepository $sondageRepository): Response
    {
        $form = $this->createForm(SondageType::class, $sondage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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

    #[Route('/{id}', name: 'app_sondeur_delete', methods: ['POST'])]
    public function delete(Request $request, Sondage $sondage, SondageRepository $sondageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $sondage->getId(), $request->request->get('_token'))) {
            $sondageRepository->remove($sondage, true);
        }

        return $this->redirectToRoute('app_sondeur_my_surveys', [], Response::HTTP_SEE_OTHER);
    }


}
