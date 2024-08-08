<?php

namespace App\Repository;

use App\Entity\DetalleConsumoLuz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DetalleConsumoLuz>
 *
 * @method DetalleConsumoLuz|null find($id, $lockMode = null, $lockVersion = null)
 * @method DetalleConsumoLuz|null findOneBy(array $criteria, array $orderBy = null)
 * @method DetalleConsumoLuz[]    findAll()
 * @method DetalleConsumoLuz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DetalleConsumoLuzRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DetalleConsumoLuz::class);
    }

    //    /**
    //     * @return DetalleConsumoLuz[] Returns an array of DetalleConsumoLuz objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?DetalleConsumoLuz
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
