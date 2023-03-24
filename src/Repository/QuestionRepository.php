<?php

namespace App\Repository;

use App\Entity\Question;
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
    public function findStatsGlobales(Question $question) : array
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
                ],
            ],
        ];
        //$question->addStatQuestion();
        return $chart_data;
    }



// fin de la classe
}

