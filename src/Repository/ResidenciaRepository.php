<?php

namespace App\Repository;

use App\Entity\Residencia;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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

    

    public function findByUsuario($usuario): QueryBuilder
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.usuario = :usuario')
            ->setParameter('usuario', $usuario);
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
