<?php

namespace App\Repository;

use App\Entity\ReciboConceptoPago;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReciboConceptoPago>
 *
 * @method ReciboConceptoPago|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReciboConceptoPago|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReciboConceptoPago[]    findAll()
 * @method ReciboConceptoPago[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReciboConceptoPagoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReciboConceptoPago::class);
    }

    //    /**
    //     * @return ReciboConceptoPago[] Returns an array of ReciboConceptoPago objects
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

    //    public function findOneBySomeField($value): ?ReciboConceptoPago
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
