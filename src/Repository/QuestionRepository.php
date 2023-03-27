<?php

namespace App\Repository;

use App\Entity\Question;
use App\Entity\UserSondageResult;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Query;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Expr\Array_;

/**
 * @extends ServiceEntityRepository<Question>
 *
 * @method Question|null find($id, $lockMode = null, $lockVersion = null)
 * @method Question|null findOneBy(array $criteria, array $orderBy = null)
 * @method Question[]    findAll()
 * @method Question[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    public function save(Question $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Question $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Question[] Returns an array of Question objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Question
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function findStatsGlobales(Question $question,array $colors) : array
    {
        // Les réponses possibles
        $toutesLesReponses = $question->getReponses(); // une collection ou toutes les objets reponses sont repertorié
        $reponsesPossibles=[];
        foreach ( $toutesLesReponses as $i => $rep){
            $reponsesPossibles[]=$rep->getLaReponse();
        }

        $userSondageReponses= $question->getUserSondageReponses();
        $responsesCounts = array_fill(0, count($reponsesPossibles), 0);

        foreach ($userSondageReponses as $userSondageReponse) {
            $reponsesSonde = $userSondageReponse->getReponses();
            foreach ($reponsesSonde as $reponseSonde) {
                $reponseIndex = array_search($reponseSonde->getLaReponse(), $reponsesPossibles);
                if ($reponseIndex !== false) {
                    $responsesCounts[$reponseIndex]++;
                }
            }
        }

        $chart_data = [
            'labels' => $reponsesPossibles,
            'datasets' => [
                [
                    'data' => $responsesCounts,
                    'backgroundColor'=> [$colors[rand(0, 50)],$colors[rand(0, 50)],$colors[rand(0, 50)],$colors[rand(0, 50)]]
                ],
            ],
        ];
        //$question->addStatQuestion();
        return $chart_data;
    }


    public function findStatsParGenre(Question $question,array $colors) : array
    {
        // les genres
        $genres = ['Femme','Homme','Autre'];

        $toutesLesReponses = $question->getReponses(); // une collection ou toutes les objets reponses sont repertorié
        $reponsesPossibles=[];
        foreach ( $toutesLesReponses as $i => $rep){
            $reponsesPossibles[]=$rep->getLaReponse();
        }

        // Initialize the array to count the responses for each genre
        $responsesByGenre = [];
        foreach ($genres as $genre) {
            $responsesByGenre[$genre] = array_fill(0, count($question->getReponses()), 0);
        }

        // Loop over each user sondage reponse to count the responses
        foreach ($question->getUserSondageReponses() as $userSondageReponse) {
            $userGenre = $userSondageReponse->getUserSondageResult()->getSonde()->getGenre();
            if (in_array($userGenre, $genres)) {
                $reponsesSonde = $userSondageReponse->getReponses();
                foreach ($reponsesSonde as $reponseSonde) {

                    $reponseIndex = array_search($reponseSonde->getLaReponse(), $reponsesPossibles);
                    if ($reponseIndex !== false) {
                        $responsesByGenre[$userGenre][$reponseIndex]++;
                    }
                }
            }
        }

        // Create the chart data array with dynamic labels
        $chart_data = [
            'labels' => $reponsesPossibles,
            'datasets' => [],
        ];

        // Loop over each genre to add the data to the chart data array
        foreach ($genres as $genre) {
            $chart_data['datasets'][] = [
                'label' => $genre,
                'data' => $responsesByGenre[$genre],
                'backgroundColor'=> $colors[rand(0, 50)],
            ];
        }

        return $chart_data;
    }


    public function findStatsParFormation(Question $question,FormationRepository $formationRepository,array $colors) : array
    {
        $formationsPossibles=$formationRepository->findAll();  // initialise un tableau ou toutes les formations sont repertoriés
        $formations=[] ;
        foreach ( $formationsPossibles as $formation){ // initialisation des différents labels
            $intitule = $formation->getNomFormation();
            $formations[]=$intitule;
        }

        $toutesLesReponses = $question->getReponses(); // une collection ou toutes les objets reponses sont repertorié
        $reponsesPossibles=[];
        foreach ( $toutesLesReponses as  $rep){
            $reponsesPossibles[]=$rep->getLaReponse();
        }

        // Initialize the array to count the responses for each genre
        $responsesByFormation = [];
        foreach ($formations as $formation) {
            $responsesByFormation[$formation] = array_fill(0, count($question->getReponses()), 0);
        }

        // Loop over each user sondage reponse to count the responses
        foreach ($question->getUserSondageReponses() as $userSondageReponse) {
            $userFormation = $userSondageReponse->getUserSondageResult()->getSonde()->getFormation()->getNomFormation();
            if (in_array($userFormation, $formations)) {
                $reponsesSonde = $userSondageReponse->getReponses();
                foreach ($reponsesSonde as $reponseSonde) {
                    $reponseIndex = array_search($reponseSonde->getLaReponse(), $reponsesPossibles);
                    if ($reponseIndex !== false) {
                        $responsesByFormation[$userFormation][$reponseIndex]++;
                    }
                }
            }
        }

        // Create the chart data array with dynamic labels
        $chart_data = [
            'labels' => $reponsesPossibles,
            'datasets' => [],
        ];

        // Loop over each genre to add the data to the chart data array
        foreach ($formations as $formation) {
            $chart_data['datasets'][] = [
                'label' => $formation,
                'data' => $responsesByFormation[$formation],
                'backgroundColor'=> $colors[rand(0, 50)],
            ];
        }

        return $chart_data;
    }


    public function findStatsParTrancheAge(Question $question,array $colors) : array
    {
        $toutesLesReponses = $question->getReponses(); // une collection ou toutes les objets reponses sont repertorié
        $reponsesPossibles=[];
        foreach ( $toutesLesReponses as  $rep){
            $reponsesPossibles[]=$rep->getLaReponse();
        }

        $ages = ['0-17 ans ','18-24 ans ','25-30 ans', '40 et +']; // les differents labels

        $intervals = [
            ['min' => 0, 'max' => 18],
            ['min' => 19, 'max' => 24],
            ['min' => 25, 'max' => 30],
            ['min' => 40, 'max' => 100],
        ];

        // Initialize the array to count the responses for each age group
        $responsesByAge = [];
        foreach ($ages as $age) {
            $responsesByAge[$age] = array_fill(0, count($question->getReponses()), 0);
        }

        // Loop over each user sondage response to count the responses
        foreach ($question->getUserSondageReponses() as $userSondageReponse) {
            $dateNaissance = $userSondageReponse->getUserSondageResult()->getSonde()->getDateNaissance();
            $ageUser=$dateNaissance->diff(new DateTime())->y;
            foreach ($intervals as $i => $interval) {
                if ($ageUser >= $interval['min'] && $ageUser <= $interval['max']) {
                    $ageLabel = $ages[$i];
                    $reponsesSonde = $userSondageReponse->getReponses();
                    foreach ($reponsesSonde as $reponseSonde) {
                        $reponseIndex = array_search($reponseSonde->getLaReponse(), $reponsesPossibles);
                        if ($reponseIndex !== false) {
                            $responsesByAge[$ageLabel][$reponseIndex]++;
                        }
                    }
                    break;
                }
            }
        }

        // Create the chart data array with dynamic labels
        $chart_data = [
            'labels' => $reponsesPossibles,
            'datasets' => [],
        ];

        // Loop over each age group to add the data to the chart data array
        foreach ($ages as $age) {
            $chart_data['datasets'][] = [
                'label' => $age,
                'data' => $responsesByAge[$age],
                'backgroundColor'=> $colors[rand(0, 50)],
            ];
        }

        return $chart_data;
    }







// fin de la classe
}

