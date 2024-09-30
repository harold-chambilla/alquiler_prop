<?php

namespace App\Repository;

use App\Entity\Arrendatario;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Arrendatario>
 *
 * @method Arrendatario|null find($id, $lockMode = null, $lockVersion = null)
 * @method Arrendatario|null findOneBy(array $criteria, array $orderBy = null)
 * @method Arrendatario[]    findAll()
 * @method Arrendatario[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArrendatarioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Arrendatario::class);
    }

    public function findByUsuario($usuario): QueryBuilder
    {
        return $this->createQueryBuilder('a')
        ->join('a.contratos', 'c')
        ->join('c.piso_id', 'p')
        ->join('p.residencia_id', 'r')
        ->andWhere('r.usuario = :usuario')
        ->setParameter('usuario', $usuario);
    }

    public function countTitularesYNoTitulares(): array
    {
        // Contar titulares
        $titulares = $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->where('a.ao_tipo = :tipo')
            ->setParameter('tipo', 'titular')
            ->getQuery()
            ->getSingleScalarResult();

        // Contar no titulares
        $noTitulares = $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->where('a.ao_tipo = :tipo')
            ->setParameter('tipo', 'no titular')
            ->getQuery()
            ->getSingleScalarResult();

        // Devolver el resultado como [titulares, no_titulares]
        return [(int) $titulares, (int) $noTitulares];
    }

    //    /**
    //     * @return Arrendatario[] Returns an array of Arrendatario objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Arrendatario
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
