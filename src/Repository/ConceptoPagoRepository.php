<?php

namespace App\Repository;

use App\Entity\ConceptoPago;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ConceptoPago>
 *
 * @method ConceptoPago|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConceptoPago|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConceptoPago[]    findAll()
 * @method ConceptoPago[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConceptoPagoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConceptoPago::class);
    }

//    /**
//     * @return ConceptoPago[] Returns an array of ConceptoPago objects
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

//    public function findOneBySomeField($value): ?ConceptoPago
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
