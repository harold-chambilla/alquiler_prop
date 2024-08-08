<?php

namespace App\Repository;

use App\Entity\Residencia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Residencia>
 *
 * @method Residencia|null find($id, $lockMode = null, $lockVersion = null)
 * @method Residencia|null findOneBy(array $criteria, array $orderBy = null)
 * @method Residencia[]    findAll()
 * @method Residencia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResidenciaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Residencia::class);
    }

    //    /**
    //     * @return Residencia[] Returns an array of Residencia objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Residencia
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
