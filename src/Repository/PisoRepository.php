<?php

namespace App\Repository;

use App\Entity\Piso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Piso>
 *
 * @method Piso|null find($id, $lockMode = null, $lockVersion = null)
 * @method Piso|null findOneBy(array $criteria, array $orderBy = null)
 * @method Piso[]    findAll()
 * @method Piso[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PisoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Piso::class);
    }

    //    /**
    //     * @return Piso[] Returns an array of Piso objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Piso
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
