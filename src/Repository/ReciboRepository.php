<?php

namespace App\Repository;

use App\Entity\Recibo;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Recibo>
 *
 * @method Recibo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recibo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recibo[]    findAll()
 * @method Recibo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReciboRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recibo::class);
    }

    public function findByUsuario($usuario): QueryBuilder
    {
        return $this->createQueryBuilder('re')
        ->join('re.contrato_id', 'c')
        ->join('c.piso_id', 'p')
        ->join('p.residencia_id', 'r')
        ->andWhere('r.usuario = :usuario')
        ->setParameter('usuario', $usuario);
    }

    public function findPreviousRecibo($recibo)
    {
        return $this->createQueryBuilder('r')
        ->andWhere('r.contrato_id = :contrato')
        ->andWhere('r.re_fecha_emision < :fechaActual')
        ->setParameter('contrato', $recibo->getContratoId())
        ->setParameter('fechaActual', $recibo->getReFechaEmision())
        ->orderBy('r.re_fecha_emision', 'DESC')
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();
    }

//    /**
//     * @return Recibo[] Returns an array of Recibo objects
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

//    public function findOneBySomeField($value): ?Recibo
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
