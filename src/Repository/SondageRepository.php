<?php

namespace App\Repository;

use App\Entity\Sondage;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Collection;
use PhpParser\Node\Expr\Array_;

/**
 * @extends ServiceEntityRepository<Sondage>
 *
 * @method Sondage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sondage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sondage[]    findAll()
 * @method Sondage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SondageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sondage::class);
    }

    public function save(Sondage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sondage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Sondage[] Returns an array of Sondage objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sondage
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function findAgeSondes(Sondage $sondage) : array
    {
        $results = $sondage->getLesSondes();
        $ages=[];  // initialise un tableau ou tous les ages sont repertoriés
        $labels = ['0-17 ans ','18-24 ans ','25-30 ans', '40 et +']; // les differents labels
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
        $chart_data = [
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $counts,
                ],
            ],
        ];
        return $chart_data;  // indice 0 : les différents interval d'age, indice 1 le compte de personne pour chaque intervalle
    }

    public function findFormationSondes(Sondage $sondage,FormationRepository $formationRepository) : array
    {
        $results = $sondage->getLesSondes();
        $sondesFormation = [];  // un tableau ou toutes les formations des sondes sont repertories
        foreach ( $results as $result){
            $sonde= $result->getSonde();
            $sondeF = $sonde->getFormation();
            $sondesFormation[]=$sondeF;
        }

        $formations=$formationRepository->findAll();  // initialise un tableau ou toutes les formations sont repertoriés
        $labels=[] ;
        foreach ( $formations as $formation){ // initialisation des différents labels
            $intitule = $formation->getNomFormation();
            $labels[]=$intitule;
        }

        $counts = array_fill(0, count($formations), 0);
        foreach ($sondesFormation as $sondeFormation) {
            foreach ($formations as $i => $formation) {
                if ($sondeFormation == $formation) {
                    $counts[$i]++;
                    break;
                }
            }
        }

        $chart_data = [
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $counts,
                ],
            ],
        ];
        return $chart_data;

    }

    public function findGenreSondes(Sondage $sondage) : array
    {
        $results = $sondage->getLesSondes();
        $genres=[];  // initialise un tableau ou tous les ages sont repertoriés
        $labels = ['Femme','Homme']; // les différents labels
        foreach ( $results as $result){
            $sonde= $result->getSonde();
            $genreSonde= $sonde->getGenre();
            $genres[]=$genreSonde;
        }
        $intervals = [
            ['min' => 0, 'max' => 18],
            ['min' => 19, 'max' => 24],
            ['min' => 25, 'max' => 30],
            ['min' => 40, 'max' => 100],
        ];

        $counts = array_fill(0, count($intervals), 0);
        foreach ($genres as $genreSonde) {
            foreach ($labels as $i => $label) {
                if ($genreSonde == $label) {
                    $counts[$i]++;
                    break;
                }
            }
        }
        $chart_data = [
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $counts,
                ],
            ],
        ];
        return $chart_data;  // indice 0 : les différents interval d'age, indice 1 le compte de personne pour chaque intervalle
    }

    public function findAllSondageEnCours($user): array
    {

        $sondages = $this->findAll();
        $now = new \DateTimeImmutable();
        $sondagesEnCours= [];
        foreach ($sondages as $sondage){
            $usersSondageResult = $sondage->getLesSondes();
            $sondes=[];
            foreach ($usersSondageResult as $userSondageResult) {
                $sondes[]=$userSondageResult->getSonde();
            }
            if ( !(in_array($user,$sondes)) && $sondage->getDateFin()>$now  ){
                $sondagesEnCours[]=$sondage;
            }
        }
        return $sondagesEnCours;
    }




}

