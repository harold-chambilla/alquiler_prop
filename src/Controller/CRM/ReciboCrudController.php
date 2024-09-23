<?php

namespace App\Controller\CRM;

use DateTime;
use DateTimeZone;
use App\Entity\Recibo;
use App\Entity\Lectura;
use App\Entity\Contrato;
use App\Entity\ReciboDetalleConsumo;
use App\Repository\LecturaRepository;
use App\Repository\ContratoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
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
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ReciboCrudController extends AbstractCrudController
{
    private $entityManager;
    private $requestStack;
    private $contratoActual;
    private $arrendatarioactual;
    private $contratoRepository;
    private $lecturaRepository;
    

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack, ContratoRepository $contratoRepository, LecturaRepository $lecturaRepository)
    {
        $this->requestStack = $requestStack;
        $this->entityManager = $entityManager;
        $this->contratoRepository = $contratoRepository;
        $this->lecturaRepository = $lecturaRepository;
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
                    if ($entity && $entity->getContratoId()) {
                        return sprintf(
                            $contrato = $entity->getContratoId(),
                            '%s %s (DNI: %s)',
                            $contrato->getArrendatarioId()->getAoNombres(),
                            $contrato->getArrendatarioId()->getAoApellidos(),
                            $contrato->getArrendatarioId()->getAoCedulaIdentidad()
                        );
                    }
                    return '';
                }),
                ChoiceField::new('re_estado', 'Estado recibo')
                    ->setChoices([
                        'Pagado' => 1,
                        'Pendiente' => 0,
                    ])
            ];
        }else{
            $lecturaLuzActual = null;
            if ($pageName === Crud::PAGE_EDIT && $this->getContext()->getEntity()) {
                $recibo = $this->getContext()->getEntity()->getInstance();
                $detalleConsumo = $recibo->getReciboDetalleConsumos()[0];  
                if ($detalleConsumo) {
                    $lecturaLuzActual = $this->lecturaRepository->find($detalleConsumo->getLecActId());
                    $lecturaLuzActual = $lecturaLuzActual->getLelDato();
                }
            }else{
                $arrendatarioData = $this->getArrendatarioDatos();
            }
            return [
                TextField::new('Arrendatario')
                    ->setFormTypeOption('data', $this->getArrendatarioDatos())
                    ->setFormTypeOption('mapped', false),
                NumberField::new('lecturaLuzActual', 'Lectura de luz actual')
                    ->setFormTypeOption('data', $lecturaLuzActual)
                    ->setRequired(true)
                    ->setFormTypeOption('mapped', false),
                FormField::addFieldset('Conceptos de pago'),
                CollectionField::new('reciboConceptoPagos', '')
                    ->useEntryCrudForm(ReciboConceptoPagoCrudController::class)
                    ->hideOnIndex(),
            ];
        }

        
    }

    public function createEntity(string $entityFqcn)
    {
        date_default_timezone_set('America/Lima');
        $recibo = new Recibo();
        $recibo->setReEstado(0);
        $recibo->setReFechaEmision(new DateTime());
        return $recibo;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {

        $recibo = $entityInstance;
        $contrato = $this->getContratoActual();
        $medidores = $contrato->getPisoId()->getMedidors();
        $medidor = null;
        foreach($medidores as $medidors){
            if ($medidors->getMelTipo() == "Eléctrico"){
                $medidor = $medidors;
                break;
            }
        }
        $request = $this->requestStack->getCurrentRequest();
        $lecturaActual = $request->get('Recibo')['lecturaLuzActual'] ?? null;

        if ((is_numeric($lecturaActual)) == false) {
            $lecturaActual = (float) $lecturaActual;  
        } 

        if ($lecturaActual === null) {
            throw new \InvalidArgumentException('La lectura actual es requerida.');
        }

        // Obtener la última lectura
        $ultimaLectura = $this->lecturaRepository->findOneBy(
            ['medidor_id' => $medidor],
            ['lel_fecha' => 'DESC'],
            ['lel_tipo' => 'Tipo 1']
        );
        $lecturaAnterior = $ultimaLectura ? $ultimaLectura->getLelDato() : 0;
        $consumo = abs($lecturaActual-$lecturaAnterior);
        
        // Crear nueva lectura
        $lectura = new Lectura();
        $lectura->setMedidorId($medidor);
        $lectura->setLelDato($lecturaActual);
        $lectura->setLelTipo('Tipo 1');
        $lectura->setLelEstado(true);
        $lectura->setLelFecha(new \DateTime());
        $entityManager->persist($lectura);
        $entityManager->flush();

        $detalleConsumo = new ReciboDetalleConsumo();
        $detalleConsumo->setRecibo($recibo);
        $detalleConsumo->setRdcConsumo($consumo);
        $detalleConsumo->setRdcSubtotal($consumo*1);
        $detalleConsumo->setRdcTipo(1);
        $detalleConsumo->setLectAntId($ultimaLectura ? $ultimaLectura->getId() : 0);
        $detalleConsumo->setLecActId($lectura->getId());
        $detalleConsumo->setRdcEstado(true);
        $entityManager->persist($detalleConsumo);
        $totalrecibo = $detalleConsumo->getRdcSubtotal()+$contrato->getCoAlquilerMensual()+$contrato->getCoAgua();
        $recibo->setRePagoTotal($totalrecibo);
        $recibo->setContratoId($contrato);
        $entityManager->persist($recibo);
        $entityManager->flush();
        $recibo->setReCodigo('REC_'.$recibo->getId());
        $entityManager->flush();
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
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW);
    }

}
