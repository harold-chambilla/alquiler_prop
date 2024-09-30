<?php

namespace App\Controller\CRM;

use App\Entity\Recibo;
use App\Repository\LecturaRepository;
use Knp\Snappy\Pdf;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReciboController extends AbstractController
{
    private $pdfGenerator;

    public function __construct(Pdf $pdfGenerator)
    {
        $this->pdfGenerator = $pdfGenerator;
    }
    #[Route('/recibo/{id}/pdf', name: 'recibo_pdf')]
    public function pdf(Recibo $recibo, LecturaRepository $lecturaRepository): Response
    {
        $lecturacuartoActual = null;
        $lecturaescaleraactual = null;
        $contrato = $recibo->getContratoId();

        $detallesRecibo = $recibo->getReciboDetalleConsumos();
        foreach($detallesRecibo as $detalleRecibo){
            if ($detalleRecibo->getRdcTipo()==1){
                $lecturacuartoActual = $lecturaRepository->find($detalleRecibo->getLecActId());
            }else{
                $lecturaescaleraactual = $lecturaRepository->find($detalleRecibo->getLecActId());
            }
        }
        
        return $this->render('crm/recibo/pdf.html.twig', [
            'recibo' => $recibo,
            'consumoLuz' => $lecturacuartoActual,
            'consumoEscalera' => $lecturaescaleraactual,
            'contrato' => $contrato,

        ]);
    }
}
