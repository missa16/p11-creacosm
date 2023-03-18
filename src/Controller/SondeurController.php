<?php

namespace App\Controller;

use App\Entity\Sondage;
use App\Entity\User;
use App\Entity\UserSondageResult;
use App\Form\creationSondage\SondageType;
use App\Repository\SondageRepository;
use App\Repository\UserRepository;
use App\Repository\UserSondageResultRepository;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use DateTime;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

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

    #[Route('/mes-sondages/{id}', name: 'app_sondeur_delete', methods: ['POST'])]
    public function delete(Request $request, Sondage $sondage, SondageRepository $sondageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $sondage->getId(), $request->request->get('_token'))) {
            $sondageRepository->remove($sondage, true);
        }

        return $this->redirectToRoute('app_sondeur_my_surveys', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/mes-sondages/{id}/stats', name: 'app_sondeur_stats_survey', methods: ['GET', 'POST'])]
    public function statSondage(SondageRepository $sondageRepository, Sondage $sondage, ChartBuilderInterface $chartBuilder): Response
    {

       $results = $sondage->getLesSondes();
       $ages=[];

       foreach ( $results as $result){
           $sonde= $result->getSonde();
           $dateNaissance = $sonde->getDateNaissance();
           $age = $dateNaissance->diff(new DateTime())->y;
           $ages[]=$age;
       }

        $intervals = [
            ['min' => 0, 'max' => 18],
            ['min' => 19, 'max' => 24],
            ['min' => 25, 'max' => 30],
            ['min' => 40, 'max' => 100],
        ];

        $counts = array_fill(0, count($intervals), 0);

        foreach ($ages as $age) {
            foreach ($intervals as $i => $interval) {
                if ($age >= $interval['min'] && $age <= $interval['max']) {
                    $counts[$i]++;
                    break;
                }
            }
        }

            $data = [
                'labels' => array_map(function ($interval) {
                    return $interval['min'] . '-' . $interval['max'];
                }, $intervals),
                'datasets' => [
                    [
                        'label' => 'Age distribution',
                        'data' => $counts,
                        'backgroundColor' => [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                        ],
                        'borderColor' => [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1'
                            ],
                    ],
                ],
            ];

        $options = [
            'scales' => [
                'yAxes' => [
                    [
                        'ticks' => [
                            'beginAtZero' => true
                        ]
                    ]
                ]
            ]
        ];

        return $this->render('sondeur/stats_sondage.html.twig', [
            'sondage' => $sondage,
            //'chart_data' => json_encode($data),
            //'chart_options' => json_encode($options),
            'results' => $counts
        ]);

    }

}
