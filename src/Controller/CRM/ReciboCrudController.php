<?php

namespace App\Controller\CRM;

use DateTime;
use DateTimeZone;
use App\Entity\Recibo;
use App\Entity\Lectura;
use App\Entity\Contrato;
use Doctrine\ORM\QueryBuilder;
use App\Form\ReciboConceptoPagoType;
use App\Entity\ReciboDetalleConsumo;
use App\Entity\Residencia;
use App\Repository\LecturaRepository;
use App\Repository\ContratoRepository;
use App\Repository\ReciboRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use Symfony\Component\HttpFoundation\RequestStack;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ReciboCrudController extends AbstractCrudController
{
    private $requestStack;
    private $contratoActual;
    private $contratoRepository;
    private $lecturaRepository;
    private $reciboRepository;

    public function __construct(RequestStack $requestStack, ContratoRepository $contratoRepository, LecturaRepository $lecturaRepository, ReciboRepository $reciboRepository)
    {
        $this->requestStack = $requestStack;
        $this->contratoRepository = $contratoRepository;
        $this->lecturaRepository = $lecturaRepository;
        $this->reciboRepository = $reciboRepository;
    }
    public static function getEntityFqcn(): string
    {
        return Recibo::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Recibo')
            ->setEntityLabelInPlural('Recibos')
            ->setPageTitle(Crud::PAGE_INDEX, 'Gestión de Recibos')
            ->setDefaultSort(['re_fecha_emision' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        
        if ($pageName === Crud::PAGE_INDEX) {
            return [
                TextField::new('re_codigo', 'Código'),
                TextField::new('re_pago_total', 'Total a pagar'),
                DateField::new('re_fecha_emision', 'Fecha de emisión'),
                AssociationField::new('contrato_id', 'Arrendatario')
                ->formatValue(function ($value, $entity) {
                    return $entity->getFormattedArrendatario();
                }),
                ChoiceField::new('re_estado', 'Estado recibo')
                    ->setChoices([
                        'Pagado' => 1,
                        'Pendiente' => 0,
                    ])
            ];
        }else{
            $lecturaLuzCuartoActual = null;
            $lecturaLuzEscaleraActual = null;
            
            if ($pageName === Crud::PAGE_EDIT && $this->getContext()->getEntity()) {
                $recibo = $this->getContext()->getEntity()->getInstance();
                $arrendatarioData = $recibo->getFormattedArrendatario();
                $detallesConsumo = $recibo->getReciboDetalleConsumos();
                if ($detallesConsumo) {
                    foreach ($detallesConsumo as $detalleConsumo){
                        if ($detalleConsumo->getRdcTipo() == 1){
                            $lecturaLuzCuartoActual = $this->lecturaRepository->find($detalleConsumo->getLecActId());
                            $lecturaLuzCuartoActual = $lecturaLuzCuartoActual->getLelDato();
                        }else{
                            $lecturaLuzEscaleraActual = $this->lecturaRepository->find($detalleConsumo->getLecActId());
                            $lecturaLuzEscaleraActual = $lecturaLuzEscaleraActual->getLelDato();
                        }
                    }
                }
            }else{
                $arrendatarioData = $this->getArrendatarioDatos();
            }
            return [
                TextField::new('Arrendatario')
                    ->setFormTypeOption('data', $arrendatarioData)
                    ->setFormTypeOption('mapped', false)
                    ->setFormTypeOption('disabled', true),
                NumberField::new('lecturaLuzCuartoActual', 'Lectura actual de luz de cuarto')
                    ->setFormTypeOption('data', $lecturaLuzCuartoActual)
                    ->setRequired(true)
                    ->setFormTypeOption('mapped', false),
                NumberField::new('lecturaLuzEscaleraActual', 'Lectura actual de luz de escalera')
                    ->setFormTypeOption('data', $lecturaLuzEscaleraActual)
                    ->setRequired(true)
                    ->setFormTypeOption('mapped', false),
                FormField::addFieldset('Conceptos de pago'),
                CollectionField::new('reciboConceptoPagos', 'Conceptos de Pago')
                    ->allowAdd()
                    ->allowDelete()
                    ->setEntryType(ReciboConceptoPagoType::class)
                    ->setFormTypeOptions([
                        'by_reference' => false,
                        'label' => false,
                    ])
                    ->formatValue(function ($value, $entity) {
                        return $entity->getConceptoPagoId()->getCopNombre();
                    })
                    ->hideOnIndex(),
            ];
        }
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $usuario = $this->getUser();
        return $this->reciboRepository->findByUsuario($usuario);
    }

    public function createEntity(string $entityFqcn)
    {   
        
        $recibo = new Recibo();
        $recibo->setReEstado(0);
        return $recibo;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {

        $recibo = $entityInstance;
        $contrato = $this->getContratoActual();
        $medidores = $contrato->getPisoId()->getMedidors();
        $fecharecibo = $this->obtenerfechamensual();
        $medidor = null;
        foreach($medidores as $medidors){
            if ($medidors->getMelTipo() == "Eléctrico"){
                $medidor = $medidors;
                break;
            }
        }
        $request = $this->requestStack->getCurrentRequest();
        $lecturaLuzCuartoActual = $request->get('Recibo')['lecturaLuzCuartoActual'] ?? null; //añadir
        $lecturaLuzEscaleraActual = $request->get('Recibo')['lecturaLuzEscaleraActual'] ?? null;
        if (((is_numeric($lecturaLuzCuartoActual)) == false) || ((is_numeric($lecturaLuzEscaleraActual)) == false)) {
            $lecturaLuzCuartoActual = (float) $lecturaLuzCuartoActual; 
            $lecturaLuzEscaleraActual = (float) $lecturaLuzEscaleraActual;  
        }
        $residencia = $medidor->getPiso()->getResidenciaId();
        $lecturaLuzEscaleraActual = $this->ObtenerDeudaLuzEscalera($residencia, $lecturaLuzEscaleraActual);

        if (($lecturaLuzCuartoActual === null) || ($lecturaLuzEscaleraActual === null)) {
            throw new \InvalidArgumentException('La lectura actual es requerida.'); //añadir
        }

        $tiposLectura = [
            1 => 'Tipo 1', // Luz de cuarto
            2 => 'Tipo 2', // Luz de escalera
        ];
        
        $totalConsumo = 0;
        
        foreach ($tiposLectura as $tipo => $descripcion) {
            // Obtener la última lectura del tipo correspondiente
            $ultimaLectura = $this->lecturaRepository->findOneBy(
                ['medidor_id' => $medidor, 'lel_tipo' => $descripcion],
                ['lel_fecha' => 'DESC']
            );
        
            // Si existe una lectura anterior, usamos su dato, si no, es 0
            $lecturaAnterior = $ultimaLectura ? $ultimaLectura->getLelDato() : 0;
            
            $lecturaActual = $lecturaLuzCuartoActual;
            if ($descripcion == 'Tipo 2'){
                $lecturaActual = $lecturaLuzEscaleraActual;
            }
            $consumo = abs($lecturaActual - $lecturaAnterior);
        
            // Crear nueva lectura
            $lectura = new Lectura();
            $lectura->setMedidorId($medidor);
            $lectura->setLelDato($lecturaActual);
            $lectura->setLelTipo($descripcion); // Establecer el tipo de lectura
            $lectura->setLelEstado(true);
            $lectura->setLelFecha($fecharecibo);
            $entityManager->persist($lectura);

            foreach ($recibo->getReciboConceptoPagos() as $reciboConceptoPago) {
                $reciboConceptoPago->setReciboId($recibo);
                $reciboConceptoPago->setRcpFechaDigitacion(new \DateTime());
                $conceptoPago = $reciboConceptoPago->getConceptoPagoId();
                $conceptoPago->setCopEstado(true);
                $entityManager->persist($conceptoPago);
                $entityManager->persist($reciboConceptoPago);
            }
    
            $entityManager->persist($recibo);
            $entityManager->flush();
            // Crear detalle de consumo para el recibo
            $detalleConsumo = new ReciboDetalleConsumo();
            $detalleConsumo->setRecibo($recibo);
            $detalleConsumo->setRdcConsumo($consumo);
            $detalleConsumo->setRdcSubtotal($consumo * 1); // Ajusta el cálculo si el costo por unidad es distinto
            $detalleConsumo->setRdcTipo($tipo); // Establecer el tipo del consumo (1 para cuarto, 2 para escalera)
            $detalleConsumo->setLectAntId($ultimaLectura ? $ultimaLectura->getId() : 0);
            $detalleConsumo->setLecActId($lectura->getId());
            $detalleConsumo->setRdcEstado(true);
            $entityManager->persist($detalleConsumo);
            
            // Sumar el subtotal al total del consumo
            $totalConsumo += $detalleConsumo->getRdcSubtotal();
        }
        
        // Calcular el total del recibo (consumo total + alquiler mensual + agua)
        $totalrecibo = $totalConsumo + $contrato->getCoAlquilerMensual() + $contrato->getCoAgua();
        $recibo->setRePagoTotal($totalrecibo);
        $recibo->setContratoId($contrato);
        
        // Guardar en la base de datos
        // $entityManager->flush();

        //Añadido por kheyvin para ReciboConceptoPago
        
        $recibo->setReCodigo('REC_'.$recibo->getId());
        $recibo->setReFechaEmision($fecharecibo);
        $entityManager->flush();
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $recibo = $entityInstance;
        $contrato = $recibo->getContratoId();
        $request = $this->requestStack->getCurrentRequest();
        $residencia = $contrato->getPisoId()->getResidenciaId();
        // Obtener lecturas de luz de cuarto y luz de escalera del request
        $lecturaLuzCuartoActual = $request->get('Recibo')['lecturaLuzCuartoActual'] ?? null;
        $lecturaLuzEscaleraActual = $request->get('Recibo')['lecturaLuzEscaleraActual'] ?? null;

        if ($lecturaLuzCuartoActual === null || $lecturaLuzEscaleraActual === null) {
            throw new \InvalidArgumentException('Las lecturas actuales son requeridas.');
        }

        // Asegurar que las lecturas sean numéricas
        if (!is_numeric($lecturaLuzCuartoActual) || !is_numeric($lecturaLuzEscaleraActual)) {
            throw new \InvalidArgumentException('Las lecturas deben ser valores numéricos.');
        }

        // Convertir las lecturas a float
        $lecturaLuzCuartoActual = (float) $lecturaLuzCuartoActual;
        $lecturaLuzEscaleraActual = (float) $lecturaLuzEscaleraActual;
        $lecturaLuzEscaleraActual = $this->ObtenerDeudaLuzEscalera($residencia, $lecturaLuzEscaleraActual);
        // Array con las lecturas y tipos
        $lecturas = [
            1 => 'Tipo 1', // Luz de cuarto
            2 => 'Tipo 2', // Luz de escalera
        ];

        $totalRecibo = 0; // Inicializamos el total del recibo

        // Procesar cada tipo de detalle de consumo
        foreach ($lecturas as $tipo => $descripcion) {
            // Buscar el detalle de consumo para el tipo correspondiente
            $detalleConsumo = $recibo->getReciboDetalleConsumos()->filter(function ($detalle) use ($tipo) {
                return $detalle->getRdcTipo() === $tipo;
            })->first();

            $lecturaActual = $lecturaLuzCuartoActual;
            if ($descripcion == 'Tipo 2'){
                $lecturaActual = $lecturaLuzEscaleraActual;
            }

            if ($detalleConsumo) {
                $lecturaid = $detalleConsumo->getLecActId();
                if ($lecturaid) {
                    // Actualizar la lectura actual
                    $lectura = $this->lecturaRepository->find($lecturaid);
                    if ($lectura) {
                        $lectura->setLelDato($lecturaActual);
                        $lectura->setLelFecha(new \DateTime());
                        $entityManager->persist($lectura);
                    }

                    // Obtener la lectura anterior
                    $lecturaAnteriorid = $detalleConsumo->getLectAntId();
                    $lecturaAnterior = $this->lecturaRepository->find($lecturaAnteriorid);
                    $lecturaAnteriorDato = $lecturaAnterior ? $lecturaAnterior->getLelDato() : 0;

                    // Recalcular el consumo y el subtotal
                    $consumo = abs($lecturaActual - $lecturaAnteriorDato);
                    $detalleConsumo->setRdcConsumo($consumo);
                    $detalleConsumo->setRdcSubtotal($consumo * 1); // Multiplicar por el costo unitario
                    $entityManager->persist($detalleConsumo);
                    $entityManager->flush();

                    // Sumar el subtotal al total del recibo
                    $totalRecibo += $detalleConsumo->getRdcSubtotal();
                }
            }
        }

        // Actualizar el total del recibo
        $totalRecibo += $contrato->getCoAlquilerMensual() + $contrato->getCoAgua();
        $recibo->setRePagoTotal($totalRecibo);
        $recibo->setContratoId($contrato);

        // Guardar cambios en la base de datos
        $entityManager->persist($recibo);
        $entityManager->flush();
    }


    private function obtenerfechamensual():DateTime
    {
        $contrato = $this->getContratoActual();
        $fechainiciocontrato = $contrato->getCoFechaIngreso();
        if($fechainiciocontrato !== null){
            $fechamensual = date_modify($fechainiciocontrato, '+1 month');
        }
        
        $reciboexistente = $contrato->getRecibos();
        if(count($reciboexistente)>=1){
           $reciboMasReciente = $reciboexistente->last();
           $fechaultima = $reciboMasReciente->getReFechaEmision();
           $fechamensual = (clone $fechaultima)->modify('+1 month');
        }
        return $fechamensual;
    }

    private function getContratoActual(): ?Contrato
    {
        if ($this->contratoActual === null) {
            $request = $this->requestStack->getCurrentRequest();
            $contratoId = $request->query->get('contrato_id');
            $this->contratoActual = $contratoId ? $this->contratoRepository->find($contratoId) : null;
        }
        return $this->contratoActual;
    }

    private function getArrendatarioDatos(): string
    {
        $contrato = $this->getContratoActual();
        if ($contrato && ($arrendatario = $contrato->getArrendatarioId())) {
            return sprintf(
                '%s %s (DNI: %s)',
                $arrendatario->getAoNombres(),
                $arrendatario->getAoApellidos(),
                $arrendatario->getAoCedulaIdentidad()
            );
        }
        return 'No disponible';
    }

    public function configureActions(Actions $actions): Actions
    {
        $reciboPDF = Action::new('reciboPdf', 'ver Recibo')
            ->linkToRoute('recibo_pdf', function (Recibo $recibo) {
                return [
                    'id' => $recibo->getId(),
                ];
            })
            ->setIcon('fa fa-file-invoice')
            ->setHtmlAttributes(['target' => '_blank']);

            $reciboanteriorPDF = Action::new('reciboAntPdf', 'Ver Recibo Anterior')
            ->linkToRoute('recibo_pdf', function (Recibo $recibo) {
                // Buscar el recibo anterior
                $reciboanterior = $this->reciboRepository->findPreviousRecibo($recibo);
                
                // Verificar si existe el recibo anterior
                if ($reciboanterior == null) {
                    $contrato = $this->getContratoActual();
                    $ultimoRecibo = $this->contratoRepository->findLastReciboByContrato($contrato);
                    return [
                        'id' => $ultimoRecibo->getId(),  // Pasar el ID del recibo anterior
                    ];
                }
        
                // Si no hay recibo anterior, puedes devolver un array vacío o manejarlo de otra manera
                return [];
            })
            ->setIcon('fa fa-file-invoice')
            ->setHtmlAttributes(['target' => '_blank']);

            $request = $this->requestStack->getCurrentRequest();
            $crudPage = $request->query->get('crudAction');
            $entityId = $request->query->get('entityId');

            if($crudPage === Crud::PAGE_NEW){
                $contrato = $this->getContratoActual();
                if ($contrato && !$contrato->getRecibos()->isEmpty()) {
                    $actions = $actions->add(Crud::PAGE_NEW, $reciboanteriorPDF);
            }
            }

            if ($crudPage === Crud::PAGE_EDIT && $entityId) {
                $recibo = $this->reciboRepository->find($entityId);
                if ($recibo && $this->reciboRepository->findPreviousRecibo($recibo)) {
                    $actions = $actions->add(Crud::PAGE_EDIT, $reciboanteriorPDF);
                }
            }

        return $actions
            ->add(Crud::PAGE_INDEX, $reciboPDF)
            ->add(Crud::PAGE_DETAIL, $reciboPDF)
            ->remove(Crud::PAGE_INDEX, Action::NEW);
    }

    private function ObtenerDeudaLuzEscalera(Residencia $residencia, float $lectura){
        $arrendatarios = 0;
        $pisos = $residencia->getPisos();
        foreach($pisos as $piso){
            if ($piso->getContratos()){
                foreach($piso->getContratos() as $contrato){
                    if ($contrato->isCoEstado()){
                        $arrendatarios ++;
                    }
                }
            }
        }
        $lectura = $lectura / $arrendatarios;
        return $lectura;
    }

}
