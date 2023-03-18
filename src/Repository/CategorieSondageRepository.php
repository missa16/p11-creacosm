<?php

namespace App\Repository;

use App\Entity\CategorieSondage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategorieSondage>
 *
 * @method CategorieSondage|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieSondage|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieSondage[]    findAll()
 * @method CategorieSondage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieSondageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategorieSondage::class);
    }

    public function save(CategorieSondage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CategorieSondage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CategorieSondage[] Returns an array of CategorieSondage objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CategorieSondage
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
