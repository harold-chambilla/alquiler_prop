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
    public function pdf(Recibo $recibo, LecturaRepository $lecturaRepository)
    {
        $lecturacuartoActual = null;
        $lecturaescaleraactual = null;
        $contrato = $recibo->getContratoId();

        $detallesRecibo = $recibo->getReciboDetalleConsumos();
        foreach($detallesRecibo as $detalleRecibo){
            if ($detalleRecibo->getRdcTipo()==1){
                $lecturacuartoActual = $detalleRecibo->getRdcConsumo();
            }else{
                $lecturaescaleraactual = $detalleRecibo->getRdcConsumo();
            }
        }
        
        $html = $this->renderView('crm/recibo/pdf.html.twig', [
            'recibo' => $recibo,
            'consumoLuz' => $lecturacuartoActual,
            'consumoEscalera' => $lecturaescaleraactual,
            'contrato' => $contrato,

        ]);

        $pdfContent = $this->pdfGenerator->getOutputFromHtml($html);
        return new Response(
            $pdfContent,
            200,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'inline; filename="recibo_0'.$recibo->getId().'"',
            ]
        );
    }
}
