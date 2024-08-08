<?php

namespace App\Repository;

use App\Entity\Medidor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Medidor>
 *
 * @method Medidor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Medidor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Medidor[]    findAll()
 * @method Medidor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MedidorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Medidor::class);
    }

    //    /**
    //     * @return Medidor[] Returns an array of Medidor objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Medidor
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
