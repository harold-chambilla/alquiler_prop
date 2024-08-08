<?php

namespace App\Controller\Alquiler;

use App\Entity\Contrato;
use App\Repository\ContratoRepository;
use App\Service\ContratoPdfGenerator;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContratoController extends AbstractController
{
    // #[Route('/alquiler/contrato', name: 'app_alquiler_contrato')]
    // public function index(): Response
    // {
    //     return $this->render('alquiler/contrato/index.html.twig', [
    //         'controller_name' => 'ContratoController',
    //     ]);
    // }
    private $pdfGenerator;

    public function __construct(Pdf $pdfGenerator)
    {
        $this->pdfGenerator = $pdfGenerator;
    }
    #[Route('/contrato/{id}/pdf', name: 'contrato_pdf')]
    public function pdf(Contrato $contrato): Response
    {

        $residencia = $contrato->getResidenciaId();
        $pisos = $contrato->getPisoId();
        $arrendatario = $contrato->getArrendatarioId();
        // Renderizar la vista HTML (ajusta la vista según lo que necesitas)
        $html = $this->renderView('alquiler/contrato/pdf.html.twig', [
            'contrato' => $contrato,
            'residencia' => $residencia,
            'piso' => $pisos,
            'arrendatario' => $arrendatario
        ]);

        // Generar el PDF
        $pdfContent = $this->pdfGenerator->getOutputFromHtml($html);

        // Devolver el PDF como respuesta en línea (no descarga automática)
        return new Response(
            $pdfContent,
            200,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'inline; filename="contrato.pdf"',
            ]
        );
    }
}
