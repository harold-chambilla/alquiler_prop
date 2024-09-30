<?php

namespace App\Repository;

use App\Entity\Recibo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

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
    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Recibo::class);
        $this->entityManager = $entityManager;
    }

    public function obtenerPagosPorMes(): array
    {
        $recibos = $this->createQueryBuilder('x')
        ->select('x.re_fecha_emision, x.re_pago_total')
        ->where('x.re_estado = 1')
        // ->groupBy('mesFormateado')
        ->orderBy('x.re_fecha_emision', 'ASC')
        ->getQuery()
        ->getResult();

        $pagosPorMes = [];

        foreach ($recibos as $recibo) {
            $fecha = $recibo['re_fecha_emision'];
            $mesFormateado = $fecha->format('d M Y');

            if (!isset($pagosPorMes[$mesFormateado])) {
                $pagosPorMes[$mesFormateado] = 0;
            }
            $pagosPorMes[$mesFormateado] += $recibo['re_pago_total'];
        }

        $prices = [];
        $dates = [];

        foreach ($pagosPorMes as $mes => $sumaPagos) {
            $dates[] = $mes;
            $prices[] = $sumaPagos;
        }

        return [
            'series' => [
                'prices' => $prices,
                'dates' => $dates
            ]
        ];
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
