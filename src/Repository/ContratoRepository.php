<?php

namespace App\Repository;

use App\Entity\Recibo;
use App\Entity\Contrato;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Contrato>
 *
 * @method Contrato|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contrato|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contrato[]    findAll()
 * @method Contrato[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContratoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contrato::class);
    }

    public function findByUsuario($usuario): QueryBuilder
    {
        return $this->createQueryBuilder('c')
        ->join('c.piso_id', 'p')
        ->join('p.residencia_id','r')
        ->andWhere('r.usuario = :usuario')
        ->setParameter('usuario', $usuario);
    }

    public function findLastReciboByContrato(Contrato $contrato): ?Recibo
{
    return $this->createQueryBuilder('c')
        ->select('r')
        ->from(Recibo::class, 'r')
        ->where('r.contrato_id = :contrato')
        ->setParameter('contrato', $contrato)
        ->orderBy('r.re_fecha_emision', 'DESC') // Ordenar por fecha de emisión en orden descendente
        ->setMaxResults(1) // Limitar a 1 resultado (el más reciente)
        ->getQuery()
        ->getOneOrNullResult(); // Devolver el recibo o null si no existe
}
    //    /**
    //     * @return Contrato[] Returns an array of Contrato objects
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

    //    public function findOneBySomeField($value): ?Contrato
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
